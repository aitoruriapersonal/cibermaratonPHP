<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\servicio\CampeonatoParticipanteService.php

require_once __DIR__ . '/../dao/ArticulosDAO.php';

class ArticulosService
{
    private ArticulosDAO $dao;

    public function __construct(PDO $pdo)
    {
        $this->dao = new ArticulosDAO($pdo);
    }

    public function getArticulos(): array
    {
        return $this->dao->getArticulos();
    }

    public function getArticuloById(int $id): ?array
    {
        return $this->dao->getArticuloById($id);
    }

    public function getArticulosBySeccionId(int $seccionId): array
    {
        return $this->dao->getArticulosBySeccionId($seccionId);
    }

    public function createArticulo(ArticuloSeccion $articulo): int
    {
        return $this->dao->createArticulo($articulo);
    }

    public function updateArticulo(ArticuloSeccion $articulo): bool
    {
        return $this->dao->updateArticulo($articulo);
    }

    public function deleteArticulo(int $id): bool
    {
        return $this->dao->deleteArticulo($id);
    }
}