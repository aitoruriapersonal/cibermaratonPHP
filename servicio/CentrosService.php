<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\servicio\CentrosService.php

require_once __DIR__ . '/../dao/CentrosDAO.php';

class CentrosService
{
    private CentrosDAO $dao;

    public function __construct(PDO $pdo)
    {
        $this->dao = new CentrosDAO($pdo);
    }

    // All Centros
    public function getCentros(): array
    {
        return $this->dao->getCentros();
    }

    // Centros by id
    public function getCentrosById(int $id): ?Centro
    {
        return $this->dao->getCentrosById($id);
    }

    // Centros by campus
    public function getCentrosByCampus(int $campusId): array
    {
        return $this->dao->getCentrosByCampus($campusId);
    }

    public function crearCentro(Centro $centro): int
    {
        return $this->dao->create($centro);
    }

        public function actualizarCentro(Centro $centro): bool
    {
        return $this->dao->update($centro);
    }

    public function eliminarCentro(int $id): bool
    {
        return $this->dao->delete($id);
    }
}