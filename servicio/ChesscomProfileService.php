<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\servicio\ChesscomProfileService.php

require_once __DIR__ . '/../dao/ChesscomProfileDAO.php';

class ChesscomProfileService
{
    private ChesscomProfileDAO $dao;

    public function __construct(PDO $pdo)
    {
        $this->dao = new ChesscomProfileDAO($pdo);
    }

    public function crearProfile(ChesscomProfile $profile): int
    {
        return $this->dao->create($profile);
    }

    public function obtenerProfilePorId(int $player_id): ?ChesscomProfile
    {
        return $this->dao->getById($player_id);
    }

    public function obtenerProfilePorParticipante(int $participante_id): ?ChesscomProfile
    {
        return $this->dao->getByParticipanteId($participante_id);
    }

    public function actualizarProfile(ChesscomProfile $profile): bool
    {
        return $this->dao->update($profile);
    }

    public function eliminarProfile(int $player_id): bool
    {
        return $this->dao->delete($player_id);
    }

    public function existeProfile(int $participante_id, string $username): bool
    {
        return $this->dao->existeProfile($participante_id, $username);
    }

}