<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\servicio\CampeonatoParticipanteService.php

require_once __DIR__ . '/../dao/ParticipanteDAO.php';

class ParticipanteService
{
    private ParticipanteDAO $dao;

    public function __construct(PDO $pdo)
    {
        $this->dao = new ParticipanteDAO($pdo);
    }

    public function getParticipantesById(int $id): array
    {
        return $this->dao->getParticipantesById($id);
    }

    public function getParticipantesByChesscomPlayerId(int $chesscomPlayerId): array
    {
        return $this->dao->getParticipantesByChesscomPlayerId($chesscomPlayerId);
    }
    // Participantes
    public function getParticipantesByCampeonato(int $campeonatoId): array
    {
        return $this->dao->getParticipantesByCampeonato($campeonatoId);
    }

    public function getParticipantesUniversitariosByCampeonato(int $campeonatoId): array
    {
        return $this->dao->getParticipantesUniversitariosByCampeonato($campeonatoId);
    }
    
    public function getParticipantesByCampeonatoAndUniversitarios(int $campeonatoId, int $universidadId): array
    {
        return $this->dao->getParticipantesByCampeonatoAndUniversitarios($campeonatoId, $universidadId);
    }

    public function getParticipantesFederadosByCampeonato(int $campeonatoId): array
    {
        return $this->dao->getParticipantesFederadosByCampeonato($campeonatoId);
    }

    public function addParticipanteToCampeonato(array $data): bool
    {
        return $this->dao->addParticipanteToCampeonato($data);
    }
}