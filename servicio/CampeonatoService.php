<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\servicio\CampeonatoParticipanteService.php

require_once __DIR__ . '/../dao/CampeonatoDAO.php';

class CampeonatoService
{
    private CampeonatoDAO $dao;

    public function __construct(PDO $pdo)
    {
        $this->dao = new CampeonatoDAO($pdo);
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
        return $this->dao->getCampeonatoById($campeonatoId);
    }
    public function getCampeonatoActivoByTipo(int $tipoId): ?array
    {
        return $this->dao->getCampeonatoActivoByTipo($tipoId);
    }
}