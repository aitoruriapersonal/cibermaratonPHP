<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\servicio\UniversidadesService.php

require_once __DIR__ . '/../dao/UniversidadesDAO.php';

class UniversidadesService
{
    private UniversidadesDAO $dao;

    public function __construct(PDO $pdo)
    {
        $this->dao = new UniversidadesDAO($pdo);
    }

    public function getUniversidades(): array
    {
        return $this->dao->getUniversidades();
    }

    public function getUniversidadById(int $id): ?Universidad
    {
        return $this->dao->getUniversidadById($id);
    }

    public function crearUniversidad(Universidad $uni): int
    {
        return $this->dao->create($uni);
    }

    public function actualizarUniversidad(Universidad $uni): bool
    {
        return $this->dao->update($uni);
    }

    public function eliminarUniversidad(int $id): bool
    {
        return $this->dao->delete($id);
    }
}