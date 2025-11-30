<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\operativa\cron_insertar_analizar_participantes.php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/../servicio/ParticipantesService.php';
require_once __DIR__ . '/../servicio/AnalizarParticipanteService.php';
require_once __DIR__ . '/../utilidades/LogGestor.php';

$esLocal = false;

// Configuración de conexión PDO (ajusta los datos a tu entorno)
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
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Instanciar servicios
$participantesService = new ParticipantesService($pdo);
$analizarParticipantesService = new AnalizarParticipanteService($pdo);
$log = new LogGestor();

$log->info("Inicio de búsqueda de casos a analizar.");

// 1. Buscar participantes que cumplen las condiciones adicionales:
// - terminado = 0
// - fecha_finalizado IS NULL
// - estado IN (0,1,2)
// - campeonato.tipo_campeonato = 'cibermaraton'
// - campeonato.fecha_fin > NOW()
$participantes = $participantesService->getParticipantesActivosCibermaratonActivo();

$log->info("Participantes encontrados para analizar: " . count($participantes));

// Este método debe devolver un array de objetos Participante con terminado=0 y fecha_finalizado IS NULL

$nuevos = 0;
foreach ($participantes as $participante) {
    // 2. Comprobar si ya existe en analizar_participantes con estado 0 o 1
    if (!$analizarParticipantesService->existeAnalisisPendiente($participante->id)) {
        // 3. Insertar en analizar_participantes
        $analizarParticipantesService->insertarPendiente($participante->id);
        $log->info("Insertado participante_id={$participante->id} en analizar_participantes.");
        $nuevos++;
    }else{
        $log->warn("Participante_id={$participante->id} ya tiene un análisis pendiente o en curso.");
    }
}

if ($nuevos > 0) {
    $log->info("Insertados $nuevos registros nuevos en analizar_participantes.");
} else {
    $log->info("No se insertaron nuevos registros en analizar_participantes.");
}

echo "Insertados $nuevos registros nuevos en analizar_participantes.\n";

/*
--- Métodos sugeridos a añadir en los servicios ---

// En ParticipantesService.php
public function getParticipantesNoTerminadosSinFinalizar(): array
{
    $stmt = $this->pdo->query("SELECT * FROM participantes WHERE terminado = 0 AND fecha_finalizado IS NULL");
    $result = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $result[] = new Participante(
            $row['id'],
            // ...otros campos según tu modelo...
        );
    }
    return $result;
}

// En AnalizarParticipantesService.php
public function existeAnalisisPendiente(int $participante_id): bool
{
    $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM analizar_participantes WHERE participante_id = ? AND estado IN (0,1)");
    $stmt->execute([$participante_id]);
    return $stmt->fetchColumn() > 0;
}

public function insertarPendiente(int $participante_id): void
{
    $stmt = $this->pdo->prepare("INSERT INTO analizar_participantes (participante_id, estado, fecha_alta) VALUES (?, 0, NOW())");
    $stmt->execute([$participante_id]);
}
*/