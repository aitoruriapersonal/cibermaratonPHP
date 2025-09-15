<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\servicio\SeccionesService.php

require_once __DIR__ . '/../dao/SeccionesDAO.php';

class SeccionesService
{
    private SeccionesDAO $dao;

    public function __construct(PDO $pdo)
    {
        $this->dao = new SeccionesDAO($pdo);
    }

    public function getSecciones(): array
    {
        return $this->dao->getSecciones();
    }

    public function getSeccionById(int $id): ?SeccionBase
    {
        return $this->dao->getSeccionById($id);
    }
    
    public function getSeccionesByBasesId(int $id): ?array
    {
        return $this->dao->getSeccionesByBasesId($id);
    }

    public function crearSeccion(SeccionBase $seccion): int
    {
        return $this->dao->create($seccion);
    }
    public function actualizarSeccion(SeccionBase $seccion): bool
    {
        return $this->dao->update($seccion);
    }

    public function eliminarSeccion(int $id): bool
    {
        return $this->dao->delete($id);
    }
}