<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\servicio\TiposEstudiosService.php

require_once __DIR__ . '/../dao/TipoEstudiosDAO.php';

class TipoEstudiosService
{
    private TipoEstudiosDAO $dao;

    public function __construct(PDO $pdo)
    {
        $this->dao = new TipoEstudiosDAO($pdo);
    }

    public function getTipoEstudios(): array
    {
        return $this->dao->getTipoEstudios();
    }

    public function getTipoEstudioById(int $id): ?TipoEstudio
    {
        return $this->dao->getTipoEstudioById($id);
    }
    
    public function getTipoEstudioByUniversidad(int $id): ?array
    {
        return $this->dao->getTipoEstudioByUniversidad($id);
    }

    public function crearTipoEstudio(TipoEstudio $tipo): int
    {
        return $this->dao->create($tipo);
    }

    public function actualizarTipoEstudio(TipoEstudio $tipo): bool
    {
        return $this->dao->update($tipo);
    }

    public function eliminarTipoEstudio(int $id): bool
    {
        return $this->dao->delete($id);
    }

}