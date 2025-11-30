<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\servicio\LanzamientosBatchService.php

require_once __DIR__ . '/../dao/LanzamientosBatchDAO.php';

class LanzamientosBatchService
{
    private LanzamientosBatchDAO $dao;

    public function __construct(PDO $pdo)
    {
        $this->dao = new LanzamientosBatchDAO($pdo);
    }

    public function getBots(): array
    {
        return $this->dao->getBots();
    }

    public function getEjecucionesByBot($botId): array
    {
        return $this->dao->getEjecucionesByBot($botId);
    }

    public function lanzarBot($botId, $parametros = null): int
    {
        return $this->dao->lanzarBot($botId, $parametros);
    }

    public function actualizarEstadoEjecucion($ejecucionId, $estado, $mensajeError = null): bool
    {
        return $this->dao->actualizarEstadoEjecucion($ejecucionId, $estado, $mensajeError);
    }

    public function agregarLog($ejecucionId, $nivel, $mensaje): bool
    {
        return $this->dao->agregarLog($ejecucionId, $nivel, $mensaje);
    }
}