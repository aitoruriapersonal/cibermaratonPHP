<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\dao\LanzamientosBatchDAO.php

class LanzamientosBatchDAO
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getBots(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM bot_batch WHERE activo = 1");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEjecucionesByBot($botId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM bot_batch_ejecucion WHERE bot_batch_id = ? ORDER BY fecha_inicio DESC");
        $stmt->execute([$botId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function lanzarBot($botId, $parametros = null): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO bot_batch_ejecucion (bot_batch_id, fecha_inicio, estado, parametros)
            VALUES (?, NOW(), 'pendiente', ?)
        ");
        $stmt->execute([$botId, $parametros]);
        return (int)$this->pdo->lastInsertId();
    }

    public function actualizarEstadoEjecucion($ejecucionId, $estado, $mensajeError = null): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE bot_batch_ejecucion
            SET estado = ?, fecha_fin = IF(? IN ('finalizado','error'), NOW(), fecha_fin), mensaje_error = ?
            WHERE id = ?
        ");
        return $stmt->execute([$estado, $estado, $mensajeError, $ejecucionId]);
    }

    public function agregarLog($ejecucionId, $nivel, $mensaje): bool
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO bot_batch_log (ejecucion_id, fecha, nivel, mensaje)
            VALUES (?, NOW(), ?, ?)
        ");
        return $stmt->execute([$ejecucionId, $nivel, $mensaje]);
    }
}