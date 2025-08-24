<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\servicio\LogAnalisisService.php

require_once __DIR__ . '/../dao/LogAnalisisDAO.php';

class LogAnalisisService
{
    private LogAnalisisDAO $dao;

    public function __construct(PDO $pdo)
    {
        $this->dao = new LogAnalisisDAO($pdo);
    }

    public function crearLog(LogAnalisis $log): int
    {
        return $this->dao->create($log);
    }

    public function obtenerLogPorId(int $id): ?LogAnalisis
    {
        return $this->dao->getById($id);
    }

    public function obtenerLogsPorAnalisis(int $analisisId): array
    {
        return $this->dao->getByAnalisisId($analisisId);
    }

    public function obtenerTodos(): array
    {
        return $this->dao->getAll();
    }

    public function actualizarLog(LogAnalisis $log): bool
    {
        return $this->dao->update($log);
    }

    public function eliminarLog(int $id): bool
    {
        return $this->dao->delete($id);
    }
}