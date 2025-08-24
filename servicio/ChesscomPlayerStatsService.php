<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\servicio\ChesscomPlayerStatsService.php

require_once __DIR__ . '/../dao/ChesscomPlayerStatsDAO.php';

class ChesscomPlayerStatsService
{
    private ChesscomPlayerStatsDAO $dao;

    public function __construct(PDO $pdo)
    {
        $this->dao = new ChesscomPlayerStatsDAO($pdo);
    }

    public function crearStats(ChesscomPlayerStats $stats): int
    {
        return $this->dao->create($stats);
    }

    public function obtenerStatsPorId(int $id): ?ChesscomPlayerStats
    {
        return $this->dao->getById($id);
    }

    public function obtenerStatsPorParticipante(int $participante_id): ?ChesscomPlayerStats
    {
        return $this->dao->getByParticipanteId($participante_id);
    }

    public function obtenerTodos(): array
    {
        return $this->dao->getAll();
    }

    public function actualizarStats(ChesscomPlayerStats $stats): bool
    {
        return $this->dao->update($stats);
    }

    public function eliminarStats(int $id): bool
    {
        return $this->dao->delete($id);
    }
}