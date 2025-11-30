<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\public\api.php

//use LDAP\Result;

/*ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);*/

require_once __DIR__ . '/servicio/ArticulosService.php';

require_once __DIR__ . '/servicio/BasesService.php';
require_once __DIR__ . '/servicio/CampeonatosService.php';
require_once __DIR__ . '/servicio/CampusService.php';
require_once __DIR__ . '/servicio/CentrosService.php';
require_once __DIR__ . '/servicio/ClubsService.php';
require_once __DIR__ . '/servicio/EstudiosService.php';
require_once __DIR__ . '/servicio/LanzamientosBatchService.php';
require_once __DIR__ . '/servicio/ParticipantesService.php';
require_once __DIR__ . '/servicio/ProvinciasService.php';
require_once __DIR__ . '/servicio/RegistroService.php';
require_once __DIR__ . '/servicio/SeccionesService.php';
require_once __DIR__ . '/servicio/TipoEstudiosService.php';
require_once __DIR__ . '/servicio/UniversidadesService.php';
require_once __DIR__ . '/servicio/ValoresGenericosService.php';
require_once __DIR__ . '/servicio/ResultadosService.php';
// Configuración de cabeceras para CORS y JSON
/*header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");*/


$esLocal = false;
$elementosUrlBase = 0;
$elementoNivel1 = 1; // Ajusta según tu estructura de URL en producción
$elementoNivel2 = 2; // Ajusta según tu estructura de URL en producción
$elementoNivel3 = 3; // Ajusta según tu estructura de URL en producción
$elementoNivel4 = 4; // Ajusta según tu estructura de URL en producción
$elementoNivel5 = 5; // Ajusta según tu estructura de URL en producción
$elementoNivel6 = 6; // Ajusta según tu estructura de URL en producción

if ($esLocal) {
    $elementosUrlBase = 2; // Ajusta según tu estructura de URL local
}

$elementoNivel1 = $elementosUrlBase + $elementoNivel1; // Ajusta según tu estructura de URL local
$elementoNivel2 = $elementosUrlBase + $elementoNivel2; // Ajusta según tu estructura de URL local
$elementoNivel3 = $elementosUrlBase + $elementoNivel3; // Ajusta según tu estructura de URL local
$elementoNivel4 = $elementosUrlBase + $elementoNivel4; // Ajusta según tu estructura de URL local
$elementoNivel5 = $elementosUrlBase + $elementoNivel5; // Ajusta según tu estructura de URL local
$elementoNivel6 = $elementosUrlBase + $elementoNivel6; // Ajusta según tu estructura de URL local


// Manejo de preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}
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

// Ruteo simple
$uri = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'));
$method = $_SERVER['REQUEST_METHOD'];

//var_dump($uri);
//var_dump($elementosUrlBase);

// Ejemplo de rutas: /api/campeonatos, /api/participantes, /api/bots, etc.
if (isset($uri[$elementoNivel3]) && $uri[$elementoNivel2] === 'api') {
    $respuesta = [];
    switch ($uri[$elementoNivel3]) {
        case 'universidades':
            $service = new UniversidadesService($pdo);
            if ($method === 'GET') {
                echo json_encode($service->getUniversidades());
            }
            break;
        case 'universidadesById':
            $service = new UniversidadesService($pdo);
            if ($method === 'GET') {
                echo json_encode($service->getUniversidadById((int)($uri[$elementoNivel4] ?? 0)));
            }
            break;
        case 'campusById':
            $service = new CampusService($pdo);
            if ($method === 'GET') {
                echo json_encode($service->getCampusById((int)($uri[$elementoNivel4] ?? 0)));
            }
            break;
        case 'campusByUniversidad':
            $service = new CampusService($pdo);
            if ($method === 'GET') {
                echo json_encode($service->getCampusByUniversidad((int)($uri[$elementoNivel4] ?? 0)));
            }
            break;
        case 'tipoEstudiosByUniversidad':
            $service = new TipoEstudiosService($pdo);
            if ($method === 'GET') {
                echo json_encode($service->getTipoEstudioByUniversidad((int)($uri[$elementoNivel4] ?? 0)));
            }
            break;
        case 'centrosByCampus':
            $service = new CentrosService($pdo);
            if ($method === 'GET') {
                echo json_encode($service->getCentrosByCampus((int)($uri[$elementoNivel4] ?? 0)));
            }
            break;
        case 'estudiosByCentro':
            $service = new EstudiosService($pdo);
            if ($method === 'GET') {
                echo json_encode($service->getEstudiosByCentro((int)($uri[$elementoNivel4] ?? 0)));
            }
            break;
        case 'estudiosByTipoEstudios':
            $service = new EstudiosService($pdo);
            if ($method === 'GET') {
                echo json_encode($service->getEstudiosByTipoEstudios((int)($uri[$elementoNivel4] ?? 0)));
            }
            break;
        case 'estudiosByCentroAndTipoEstudios':
            $service = new EstudiosService($pdo);
            if ($method === 'GET') {
                echo json_encode($service->getEstudiosByCentroAndTipoEstudios((int)($uri[$elementoNivel4] ?? 0), (int)($uri[$elementoNivel6] ?? 0)));
            }
            break;
        case 'campeonatos':
            $service = new CampeonatosService($pdo);
            if ($method === 'GET') {
                echo json_encode($service->getCampeonatos());
            }
            break;
        case 'campeonatosById':
            $service = new CampeonatosService($pdo);
            if ($method === 'GET') {
                echo json_encode($service->getCampeonatoById((int)($uri[$elementoNivel4] ?? 0)));
            }
            break;
        case 'campeonatoActivoByTipo':
            $service = new CampeonatosService($pdo);
            if ($method === 'GET') {
                echo json_encode($service->getCampeonatoActivoByTipo(($uri[$elementoNivel4] ?? '')));
            }
            break;
        case 'provincias':
            $service = new ProvinciasService($pdo);
            if ($method === 'GET') {
                echo json_encode($service->getProvincias());
            }
            break;
        case 'clubsByProvincia':
            $service = new ClubsService($pdo);
            if ($method === 'GET') {
                echo json_encode($service->getClubsByProvincia(($uri[$elementoNivel4] ?? '')));
            }
            break;
        case 'inscripcion':
            $service = new RegistroService($pdo);
            if ($method === 'POST') {
                echo json_encode($service->registrarParticipante(json_decode(file_get_contents('php://input'), true)));
            }
            break;
        case 'clasificacion':
            $service = new ParticipantesService($pdo);
            if ($method === 'POST') {
                if($uri[4] === 'general'){
                    $respuesta['general'] = $service->getParticipantesByCampeonato((int)json_decode(file_get_contents('php://input'), true));
                    $respuesta['universitarios']=null;
                    $respuesta['federados'] = null;
                    $respuesta['resultado'] = 'OK';
                }elseif($uri[4] === 'universitarios'){
                    $respuesta['general'] = null;
                    $respuesta['universitarios'] = $service->getParticipantesUniversitariosByCampeonato((int)json_decode(file_get_contents('php://input'), true));
                    $respuesta['federados'] = null;
                    $respuesta['resultado'] = 'OK';
                }elseif($uri[4] === 'federados'){
                    $respuesta['general'] = null;
                    $respuesta['universitarios']=null;
                    $respuesta['federados'] = $service->getParticipantesFederadosByCampeonato((int)json_decode(file_get_contents('php://input'), true));
                    $respuesta['resultado'] = 'OK';
                }else{
                    /*$respuesta['general'] = null;
                    $respuesta['universitarios']=null;
                    $respuesta['federados'] = null;
                    $respuesta['resultado'] = 'KO';*/
                    throw new InvalidArgumentException('KO. Petición no válida.');
                }
                echo json_encode($respuesta);
            }
            break;
        case 'basesCompletasByCampeonato':
            $service = new BasesService($pdo);
            if ($method === 'GET') {
                echo json_encode($service->getBasesCompletasByCampeonatoId((int)($uri[$elementoNivel4] ?? 0)));
            }
            break;
        case 'generos':
            $service = new ValoresGenericosService($pdo);
            if ($method === 'GET') {
                echo json_encode($service->getByTipo('genero'));
            }
            break;
        case 'resultadosByParticipanteNickName':
            $service = new ResultadosService($pdo);
            if ($method === 'GET') {
                echo json_encode($service->getResultadosByParticipanteNickName(($uri[$elementoNivel4] ?? '')));
            }
            break;
        /*case 'basesByCampeonatoId':
            $service = new CampeonatoParticipanteService($pdo);
            if ($method === 'GET') {
                echo json_encode($service->getCampeonatosById((int)($uri[4] ?? 0)));
            }
            break;
        case 'seccionesByBasesId':
            $service = new CampeonatoParticipanteService($pdo);
            if ($method === 'GET') {
                echo json_encode($service->getSeccionesByBasesId((int)($uri[4] ?? 0)));
            }
            break;
        case 'articulosBySeccionesId':
            $service = new CampeonatoParticipanteService($pdo);
            if ($method === 'GET') {
                echo json_encode($service->getArticulosBySeccionesId((int)($uri[4] ?? 0)));
            }
            break;
        case 'participantes':
            $service = new CampeonatoParticipanteService($pdo);
            if ($method === 'GET' && isset($uri[2])) {
                echo json_encode($service->getParticipantesByCampeonato((int)$uri[2]));
            }
            break;
        case 'bots':
            $service = new LanzamientosBatchService($pdo);
            if ($method === 'GET') {
                echo json_encode($service->getBots());
            }
            break;
        case 'lanzar-bot':
            $service = new LanzamientosBatchService($pdo);
            if ($method === 'POST') {
                $data = json_decode(file_get_contents('php://input'), true);
                $botId = $data['bot_id'] ?? null;
                $parametros = $data['parametros'] ?? null;
                if ($botId) {
                    $ejecucionId = $service->lanzarBot($botId, $parametros);
                    echo json_encode(['ejecucion_id' => $ejecucionId]);
                } else {
                    http_response_code(400);
                    echo json_encode(['error' => 'bot_id requerido']);
                }
            }
            break;*/
        default:
            http_response_code(404);
            echo json_encode(['error' => 'Recurso no encontrado']);
    }
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Ruta no válida']);
}

