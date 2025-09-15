<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\servicio\CampeonatoParticipanteService.php

require_once __DIR__ . '/../dao/CampeonatosDAO.php';

class CampeonatosService
{
    private CampeonatosDAO $dao;

    public function __construct(PDO $pdo)
    {
        $this->dao = new CampeonatosDAO($pdo);
    }

    // Campeonatos
    public function getCampeonatos(): array
    {
        return $this->dao->getCampeonatos();
    }

    public function getCampeonatoById(int $id): ?Campeonato
    {
        return $this->dao->getCampeonatoById($id);
    }

    // Participantes
    public function getParticipantesByCampeonato(int $campeonatoId): ?Campeonato
    {
        return $this->dao->getCampeonatoById($campeonatoId);
    }
    public function getCampeonatoActivoByTipo($tipo): array
    {
        return $this->dao->getCampeonatoActivoByTipo($tipo);
    }

    public function crearCampeonato(Campeonato $campeonato): int
    {
        return $this->dao->create($campeonato);
    }
    public function actualizarCampeonato(Campeonato $campeonato): bool
    {
        return $this->dao->update($campeonato);
    }

    public function eliminarCampeonato(int $id): bool
    {
        return $this->dao->delete($id);
    }
}