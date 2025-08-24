<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\public\api.php

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

// Configuración de cabeceras para CORS y JSON
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Manejo de preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}
// Conexión PDO (ajusta los datos a tu entorno)
$pdo = new PDO('mysql:host=localhost;dbname=cibermaraton', 'root', '');

// Ruteo simple
$uri = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'));
$method = $_SERVER['REQUEST_METHOD'];
// Ejemplo de rutas: /api/campeonatos, /api/participantes, /api/bots, etc.
if (isset($uri[3]) && $uri[2] === 'api') {
    $respuesta = [];
    switch ($uri[3]) {
        case 'universidades':
            $service = new UniversidadesService($pdo);
            if ($method === 'GET') {
                echo json_encode($service->getUniversidades());
            }
            break;
        case 'universidadesById':
            $service = new UniversidadesService($pdo);
            if ($method === 'GET') {
                echo json_encode($service->getUniversidadById((int)($uri[4] ?? 0)));
            }
            break;
        case 'campusById':
            $service = new CampusService($pdo);
            if ($method === 'GET') {
                echo json_encode($service->getCampusById((int)($uri[4] ?? 0)));
            }
            break;
        case 'campusByUniversidad':
            $service = new CampusService($pdo);
            if ($method === 'GET') {
                echo json_encode($service->getCampusByUniversidad((int)($uri[4] ?? 0)));
            }
            break;
        case 'tipoEstudiosByUniversidad':
            $service = new TipoEstudiosService($pdo);
            if ($method === 'GET') {
                echo json_encode($service->getTipoEstudioByUniversidad((int)($uri[4] ?? 0)));
            }
            break;
        case 'centrosByCampus':
            $service = new CentrosService($pdo);
            if ($method === 'GET') {
                echo json_encode($service->getCentrosByCampus((int)($uri[4] ?? 0)));
            }
            break;
        case 'estudiosByCentro':
            $service = new EstudiosService($pdo);
            if ($method === 'GET') {
                echo json_encode($service->getEstudiosByCentro((int)($uri[4] ?? 0)));
            }
            break;
        case 'estudiosByTipoEstudios':
            $service = new EstudiosService($pdo);
            if ($method === 'GET') {
                echo json_encode($service->getEstudiosByTipoEstudios((int)($uri[4] ?? 0)));
            }
            break;
        case 'estudiosByCentroAndTipoEstudios':
            $service = new EstudiosService($pdo);
            if ($method === 'GET') {
                echo json_encode($service->getEstudiosByCentroAndTipoEstudios((int)($uri[4] ?? 0), (int)($uri[6] ?? 0)));
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
                echo json_encode($service->getCampeonatoById((int)($uri[4] ?? 0)));
            }
            break;
        case 'campeonatoActivoByTipo':
            $service = new CampeonatosService($pdo);
            if ($method === 'GET') {
                echo json_encode($service->getCampeonatoActivoByTipo(($uri[4] ?? '')));
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
                echo json_encode($service->getClubsByProvincia(($uri[4] ?? '')));
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