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

    // All Estudios
    public function getEstudios(): array
    {
        return $this->dao->getEstudios();
    }

    // Estudios by centro and tipo estudios
    public function getEstudiosByCentroAndTipoEstudios(int $centroId, int $tipoEstudioId): array
    {
        return $this->dao->getEstudiosByCentroAndTipoEstudios($centroId, $tipoEstudioId);
    }

    // Estudios by centro
    public function getEstudiosByCentro(int $centroId): array
    {
        return $this->dao->getEstudiosByCentro($centroId);
    }

    // Estudios by tipo estudios
    public function getEstudiosByTipoEstudios(int $tipoEstudioId): array
    {
        return $this->dao->getEstudiosByTipoEstudios($tipoEstudioId);
    }
}