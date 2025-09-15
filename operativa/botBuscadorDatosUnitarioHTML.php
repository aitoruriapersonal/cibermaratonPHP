<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\operativa\botBuscadorDatosChesscomPlayer.php

require_once __DIR__ . '/../servicio/AnalizarParticipanteService.php';
require_once __DIR__ . '/../servicio/ParticipantesService.php';
require_once __DIR__ . '/../servicio/ChesscomProfileService.php';
require_once __DIR__ . '/../servicio/ChesscomPlayerStatsService.php';
require_once __DIR__ . '/../servicio/ChesscomPlayerGameService.php';
require_once __DIR__ . '/../servicio/ResultadosService.php';
require_once __DIR__ . '/../servicio/LogAnalisisService.php';

//Servicios API de consulta a chesscom
require_once __DIR__ . '/../servicio/chesscomJSON/ChesscomProfileApiService.php';
require_once __DIR__ . '/../servicio/chesscomJSON/ChesscomPlayerStatsApiService.php';
require_once __DIR__ . '/../servicio/chesscomJSON/ChesscomPlayerGameApiService.php';

//Conversores ChesscomAPI - chesscomModel
require_once __DIR__ . '/../conversores/ChesscomProfileApiConverter.php';
require_once __DIR__ . '/../conversores/ChesscomPlayerStatsApiConverter.php';
require_once __DIR__ . '/../conversores/ChesscomPlayerGameApiConverter.php';
require_once __DIR__ . '/../conversores/ChesscomPlayerGameApiToResultadoBdConverter.php';

//Utilidades
require_once __DIR__ . '/../utilidades/Constantes.php';
require_once __DIR__ . '/../utilidades/LogGestor.php';

// Configuración de conexión PDO (ajusta los datos a tu entorno)
$pdo = new PDO('mysql:host=localhost;dbname=cibermaraton', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Instanciar servicios y log
$logAnalisisService = new LogAnalisisService($pdo);
$analizarParticipantesService = new AnalizarParticipanteService($pdo);
$participantesService = new ParticipantesService($pdo);
$chesscomProfileService = new ChesscomProfileService($pdo);
$chesscomPlayerStatsService = new ChesscomPlayerStatsService($pdo);
$chesscomPlayerGameService = new ChesscomPlayerGameService($pdo);
$resultadosService = new ResultadosService($pdo);

// Servicios API de consulta a chesscom
$chesscomProfileApiService = new ChesscomProfileApiService();
$chesscomPlayerStatsApiService = new ChesscomPlayerStatsApiService();
$chesscomPlayerGameApiService = new ChesscomPlayerGameApiService();

// Log
$log = new LogGestor();

echo '<br/> INICIO';
$caso = $analizarParticipantesService->obtenerPrimerAnalisisPendiente();
if (!$caso) {
    $log->info("No hay casos pendientes en analizar_participantes. Proceso finalizado.");
    echo '<br/> No hay casos pendientes en analizar_participantes. Proceso finalizado.';
    exit;
}

// 1. Cambiar estado a 1 (En Proceso)
echo '<br/> En paso 1';
$analizarParticipantesService->cambiarEstado($caso->id, 1);
$mensaje = "Analizando participante_id={$caso->participante_id}, analizar_participantes.id={$caso->id}";
    $log->info($mensaje);
    echo '<br/> ' . $mensaje;
    $logAnalisisService->crearLog(new LogAnalisis(
        null,
        $caso->id,
        'INICIO',
        $mensaje
    ));

    // 2. Buscar el participante y su username chesscom
    echo '<br/> En paso 2';
    $participante = $participantesService->getParticipanteById($caso->participante_id);
    if (!$participante || empty($participante->nick)) {
        $mensaje = "Participante no encontrado o sin nick. Se marca como analizado.";
        $log->error($mensaje);
        echo '<br/> ' . $mensaje;
        $analizarParticipantesService->cambiarEstado($caso->id, 2);
        $logAnalisisService->crearLog(new LogAnalisis(
            null,
            $caso->id,
            'ERROR',
            $mensaje
        ));
        $analizarParticipantesService->cambiarEstado($caso->id, 3);
        exit;
    }

    $username = $participante->nick;

    // 3. Buscar profile en Chess.com
    echo '<br/> En paso 3';
    $profile = $chesscomProfileApiService->fetchProfile($username, $participante->id);
    if (!$profile) {
        $mensaje = "No existe perfil chess.com para nick=$username. Se marca como analizado.";
        $log->warn($mensaje);
        echo '<br/> ' . $mensaje;
        $analizarParticipantesService->cambiarEstado($caso->id, 2);
        $logAnalisisService->crearLog(new LogAnalisis(
            null,
            $caso->id,
            'PROFILE_NO_EXISTE',
            $mensaje
        ));
        $analizarParticipantesService->cambiarEstado($caso->id, 3);
        exit;
    }
    $mensaje = "Perfil chess.com encontrado para $username";
    $log->info($mensaje);
    echo '<br/> ' . $mensaje;
    $logAnalisisService->crearLog(new LogAnalisis(
        null,
        $caso->id,
        'PROFILE_OK',
        $mensaje
    ));

    // Convertir a modelo de tabla
    echo '<br/> En paso 3: Convertir a modelo de tabla';
    $profileApiModel = ChesscomProfileApiConverter::toChesscomProfile($profile, $participante->id);

    // Insertar o actualizar chesscom_profile
    echo '<br/> En paso 3: Insertar o actualizar chesscom_profile';
    if (!$chesscomProfileService->existeProfile($participante->id, $username)) {
        $chesscomProfileService->crearProfile($profileApiModel);
        $participantesService->actualizarEstado($participante->id, 3); // Estado 3: perfil chesscom encontrado
        $mensaje = "Insertado nuevo chesscom_profile para $username.";
        $log->info($mensaje);
        echo '<br/> ' . $mensaje;
        $logAnalisisService->crearLog(new LogAnalisis(
                null,
                $caso->id,
                'PROFILE_INSERT',
                $mensaje
            ));
    } else {
        $chesscomProfileService->actualizarProfile($profileApiModel);
        $mensaje = "Actualizado chesscom_profile para $username.";
        $log->info($mensaje);
        echo '<br/> ' . $mensaje;
        $logAnalisisService->crearLog(new LogAnalisis(
            null,
            $caso->id,
            'PROFILE_UPDATE',
            $mensaje
        ));
    }

    // 4. Buscar stats en Chess.com usando el servicio API
    echo '<br/> En paso 4';
    $stats = $chesscomPlayerStatsApiService->fetchStats($username);
    if ($stats) {
        $statsApiModel = ChesscomPlayerStatsApiConverter::toChesscomPlayerStats($stats, $participante);

        if (!$chesscomPlayerStatsService->existeStats($participante->id)) {
            $chesscomPlayerStatsService->crearStats($statsApiModel);
            $mensaje = "Insertado chesscom_player_stats para $username.";
            $log->info($mensaje);
            echo '<br/> ' . $mensaje;
            $logAnalisisService->crearLog(new LogAnalisis(
                null,
                $caso->id,
                'STATS_INSERT',
                $mensaje
            ));
        } else {
            $chesscomPlayerStatsService->actualizarStatsParticipante($statsApiModel);
            $mensaje = "Actualizado chesscom_player_stats para $username.";
            $log->info($mensaje);
            echo '<br/> ' . $mensaje;
            $logAnalisisService->crearLog(new LogAnalisis(
                null,
                $caso->id,
                'STATS_UPDATE',
                $mensaje
            ));
        }
        if($statsApiModel->rapid_last_rating !=null && $statsApiModel->rapid_last_rating > 0
            && $statsApiModel->rapid_last_rating != $participante->puntos){
            $participante = $participantesService->actualizarPuntos($participante->id, $statsApiModel->rapid_last_rating);
        }
    } else {
        $mensaje = "No se pudieron obtener stats para $username.";
        $log->warn($mensaje);
        echo '<br/> ' . $mensaje;
        $logAnalisisService->crearLog(new LogAnalisis(
            null,
            $caso->id,
            'STATS_NO_EXISTE',
            $mensaje
        ));
    }

    // 5. Buscar partidas chesscom
    echo '<br/> En paso 5';
    $gamesApi = $chesscomPlayerGameApiService->fetchGamesPorRitmo($username, Constantes::DIEZ_MINUTOS);
    $mensaje="Partidas chess.com obtenidas para $username: " . count($gamesApi);
    $log->info($mensaje);
    echo '<br/> ' . $mensaje;
    $logAnalisisService->crearLog(new LogAnalisis(
        null,
        $caso->id,
        'GAMES_API',
        $mensaje
    ));
    // 6. Buscar partidas en resultados
    echo '<br/> En paso 6';
    $gamesResultados = $resultadoService->getResultadosPorParticipante($participante->id);

    $mensaje = "Resultados en base de datos para $username: " . count($gamesResultados);
    $log->info($mensaje);
    echo '<br/> ' . $mensaje;
    $logAnalisisService->crearLog(new LogAnalisis(
        null,
        $caso->id,
        'RESULTADOS_BBDD',
        $mensaje
    ));

    $numApi = count($gamesApi);
    $numResultados = count($gamesResultados);

    // 7. Comparar tamaños
    echo '<br/> En paso 7: comparar tamaños resultados vs partidas chesscom';
    if ($numApi === $numResultados) {
        $analizarParticipantesService->cambiarEstado($caso->id, 2);
        $mensaje = "Participante $username: partidas sincronizadas ($numApi). Proceso terminado para este caso.";
        $log->info($mensaje);
        echo '<br/> ' . $mensaje;
        $logAnalisisService->crearLog(new LogAnalisis(
            null,
            $caso->id,
            'GAMES_SYNC',
            $mensaje
        ));
        $analizarParticipantesService->cambiarEstado($caso->id, 2);
        exit;
    }

    // Insertar partidas nuevas
    echo '<br/> En paso 7: Insertar partidas nuevas';
    $urlsResultados = array_map(fn($r) => $r->url_partida, $gamesResultados);
    $nuevas = 0;
    foreach ($gamesApi as $gameApi) {
        if (!in_array($gameApi->url, $urlsResultados)) {
            // Insertar en chesscom_player_games
            $gameApiModel = ChesscomPlayerGameApiConverter::toChesscomPlayerGame($gameApi, $participante->id, $username);
            $log->info(" gameApiModel: ".$gameApiModel->end_time);
            $chesscomPlayerGameService->crearGame($gameApiModel);
            // Insertar en resultados
            $resultadoModel = ChesscomGameApiModelToResultadoBdConverter::toResultado($gameApi, $participante);
            $log->info(" gameApi: ".$gameApi->end_time);
            if($numResultados == null){
                $numResultados = 0;
            }
            $numResultados++;
            $resultadoModel->numero_partida = $numResultados;
            $resultadoService->crearResultado($resultadoModel);
            $nuevas++;
            $mensaje = "Insertada nueva partida (Num: $numResultados) para $username: {$gameApi->url}";
            $log->info($mensaje);
            echo '<br/> ' . $mensaje;
            $logAnalisisService->crearLog(new LogAnalisis(
                null,
                $caso->id,
                'GAME_INSERT',
                $mensaje
            ));
            //Actualizar puntos del participante si ha cambiado
            if($resultadoModel->elo !=null && $resultadoModel->elo > 0
                && $resultadoModel->elo != $participante->puntos){
                $puntos = ($numResultados * 1000) + $resultadoModel->elo;
                $participante = $participantesService->actualizarPuntos($participante->id, $puntos);
                $mensaje = "Actualizados puntos del participante $username a {$resultadoModel->elo} tras insertar nueva partida. Numero de partidas: $numResultados. Puntos totales: $puntos.";
                $log->info($mensaje);
                echo '<br/> ' . $mensaje;
                $logAnalisisService->crearLog(new LogAnalisis(
                    null,
                    $caso->id,
                    'PARTICIPANTE_PUNTOS_ACTUALIZADOS',
                    $mensaje
                ));
            }
            // Si es la primera vez que se inserta una partida, actualizar estado a 1 (Activo)
            if ($numResultados == 1) {
                $participantesService->actualizarEstado($participante->id, 1);
                $mensaje = "Participante $username pasa a estado 1 (Activo).";
                $log->info($mensaje);
                echo '<br/> ' . $mensaje;
                $logAnalisisService->crearLog(new LogAnalisis(
                    null,
                    $caso->id,
                    'PARTICIPANTE_ACTIVO',
                    $mensaje
                ));
            }
            // Si es la partida 42, actualizar estado a 0 (Terminado)
            if ($numResultados == 42) {
                $participantesService->actualizarEstadoTerminado($participante);
                $mensaje = "Participante $username pasa a estado 0 (Terminado) al alcanzar 42 partidas.";
                $log->info($mensaje);
                echo '<br/> ' . $mensaje;
                $logAnalisisService->crearLog(new LogAnalisis(
                    null,
                    $caso->id,
                    'PARTICIPANTE_TERMINADO',
                    $mensaje
                ));
                 echo '<br/> Partida 42 => FIN tratamiento de partidas para este participante.';
                break;
            }
        }
    }

    $analizarParticipantesService->cambiarEstado($caso->id, 2);
    $mensaje = "Finalizado análisis para participante_id={$participante->id}. Partidas nuevas insertadas: $nuevas.";
    $log->info($mensaje);
    echo '<br/> ' . $mensaje;
    $logAnalisisService->crearLog(new LogAnalisis(
        null,
        $caso->id,
        'FIN',
        $mensaje
    ));
    echo '<br/> FIN';
