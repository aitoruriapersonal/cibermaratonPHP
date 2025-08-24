<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\servicio\IncidenciaParticipanteService.php

require_once __DIR__ . '/../dao/IncidenciaParticipanteDAO.php';

class IncidenciaParticipanteService
{
    private IncidenciaParticipanteDAO $dao;

    public function __construct(PDO $pdo)
    {
        $this->dao = new IncidenciaParticipanteDAO($pdo);
    }

    public function crearIncidencia(IncidenciaParticipante $incidencia): int
    {
        return $this->dao->create($incidencia);
    }

    public function obtenerIncidenciaPorId(int $id): ?IncidenciaParticipante
    {
        return $this->dao->getById($id);
    }

    public function obtenerIncidenciasPorParticipante(int $participanteId): array
    {
        return $this->dao->getByParticipanteId($participanteId);
    }

    public function obtenerTodas(): array
    {
        return $this->dao->getAll();
    }

    public function actualizarIncidencia(IncidenciaParticipante $incidencia): bool
    {
        return $this->dao->update($incidencia);
    }

    public function eliminarIncidencia(int $id): bool
    {
        return $this->dao->delete($id);
    }
}