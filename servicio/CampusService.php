<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\servicio\CampeonatoParticipanteService.php

require_once __DIR__ . '/../dao/CampusDAO.php';

class CampusService
{
    private CampusDAO $dao;

    public function __construct(PDO $pdo)
    {
        $this->dao = new CampusDAO($pdo);
    }

    // All Campus
    public function getCampus(): array
    {
        return $this->dao->getCampus();
    }

    // Campus by id
    public function getCampusById(int $id): ?Campus
    {
        return $this->dao->getCampusById($id);
    }

    // Campus by universidad
    public function getCampusByUniversidad(int $universidadId): array
    {
        return $this->dao->getCampusByUniversidad($universidadId);
    }
    public function crearCampus(Campus $campus): int
    {
        return $this->dao->create($campus);
    }
    public function actualizarCampus(Campus $campus): bool
    {
        return $this->dao->update($campus);
    }

    public function eliminarCampus(int $id): bool
    {
        return $this->dao->delete($id);
    }
}