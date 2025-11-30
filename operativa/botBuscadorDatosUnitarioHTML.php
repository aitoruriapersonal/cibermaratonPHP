<?php
// filepath: c:\xampp\htdocs\deporteuniversitario\ajedrez\backend\operativa\botBuscadorDatosUnitarioHTML.php

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

// AGREGAR VERIFICACIÓN DE ARCHIVOS Y CLASES
echo '<br/>VERIFICANDO archivos requeridos...';
$archivosRequeridos = [
    __DIR__ . '/../conversores/ChesscomPlayerGameApiConverter.php',
    __DIR__ . '/../conversores/ChesscomPlayerGameApiToResultadoBdConverter.php',
    __DIR__ . '/../modelo/ChesscomPlayerGame.php',
    __DIR__ . '/../modelo/Resultados.php'  // CAMBIADO: de Resultado.php a Resultados.php
];

foreach ($archivosRequeridos as $archivo) {
    if (file_exists($archivo)) {
        echo '<br/>✓ ' . basename($archivo) . ' existe';
    } else {
        echo '<br/>✗ ' . basename($archivo) . ' NO EXISTE';
        exit('Error: Archivo requerido no encontrado: ' . $archivo);
    }
}

$clasesRequeridas = [
    'ChesscomPlayerGameApiConverter',
    'ChesscomPlayerGameApiToResultadoBdConverter',
    'ChesscomPlayerGame',
    'Resultado'  // La clase sigue siendo Resultado, pero está en Resultados.php
];

foreach ($clasesRequeridas as $clase) {
    if (class_exists($clase)) {
        echo '<br/>✓ Clase ' . $clase . ' existe';
    } else {
        echo '<br/>✗ Clase ' . $clase . ' NO EXISTE';
    }
}

$esLocal = false;
// Conexión PDO (ajusta los datos a tu entorno)
try {
    if ($esLocal) {
        $dsn = 'mysql:host=127.0.0.1;dbname=cibermaraton;charset=utf8mb4';
        $usuario = 'root';
        $password = '';
    } else {
        $dbHost = "db552696640.db.1and1.com";
        $dbUsuario = "dbo552696640";
        $dbPass = "Elefante3000";
        $dbSchema = "db552696640";
        $dsn = "mysql:host={$dbHost};dbname={$dbSchema};charset=utf8mb4";
        $usuario = $dbUsuario;
        $password = $dbPass;
    }

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
    ];

    $pdo = new PDO($dsn, $usuario, $password, $options);
} catch (PDOException $e) {
    error_log('DB connection error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Database connection error']);
    exit;
}

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

// Instanciar conversores
$chesscomPlayerGameApiConverter = new ChesscomPlayerGameApiConverter();
$chesscomPlayerGameApiToResultadoBdConverter = new ChesscomPlayerGameApiToResultadoBdConverter();

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
    $statsApiModel = $chesscomPlayerStatsApiService->fetchPlayerStats($username, $participante->id);
    if ($statsApiModel) {
        // Usar directamente el modelo API o crear un converter específico
        $statsModel = ChesscomPlayerStatsApiConverter::toChesscomPlayerStats($statsApiModel, $participante);

        if (!$chesscomPlayerStatsService->existeStats($participante->id)) {
            $chesscomPlayerStatsService->crearStats($statsModel);
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
            $chesscomPlayerStatsService->actualizarStatsParticipante($statsModel);
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
        
        // Verificar que usamos la propiedad correcta del modelo API
        if($statsApiModel->chess_rapid_last_rating != null && $statsApiModel->chess_rapid_last_rating > 0
            && $statsApiModel->chess_rapid_last_rating != $participante->puntos){
            $participante = $participantesService->actualizarPuntos($participante->id, $statsApiModel->chess_rapid_last_rating);
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
    $gamesResultados = $resultadosService->getResultadosPorParticipante($participante->id);

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
    echo '<br/> DEBUG: $numApi = ' . $numApi;
echo '<br/> DEBUG: $numResultados = ' . $numResultados;

// NUEVO: Verificar límite de 42 partidas
const LIMITE_MAXIMO_PARTIDAS = 42;

echo '<br/>DEBUG: Verificando límite de partidas...';
echo '<br/>  - Partidas existentes en BD: ' . $numResultados;
echo '<br/>  - Partidas disponibles en API: ' . $numApi;
echo '<br/>  - Límite máximo permitido: ' . LIMITE_MAXIMO_PARTIDAS;

if ($numResultados >= LIMITE_MAXIMO_PARTIDAS) {
    $analizarParticipantesService->cambiarEstado($caso->id, 2);
    $mensaje = "Participante $username: ya tiene el máximo de partidas permitidas ($numResultados/$LIMITE_MAXIMO_PARTIDAS). Proceso terminado.";
    $log->info($mensaje);
    echo '<br/> ' . $mensaje;
    $logAnalisisService->crearLog(new LogAnalisis(
        null,
        $caso->id,
        'LIMITE_ALCANZADO',
        $mensaje
    ));
    exit;
}

// Calcular cuántas partidas se pueden insertar
$partidasDisponiblesParaInsertar = LIMITE_MAXIMO_PARTIDAS - $numResultados;
echo '<br/>DEBUG: Se pueden insertar máximo ' . $partidasDisponiblesParaInsertar . ' partidas más';

if ($numApi === $numResultados) {
    $analizarParticipantesService->cambiarEstado($caso->id, 2);
    $mensaje = "Participante $username: partidas sincronizadas ($numApi/$LIMITE_MAXIMO_PARTIDAS). Proceso terminado para este caso.";
    $log->info($mensaje);
    echo '<br/> ' . $mensaje;
    $logAnalisisService->crearLog(new LogAnalisis(
        null,
        $caso->id,
        'GAMES_SYNC',
        $mensaje
    ));
    exit;
}

// Insertar partidas nuevas (con límite)
echo '<br/> En paso 7: Insertar partidas nuevas (máximo ' . $partidasDisponiblesParaInsertar . ')';

// CORREGIR: Obtener URLs de chesscom_player_games en lugar de resultados
echo '<br/>DEBUG: Obteniendo partidas existentes de chesscom_player_games...';
$gamesExistentes = $chesscomPlayerGameService->obtenerGamesPorParticipanteId($participante->id);
$urlsExistentes = [];

echo '<br/>JOJOJ';
foreach ($gamesExistentes as $gameExistente) {
    if (property_exists($gameExistente, 'url') && !empty($gameExistente->url)) {
        $urlsExistentes[] = $gameExistente->url;
    } elseif (property_exists($gameExistente, 'chesscomGameUrl') && !empty($gameExistente->chesscomGameUrl)) {
        $urlsExistentes[] = $gameExistente->chesscomGameUrl;
    }
}

echo '<br/> DEBUG: URLs existentes en chesscom_player_games (' . count($urlsExistentes) . '):';
foreach ($urlsExistentes as $url) {
    echo '<br/> - ' . $url;
}

// ALTERNATIVA: Si las URLs de resultados y chesscom_player_games son diferentes, usar ambas
$urlsResultados = array_map(fn($r) => $r->url_partida, $gamesResultados);
$todasLasUrlsExistentes = array_merge($urlsExistentes, $urlsResultados);
$todasLasUrlsExistentes = array_unique($todasLasUrlsExistentes); // Eliminar duplicados

echo '<br/> DEBUG: Total URLs existentes (games + resultados): ' . count($todasLasUrlsExistentes);

echo '<br/> DEBUG: URLs en API (' . count($gamesApi) . '):';
$nuevas = 0;

// OBTENER el número máximo de partida ANTES del bucle
echo '<br/>DEBUG: Obteniendo número máximo de partida para participante_id=' . $participante->id;
$numeroPartidaBase = $resultadosService->obtenerUltimoNumeroPartida($participante->id);
echo '<br/>DEBUG: Número base de partida: ' . $numeroPartidaBase;

$contadorPartidas = 0; // Contador para las nuevas partidas insertadas
$partidasProcesadas = 0; // Contador de partidas procesadas del API

foreach ($gamesApi as $gameApi) {
    echo '<br/> - API URL: ' . ($gameApi->url ?? 'NULL');
    
    // VERIFICAR DUPLICADOS usando la lista completa de URLs
    $yaExiste = in_array($gameApi->url, $todasLasUrlsExistentes);
    echo '<br/> - ¿Existe en BD?: ' . ($yaExiste ? 'SÍ' : 'NO');
    
    // VERIFICAR LÍMITE ANTES DE PROCESAR
    if ($contadorPartidas >= $partidasDisponiblesParaInsertar) {
        echo '<br/>🛑 LÍMITE DE PARTIDAS ALCANZADO: ' . $contadorPartidas . '/' . $partidasDisponiblesParaInsertar;
        echo '<br/>   Omitiendo partidas restantes por límite de ' . LIMITE_MAXIMO_PARTIDAS . ' partidas máximas por jugador';
        break; // Salir del bucle
    }
    
    if (!$yaExiste) {
        $partidasProcesadas++;
        echo '<br/> - INSERTANDO partida ' . $partidasProcesadas . ': ' . ($gameApi->url ?? 'NULL');
        
        // DEBUG: Mostrar datos del modelo API ANTES de conversión
        echo '<br/>DEBUG: Datos del gameApi antes de conversión:';
        echo '<br/>  - Tipo de objeto: ' . get_class($gameApi);
        echo '<br/>  - URL: ' . ($gameApi->url ?? 'NULL');
        echo '<br/>  - Participante ID: ' . ($gameApi->participante_id ?? 'NULL');
        
        // Convertir a ChesscomPlayerGame
        echo '<br/>DEBUG: INICIANDO CONVERSIÓN...';
        $chesscomPlayerGame = $chesscomPlayerGameApiConverter->convert($gameApi, $participante->id, $username);
        echo '<br/>DEBUG: CONVERSIÓN TERMINADA';
        
        echo '<br/> - DEBUG conversión a ChesscomPlayerGame: ' . ($chesscomPlayerGame ? 'SÍ' : 'NO');
        
        // AGREGAR DEBUG DETALLADO AQUÍ:
        echo '<br/>DEBUG DETALLADO DESPUÉS DE CONVERSIÓN:';
        echo '<br/>  - $chesscomPlayerGame es null?: ' . ($chesscomPlayerGame === null ? 'SÍ' : 'NO');
        echo '<br/>  - $chesscomPlayerGame es false?: ' . ($chesscomPlayerGame === false ? 'SÍ' : 'NO');
        echo '<br/>  - Tipo de $chesscomPlayerGame: ' . gettype($chesscomPlayerGame);
        if ($chesscomPlayerGame !== null) {
            echo '<br/>  - Clase de $chesscomPlayerGame: ' . get_class($chesscomPlayerGame);
            echo '<br/>  - participanteId en objeto: ' . ($chesscomPlayerGame->participanteId ?? 'NO EXISTE');
            echo '<br/>  - chesscomGameUrl en objeto: ' . ($chesscomPlayerGame->chesscomGameUrl ?? 'NO EXISTE');
        }
        echo '<br/>  - Evaluación booleana: ' . ($chesscomPlayerGame ? 'TRUE' : 'FALSE');

        if ($chesscomPlayerGame) {
            echo '<br/>ENTRANDO EN EL IF - A insertar partida para participante_id=' . $participante->id . ', username=' . $username;
            
            // VERIFICACIÓN ADICIONAL: Comprobar si ya existe por URL antes de insertar
            if ($chesscomPlayerGameService->existeGamePorUrl($gameApi->url)) {
                echo '<br/>⚠ ADVERTENCIA: La partida ya existe en BD, omitiendo inserción';
                continue; // Saltar al siguiente elemento del bucle
            }
            
            // Insertar en chesscom_player_games
            $insertResult = $chesscomPlayerGameService->crearGame($chesscomPlayerGame, $username);
            echo '<br/> - DEBUG inserción chesscom_player_games: ' . ($insertResult ? 'ÉXITO' : 'FALLO');
            
            if ($insertResult) {
                echo '<br/>✓ INSERCIÓN EN chesscom_player_games EXITOSA - ID: ' . $insertResult;
                
                // Añadir URL a la lista de existentes para evitar duplicados en el mismo bucle
                $todasLasUrlsExistentes[] = $gameApi->url;
                
                echo '<br/>ENTRANDO EN EL IF - A insertar resultado para participante_id=' . $participante->id . ', username=' . $username;
                
                // DEBUG: Verificar el convertidor antes de usar
                if (!$chesscomPlayerGameApiToResultadoBdConverter) {
                    echo '<br/>ERROR: $chesscomPlayerGameApiToResultadoBdConverter es null';
                } else {
                    echo '<br/>✓ Convertidor ChesscomPlayerGameApiToResultadoBdConverter existe';
                    echo '<br/>  - Clase: ' . get_class($chesscomPlayerGameApiToResultadoBdConverter);
                }
                
                // DEBUG conversión a resultado
                echo '<br/>DEBUG: Ejecutando debugConversion...';
                $chesscomPlayerGameApiToResultadoBdConverter->debugConversion($gameApi, $username);
                echo '<br/>DEBUG: debugConversion completado';
                
                // Convertir a Resultado
                echo '<br/>DEBUG: Iniciando conversión a Resultado...';
                try {
                    $resultado = $chesscomPlayerGameApiToResultadoBdConverter->convert($gameApi, $username);
                    echo '<br/>DEBUG: Conversión a Resultado completada';
                } catch (Exception $e) {
                    echo '<br/>ERROR EXCEPTION en conversión a Resultado: ' . $e->getMessage();
                    echo '<br/>  - Línea: ' . $e->getLine();
                    echo '<br/>  - Archivo: ' . $e->getFile();
                    $resultado = null;
                } catch (Error $e) {
                    echo '<br/>ERROR FATAL en conversión a Resultado: ' . $e->getMessage();
                    echo '<br/>  - Línea: ' . $e->getLine();
                    echo '<br/>  - Archivo: ' . $e->getFile();
                    $resultado = null;
                }
                
                echo '<br/> - DEBUG conversión a resultado: ' . ($resultado ? 'SÍ' : 'NO');
                
                if ($resultado) {
                    echo '<br/>✓ RESULTADO CREADO: ' . $resultado->__toString();
                    
                    // Establecer participante_id usando el método correcto
                    $resultado->setParticipanteId($participante->id);
                    echo '<br/>DEBUG: Asignado participante_id=' . $participante->id . ' al resultado';
                    
                    // CALCULAR número de partida incremental (SIN consulta a BD)
                    $contadorPartidas++; // Incrementar contador de nuevas partidas
                    $numeroPartidaActual = $numeroPartidaBase + $contadorPartidas;
                    
                    echo '<br/>DEBUG: Número de partida calculado: ' . $numeroPartidaActual . ' (base: ' . $numeroPartidaBase . ' + contador: ' . $contadorPartidas . ')';
                    echo '<br/>DEBUG: Partidas totales después de esta inserción: ' . ($numResultados + $contadorPartidas) . '/' . LIMITE_MAXIMO_PARTIDAS;
                    
                    // Establecer el número de partida en el objeto resultado
                    $resultado->setNumeroPartida($numeroPartidaActual);
                    echo '<br/>DEBUG: Número de partida asignado: ' . $numeroPartidaActual;
                    
                    try {
                        $insertResultadoResult = $resultadosService->crearResultado($resultado);
                        echo '<br/> - DEBUG inserción resultados: ' . ($insertResultadoResult ? 'ÉXITO' : 'FALLO');
                        
                        if ($insertResultadoResult) {
                            $nuevas++;
                            $totalPartidas = $numResultados + $nuevas;
                            
                            echo '<br/> - ✓ PARTIDA COMPLETAMENTE INSERTADA (número: ' . $numeroPartidaActual . ')';
                            echo '<br/> - ✓ Total partidas del jugador: ' . $totalPartidas . '/' . LIMITE_MAXIMO_PARTIDAS;
                            
                            // ACTUALIZAR ELO del participante con el ELO del último resultado insertado
                            echo '<br/>DEBUG: Actualizando ELO del participante...';
                            echo '<br/>  - ELO anterior del participante: ' . $participante->puntos;
                            echo '<br/>  - ELO de la partida recién insertada: ' . $resultado->elo;
                            
                            if ($resultado->elo > 0) {
                                try {
                                    $participanteActualizado = $participantesService->actualizarPuntos($participante->id, $resultado->elo);
                                    
                                    if ($participanteActualizado) {
                                        $eloAnterior = $participante->puntos;
                                        $participante = $participanteActualizado; // Actualizar objeto local
                                        echo '<br/>✓ ELO del participante actualizado de ' . $eloAnterior . ' a ' . $resultado->elo;
                                        
                                        // Log de la actualización de ELO
                                        $logAnalisisService->crearLog(new LogAnalisis(
                                            null,
                                            $caso->id,
                                            'ELO_ACTUALIZADO',
                                            "ELO actualizado para participante_id={$participante->id}: {$eloAnterior} → {$resultado->elo}"
                                        ));
                                    } else {
                                        echo '<br/>⚠ WARNING: No se pudo actualizar el ELO del participante';
                                    }
                                } catch (Exception $e) {
                                    echo '<br/>ERROR actualizando ELO del participante: ' . $e->getMessage();
                                }
                            } else {
                                echo '<br/>DEBUG: ELO no actualizado (ELO de la partida es 0 o negativo)';
                            }
                            
                            // VERIFICAR si ha completado las 42 partidas
                            if ($totalPartidas >= LIMITE_MAXIMO_PARTIDAS) {
                                echo '<br/><br/>🎉 ¡PARTICIPANTE HA COMPLETADO LAS ' . LIMITE_MAXIMO_PARTIDAS . ' PARTIDAS!';
                                echo '<br/>   Procesando finalización del maratón...';
                                
                                try {
                                    // 1. CAMBIAR ESTADO A 0 (finalizado) Y TERMINADO A 1
                                    echo '<br/>DEBUG: Cambiando estado a 0 y terminado a 1...';
                                    $participantesService->actualizarEstado($participante->id, 0);
                                    $participantesService->actualizarEstadoTerminado($participante->id, 1);
                                    $participante->estado = 0;
                                    $participante->terminado = 1;
                                    echo '<br/>✓ Estado actualizado a 0 (finalizado)';
                                    echo '<br/>✓ Terminado actualizado a 1 (completado)';
                                    
                                    // 2. ASIGNAR CRÉDITOS SI ES DE LA EHU
                                    require_once __DIR__ . '/../utilidades/Utils.php';
                                    
                                    $creditosTotales = 0;
                                    $esEHU = strpos($participante->nick, Utils::NICK_EHU) === 0;
                                    
                                    if ($esEHU) {
                                        $creditosTotales = 1;
                                        echo '<br/>DEBUG: Participante de la EHU - Asignando 1 crédito';
                                        echo '<br/>✓ Crédito asignado: ' . $creditosTotales;
                                    } else {
                                        echo '<br/>DEBUG: Participante NO es de la EHU - Sin créditos';
                                    }
                                    
                                    // 3. ENVIAR EMAIL DE FINALIZACIÓN
                                    if (!empty($participante->email)) {
                                        echo '<br/>DEBUG: Enviando email de finalización...';
                                        echo '<br/>  - Destinatario: ' . $participante->email;
                                        echo '<br/>  - Nick: ' . $participante->nick;
                                        echo '<br/>  - Créditos: ' . $creditosTotales;
                                        
                                        Utils::emailFinMaraton($participante->email, $participante->nick, $creditosTotales);
                                        echo '<br/>✅ Email de finalización enviado correctamente';
                                        
                                        // Log del envío del email
                                        $logAnalisisService->crearLog(new LogAnalisis(
                                            null,
                                            $caso->id,
                                            'EMAIL_FIN_MARATON',
                                            "Email de finalización enviado. Email: {$participante->email}, Nick: {$participante->nick}, Créditos: {$creditosTotales}, Total partidas: {$totalPartidas}"
                                        ));
                                        
                                    } else {
                                        echo '<br/>⚠ WARNING: Participante no tiene email configurado';
                                        echo '<br/>   - Nick: ' . $participante->nick;
                                        echo '<br/>   - ID: ' . $participante->id;
                                        
                                        $logAnalisisService->crearLog(new LogAnalisis(
                                            null,
                                            $caso->id,
                                            'EMAIL_SIN_DESTINATARIO',
                                            "Participante {$participante->nick} (ID: {$participante->id}) completó las partidas pero no tiene email"
                                        ));
                                    }
                                    
                                    // 4. LOG FINAL DE COMPLETITUD
                                    $logAnalisisService->crearLog(new LogAnalisis(
                                        null,
                                        $caso->id,
                                        'MARATON_COMPLETADO',
                                        "Participante {$participante->nick} (ID: {$participante->id}) completó el maratón. Estado: 0, Terminado: 1, ELO: {$participante->puntos}, Créditos: {$creditosTotales}"
                                    ));
                                    
                                } catch (Exception $e) {
                                    echo '<br/>ERROR en finalización del maratón: ' . $e->getMessage();
                                    echo '<br/>   - Línea: ' . $e->getLine();
                                    echo '<br/>   - Archivo: ' . $e->getFile();
                                    
                                    $logAnalisisService->crearLog(new LogAnalisis(
                                        null,
                                        $caso->id,
                                        'ERROR_FINALIZACION',
                                        "Error finalizando maratón para {$participante->nick}: " . $e->getMessage()
                                    ));
                                }
                                
                                // Marcar que el proceso debe terminar ya que alcanzó el límite
                                echo '<br/><br/>🏁 MARATÓN COMPLETADO - Finalizando procesamiento...';
                                break; // Salir del bucle de inserción de partidas
                            }
                            
                        } else {
                            echo '<br/> - ✗ ERROR: Falló inserción en resultados';
                            $contadorPartidas--; // Revertir contador si falló la inserción
                        }
                    } catch (Exception $e) {
                        echo '<br/>ERROR en inserción de resultado: ' . $e->getMessage();
                        $contadorPartidas--; // Revertir contador si falló la inserción
                    }
                } else {
                    echo '<br/> - ✗ ERROR: No se pudo convertir a resultado';
                }
            } else {
                echo '<br/> - ✗ ERROR: No se pudo insertar en chesscom_player_games';
            }
        } else {
            echo '<br/> - ✗ ERROR: No se pudo convertir API model a ChesscomPlayerGame';
        }
    } else {
        echo '<br/>⏩ Partida ya existe: ' . $gameApi->url;
    }
}

// NUEVO: Verificar estado del participante antes de procesar
echo '<br/>DEBUG: Verificando estado del participante...';
echo '<br/>  - Estado actual: ' . $participante->estado;
echo '<br/>  - Terminado: ' . ($participante->terminado ?? 'NULL');
echo '<br/>  - Nombre: ' . $participante->nombre;
echo '<br/>  - Nick: ' . $participante->nick;

// Solo procesar si el estado NO es 5 (deshabilitado) ni 0 (finalizado) y terminado != 1
if ($participante->estado == 5 || $participante->estado == 0 || ($participante->terminado ?? 0) == 1) {
    $motivo = '';
    if ($participante->estado == 5) {
        $motivo = 'deshabilitado (estado 5)';
    } elseif ($participante->estado == 0) {
        $motivo = 'finalizado (estado 0)';
    } elseif (($participante->terminado ?? 0) == 1) {
        $motivo = 'maratón completado (terminado 1)';
    }
    
    echo '<br/>⚠️ PARTICIPANTE ' . strtoupper($motivo) . ' - NO SE PROCESARÁN NUEVAS PARTIDAS';
    
    $mensaje = "Participante {$participante->nick} (ID: {$participante->id}) {$motivo}. No se procesan partidas.";
    $log->info($mensaje);
    echo '<br/> ' . $mensaje;
    
    $logAnalisisService->crearLog(new LogAnalisis(
        null,
        $caso->id,
        'PARTICIPANTE_NO_ACTIVO',
        $mensaje
    ));
    
    $analizarParticipantesService->cambiarEstado($caso->id, 2);
    exit;
}

echo '<br/>✓ Participante activo - Procesando partidas...';

// Si tiene partidas para insertar, asegurar que el estado sea 1 (activo)
if ($participante->estado != 1 && count($gamesApi) > count($urlsExistentes)) {
    echo '<br/>DEBUG: Actualizando estado del participante a 1 (activo)...';
    try {
        $participantesService->actualizarEstado($participante->id, 1);
        $participante->estado = 1;
        echo '<br/>✓ Estado actualizado a 1 (activo)';
    } catch (Exception $e) {
        echo '<br/>ERROR actualizando estado: ' . $e->getMessage();
    }
}

// MENSAJE FINAL ACTUALIZADO
$analizarParticipantesService->cambiarEstado($caso->id, 2);
$totalPartidasFinal = $numResultados + $nuevas;
$mensaje = "Finalizado análisis para participante_id={$participante->id}. Partidas nuevas insertadas: $nuevas. Total: $totalPartidasFinal/" . LIMITE_MAXIMO_PARTIDAS . ".";

if ($totalPartidasFinal >= LIMITE_MAXIMO_PARTIDAS) {
    $mensaje .= " MARATÓN COMPLETADO. Estado: 0 (finalizado), Terminado: 1. Email enviado.";
} elseif ($participante->estado == 0) {
    $mensaje .= " Participante ya finalizado (estado 0).";
} elseif ($participante->estado == 5) {
    $mensaje .= " Participante deshabilitado (estado 5).";
} elseif (($participante->terminado ?? 0) == 1) {
    $mensaje .= " Maratón ya completado (terminado 1).";
}

$log->info($mensaje);
echo '<br/> ' . $mensaje;
$logAnalisisService->crearLog(new LogAnalisis(
    null,
    $caso->id,
    'FIN',
    $mensaje
));

// ESTADÍSTICAS FINALES
echo '<br/><br/>📊 RESUMEN DE PROCESAMIENTO:';
echo '<br/>  - Partidas en API: ' . count($gamesApi);
echo '<br/>  - Partidas existentes al inicio: ' . $numResultados;
echo '<br/>  - Partidas nuevas insertadas: ' . $nuevas;
echo '<br/>  - Total partidas final: ' . $totalPartidasFinal;
echo '<br/>  - Límite máximo: ' . LIMITE_MAXIMO_PARTIDAS;
echo '<br/>  - Estado final del participante: ' . $participante->estado;
echo '<br/>  - Terminado: ' . ($participante->terminado ?? 0);
echo '<br/>  - Fecha finalizado: ' . ($participante->fecha_finalizado ?? 'NULL');
echo '<br/>  - Créditos totales: ' . ($participante->creditos_totales ?? 0);

if ($totalPartidasFinal >= LIMITE_MAXIMO_PARTIDAS) {
    echo '<br/>  - ✅ MARATÓN COMPLETADO';
    echo '<br/>  - ✅ Estado cambiado a 0 (finalizado)';
    echo '<br/>  - ✅ Terminado cambiado a 1 (completado)';
    echo '<br/>  - ✅ Fecha finalizado registrada';
    echo '<br/>  - ✅ Email de finalización enviado';
    
    if ($participante->estudio_id !== null) {
        echo '<br/>  - ✅ Crédito asignado: 1 (universitario)';
    } else {
        echo '<br/>  - ℹ️ Sin créditos (federado)';
    }
}
