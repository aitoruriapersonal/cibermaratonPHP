<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\servicio\CampeonatoParticipanteService.php

require_once __DIR__ . '/../dao/CampeonatoParticipanteDAO.php';

class CampeonatoParticipanteService
{
    private CampeonatoParticipanteDAO $dao;

    public function __construct(PDO $pdo)
    {
        $this->dao = new CampeonatoParticipanteDAO($pdo);
    }

    // Campeonatos
    public function getCampeonatos(): array
    {
        return $this->dao->getCampeonatos();
    }

    public function getCampeonatoById(int $id): ?array
    {
        return $this->dao->getCampeonatoById($id);
    }

    // Participantes
    public function getParticipantesByCampeonato(int $campeonatoId): array
    {
        return $this->dao->getParticipantesByCampeonato($campeonatoId);
    }

    public function addParticipanteToCampeonato(array $data): bool
    {
        return $this->dao->addParticipanteToCampeonato($data);
    }
}