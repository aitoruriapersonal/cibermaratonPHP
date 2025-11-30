<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\servicio\EstudiosService.php

require_once __DIR__ . '/../dao/EstudiosDAO.php';

class EstudiosService
{
    private EstudiosDAO $dao;

    public function __construct(PDO $pdo)
    {
        $this->dao = new EstudiosDAO($pdo);
    }

    // Universidad
    public function getUniversidades(): array
    {
        return $this->dao->getUniversidades();
    }

    public function getUniversidadById(int $id): ?array
    {
        return $this->dao->getUniversidadById($id);
    }

    // Tipos de estudio
    public function getTiposEstudio(): array
    {
        return $this->dao->getTiposEstudio();
    }

    // Campus
    public function getCampusByUniversidad(int $universidadId): array
    {
        return $this->dao->getCampusByUniversidad($universidadId);
    }

    // Centros
    public function getCentrosByCampus(int $campusId): array
    {
        return $this->dao->getCentrosByCampus($campusId);
    }

    // Grados
    public function getGradosByCentro(int $centroId): array
    {
        return $this->dao->getGradosByCentro($centroId);
    }

    // Postgrados
    public function getPostgradosByUniversidad(int $universidadId): array
    {
        return $this->dao->getPostgradosByUniversidad($universidadId);
    }

    // Doctorados
    public function getDoctoradosByUniversidad(int $universidadId): array
    {
        return $this->dao->getDoctoradosByUniversidad($universidadId);
    }
}