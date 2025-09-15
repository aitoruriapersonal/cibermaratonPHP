<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\servicio\CampeonatoParticipanteService.php

require_once __DIR__ . '/../dao/BasesDAO.php';
require_once __DIR__ . '/../dao/SeccionesDAO.php';
require_once __DIR__ . '/../dao/ArticulosDAO.php';

class BasesService
{
    private BasesDAO $dao;
    private SeccionesDAO $seccionesDAO;
    private ArticulosDAO $articulosDAO;

    public function __construct(PDO $pdo)
    {
        $this->dao = new BasesDAO($pdo);
        $this->seccionesDAO = new SeccionesDAO($pdo);
        $this->articulosDAO = new ArticulosDAO($pdo);
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

    public function getBasesCompletasByCampeonatoId(int $campeonatoId): array
{
    $bases = $this->dao->getBasesByCampeonatoId($campeonatoId);
    // Para cada base, obtener sus secciones y para cada sección sus artículos
    $basesArray = [];
    foreach ($bases as $base) {
        $secciones = $this->seccionesDAO->getSeccionesByBasesId($base->id);
        $seccionesArray = [];
        foreach ($secciones as $seccion) {
            $articulos = $this->articulosDAO->getArticulosBySeccionId($seccion->id);
            $seccionObj = (array)$seccion;
            $seccionObj['articulos'] = $articulos;
            $seccionesArray[] = $seccionObj;
        }
        $baseObj = (array)$base;
        $baseObj['secciones'] = $seccionesArray;
        $basesArray[] = $baseObj;
    }

    return [
        'bases' => $basesArray
    ];
}
}