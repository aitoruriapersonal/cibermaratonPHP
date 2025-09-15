<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\operativa\cron_insertar_analizar_participantes.php

require_once __DIR__ . '/../servicio/ParticipantesService.php';
require_once __DIR__ . '/../servicio/AnalizarParticipanteService.php';
require_once __DIR__ . '/../utilidades/LogGestor.php';


// Configuración de conexión PDO (ajusta los datos a tu entorno)
$pdo = new PDO('mysql:host=localhost;dbname=cibermaraton', 'root', '');
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