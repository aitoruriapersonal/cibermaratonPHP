<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\servicio\CampeonatoParticipanteService.php

require_once __DIR__ . '/../dao/ProvinciasDAO.php';

class ProvinciasService
{
    private ProvinciasDAO $dao;

    public function __construct(PDO $pdo)
    {
        $this->dao = new ProvinciasDAO($pdo);
    }

    public function getProvincias(): array
    {
        return $this->dao->getProvincias();
    }

    public function getProvinciasById(int $id): ?Provincia
    {
        return $this->dao->getProvinciasById($id);
    }

    public function crearProvincia(Provincia $provincia): int
    {
        return $this->dao->create($provincia);
    }

    public function actualizarProvincia(Provincia $provincia): bool
    {
        return $this->dao->update($provincia);
    }

    public function eliminarProvincia(int $id): bool
    {
        return $this->dao->delete($id);
    }
}