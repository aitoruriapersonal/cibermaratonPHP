<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\servicio\ChesscomPlayerGameService.php

require_once __DIR__ . '/../dao/ChesscomPlayerGameDAO.php';

class ChesscomPlayerGameService
{
    private ChesscomPlayerGameDAO $dao;

    public function __construct(PDO $pdo)
    {
        $this->dao = new ChesscomPlayerGameDAO($pdo);
    }

    public function crearGame(ChesscomPlayerGame $game): int
    {
        return $this->dao->create($game);
    }

    public function obtenerGamePorId(int $id): ?ChesscomPlayerGame
    {
        return $this->dao->getById($id);
    }

    public function obtenerGamesPorParticipante(int $participante_id): array
    {
        return $this->dao->getByParticipanteId($participante_id);
    }

    public function obtenerTodos(): array
    {
        return $this->dao->getAll();
    }

    public function actualizarGame(ChesscomPlayerGame $game): bool
    {
        return $this->dao->update($game);
    }

    public function eliminarGame(int $id): bool
    {
        return $this->dao->delete($id);
    }
}