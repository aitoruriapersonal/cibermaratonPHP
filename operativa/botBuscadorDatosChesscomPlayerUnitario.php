<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\operativa\botBuscadorDatosChesscomPlayer.php

require_once __DIR__ . '/../servicio/AnalizarParticipanteService.php';
require_once __DIR__ . '/../servicio/ParticipantesService.php';
require_once __DIR__ . '/../servicio/ChesscomProfileService.php';
require_once __DIR__ . '/../servicio/ChesscomPlayerStatsService.php';
require_once __DIR__ . '/../servicio/ChesscomPlayerGameService.php';
require_once __DIR__ . '/../servicio/ResultadoService.php';
require_once __DIR__ . '/../utilidades/LogGestor.php';
require_once __DIR__ . '/../servicio/LogAnalisisService.php';

//Servicios API de consulta a chesscom
require_once __DIR__ . '/../servicio/chesscomJSON/ChesscomProfileApiConverter.php';
require_once __DIR__ . '/../servicio/chesscomJSON/ChesscomPlayerStatsApiConverter.php';
require_once __DIR__ . '/../servicio/chesscomJSON/ChesscomPlayerGameApiConverter.php';

//Conversores ChesscomAPI - chesscomModel
require_once __DIR__ . '/../conversores/ChesscomProfileApiConverter.php';
require_once __DIR__ . '/../conversores/ChesscomPlayerStatsApiConverter.php';
require_once __DIR__ . '/../conversores/ChesscomPlayerGameApiConverter.php';

require_once __DIR__ . '/../utilidades/Constantes.php';

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
$resultadoService = new ResultadoService($pdo);
$log = new LogGestor();

$caso = $analizarParticipantesService->obtenerPrimerAnalisisPendiente();
if (!$caso) {
    $log->info("No hay casos pendientes en analizar_participantes. Proceso finalizado.");
    exit;
}

// 1. Cambiar estado a 1 (En Proceso)
$analizarParticipantesService->cambiarEstado($caso->id, 1);
$mensaje = "Analizando participante_id={$caso->participante_id}, analizar_participantes.id={$caso->id}";
    $log->info($mensaje);
    $logAnalisisService->crearLog(new LogAnalisis(
        null,
        $caso->id,
        'INICIO',
        $mensaje
    ));

    // 2. Buscar el participante y su username chesscom
    $participante = $participantesService->getParticipanteById($caso->participante_id);
    if (!$participante || empty($participante->nick)) {
        $mensaje = "Participante no encontrado o sin nick. Se marca como analizado.";
        $log->error($mensaje);
        $analizarParticipantesService->cambiarEstado($caso->id, 2);
        $logAnalisisService->crearLog(new LogAnalisis(
            null,
            $caso->id,
            'ERROR',
            $mensaje
        ));
        continue;
    }

    $username = $participante->nick;

    // 3. Buscar profile en Chess.com
    $profile = $chesscomProfileApiService->fetchProfile($username);
    if (!$profile) {
        $mensaje = "No existe perfil chess.com para nick=$username. Se marca como analizado.";
        $log->warn($mensaje);
        $analizarParticipantesService->cambiarEstado($caso->id, 2);
        $logAnalisisService->crearLog(new LogAnalisis(
            null,
            $caso->id,
            'PROFILE_NO_EXISTE',
            $mensaje
        ));
        continue;
    }
    $logAnalisisService->crearLog(new LogAnalisis(
        null,
        $caso->id,
        'PROFILE_OK',
        "Perfil chess.com encontrado para $username"
    ));

    // Convertir a modelo de tabla
    $profile = ChesscomProfileApiConverter::toChesscomProfile($profileApiModel, $participante->id);

    // Insertar o actualizar chesscom_profile
    if (!$chesscomProfileService->existeProfile($participante->id, $username)) {
        $chesscomProfileService->crearProfile($profile);
        $participantesService->actualizarEstado($participante->id, 3); // Estado 3: perfil chesscom encontrado
        $mensaje = "Insertado nuevo chesscom_profile para $username.";
        $log->info($mensaje);
        $logAnalisisService->crearLog(new LogAnalisis(
                null,
                $caso->id,
                'PROFILE_INSERT',
                $mensaje
            ));
    } else {
        $chesscomProfileService->actualizarProfile($profile);
        $mensaje = "Actualizado chesscom_profile para $username.";
        $log->info($mensaje);
        $logAnalisisService->crearLog(new LogAnalisis(
            null,
            $caso->id,
            'PROFILE_UPDATE',
            $mensaje
        ));
    }

    // 4. Buscar stats en Chess.com usando el servicio API
    $stats = $chesscomPlayerStatsApiService->fetchStats($username);
    if ($stats) {
        $statsApiModel = ChesscomPlayerStatsApiConverter::toChesscomPlayerStats($stats, $participante->id, $username);

        if (!$chesscomPlayerStatsService->existeStats($participante->id)) {
            $chesscomPlayerStatsService->crearStats($statsApiModel);
            $mensaje = "Insertado chesscom_player_stats para $username.";
            $log->info($mensaje);
            $logAnalisisService->crearLog(new LogAnalisis(
                null,
                $caso->id,
                'STATS_INSERT',
                $mensaje
            ));
        } else {
            $chesscomPlayerStatsService->actualizarStats($statsApiModel);
            $mensaje = "Actualizado chesscom_player_stats para $username.";
            $log->info($mensaje);
            $logAnalisisService->crearLog(new LogAnalisis(
                null,
                $caso->id,
                'STATS_UPDATE',
                $mensaje
            ));
        }
    } else {
        $mensaje = "No se pudieron obtener stats para $username.";
        $log->warn($mensaje);
        $logAnalisisService->crearLog(new LogAnalisis(
            null,
            $caso->id,
            'STATS_NO_EXISTE',
            $mensaje
        ));
    }

    // 5. Buscar partidas chesscom
    $gamesApi = $chesscomPlayerGameApiService->fetchGamesPorRitmo($username, Constantes::DIEZ_MINUTOS);
    $logAnalisisService->crearLog(new LogAnalisis(
        null,
        $caso->id,
        'GAMES_API',
        "Partidas chess.com obtenidas para $username: " . count($gamesApi)
    ));
    // 6. Buscar partidas en resultados
    $gamesResultados = $resultadoService->getResultadosPorParticipante($participante->id);
    $logAnalisisService->crearLog(new LogAnalisis(
        null,
        $caso->id,
        'RESULTADOS_BBDD',
        "Resultados en base de datos para $username: " . count($gamesResultados)
    ));

    $numApi = count($gamesApi);
    $numResultados = count($gamesResultados);

    // 7. Comparar tamaños
    if ($numApi === $numResultados) {
        $analizarParticipantesService->cambiarEstado($caso->id, 2);
        $mensaje = "Participante $username: partidas sincronizadas ($numApi). Proceso terminado para este caso.";
        $log->info($mensaje);
        $logAnalisisService->crearLog(new LogAnalisis(
            null,
            $caso->id,
            'GAMES_SYNC',
            $mensaje
        ));
        continue;
    }

    // Insertar partidas nuevas
    $urlsResultados = array_map(fn($r) => $r->url_partida, $gamesResultados);
    $nuevas = 0;
    foreach ($gamesApi as $gameApi) {
        if (!in_array($gameApi->url, $urlsResultados)) {
            // Insertar en chesscom_player_games
            $gameApiModel = ChesscomPlayerGameApiConverter::toChesscomPlayerGame($gameApi, $participante->id, $username);
            $chesscomPlayerGameService->crearGame($gameApiModel);
            // Insertar en resultados
            $resultadoModel = ChesscomGameApiModelToResultadoConverter::toResultado($gameApiModel, $participante->id);
            $resultadoService->crearResultado($resultadoModel);
            $nuevas++;
            $logAnalisisService->crearLog(new LogAnalisis(
                null,
                $caso->id,
                'GAME_INSERT',
                "Insertada nueva partida para $username: {$gameApi->url}"
            ));
            // Si es la primera vez que se inserta una partida, actualizar estado a 1 (Activo)
            if ($numResultados == 0 && $nuevas == 1) {
                $participantesService->actualizarEstado($participante->id, 1);
                $mensaje = "Participante $username pasa a estado 1 (Activo).";
                $log->info($mensaje);
                $logAnalisisService->crearLog(new LogAnalisis(
                    null,
                    $caso->id,
                    'PARTICIPANTE_ACTIVO',
                    $mensaje
                ));
            }
            // Si es la partida 42, actualizar estado a 0 (Terminado)
            if (($numResultados + $nuevas) == 42) {
                $participantesService->actualizarEstado($participante->id, 0);
                $mensaje = "Participante $username pasa a estado 0 (Terminado) al alcanzar 42 partidas.";
                $log->info($mensaje);
                $logAnalisisService->crearLog(new LogAnalisis(
                    null,
                    $caso->id,
                    'PARTICIPANTE_TERMINADO',
                    $mensaje
                ));
            }
        }
    }

    $analizarParticipantesService->cambiarEstado($caso->id, 2);
    $mensaje = "Finalizado análisis para participante_id={$participante->id}. Partidas nuevas insertadas: $nuevas.";
    $log->info($mensaje);
    $logAnalisisService->crearLog(new LogAnalisis(
        null,
        $caso->id,
        'FIN',
        $mensaje
    ));

