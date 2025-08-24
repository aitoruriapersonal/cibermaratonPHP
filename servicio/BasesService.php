<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\servicio\CampeonatoParticipanteService.php

require_once __DIR__ . '/../dao/BasesDAO.php';

class BasesService
{
    private BasesDAO $dao;

    public function __construct(PDO $pdo)
    {
        $this->dao = new BasesDAO($pdo);
    }

    // Bases
    public function getBases(): array
    {
        return $this->dao->getBases();
    }

    public function getBaseById(int $id): ?BaseCampeonato
    {
        return $this->dao->getBasesById($id);
    }

    // Participantes
    public function getBasesByCampeonatoId(int $campeonatoId): array
    {
        return $this->dao->getBasesByCampeonatoId($campeonatoId);
    }

    public function crearBase(BaseCampeonato $base): int
    {
        return $this->dao->create($base);
    }
    public function actualizarBase(BaseCampeonato $base): bool
    {
        return $this->dao->update($base);
    }

    public function eliminarBase(int $id): bool
    {
        return $this->dao->delete($id);
    }
}