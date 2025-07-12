<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\public\api.php

require_once __DIR__ . '/../servicio/CampeonatoParticipanteService.php';
require_once __DIR__ . '/../servicio/LanzamientosBatchService.php';

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
$pdo = new PDO('mysql:host=localhost;dbname=tu_bd;charset=utf8', 'usuario', 'password');

// Ruteo simple
$uri = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'));
$method = $_SERVER['REQUEST_METHOD'];

// Ejemplo de rutas: /api/campeonatos, /api/participantes, /api/bots, etc.
if (isset($uri[1]) && $uri[0] === 'api') {
    switch ($uri[1]) {
        case 'campeonatos':
            $service = new CampeonatoParticipanteService($pdo);
            if ($method === 'GET') {
                echo json_encode($service->getCampeonatos());
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
            break;
        default:
            http_response_code(404);
            echo json_encode(['error' => 'Recurso no encontrado']);
    }
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Ruta no válida']);
}