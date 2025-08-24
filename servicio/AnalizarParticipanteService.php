<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\servicio\AnalizarParticipanteService.php

require_once __DIR__ . '/../dao/AnalizarParticipanteDAO.php';

class AnalizarParticipanteService
{
    private AnalizarParticipanteDAO $dao;

    public function __construct(PDO $pdo)
    {
        $this->dao = new AnalizarParticipanteDAO($pdo);
    }

    public function crearAnalisis(AnalizarParticipante $analisis): int
    {
        return $this->dao->create($analisis);
    }

    public function obtenerAnalisisPorId(int $id): ?AnalizarParticipante
    {
        return $this->dao->getById($id);
    }

    public function obtenerAnalisisPorParticipante(int $participanteId): array
    {
        return $this->dao->getByParticipanteId($participanteId);
    }

    public function actualizarAnalisis(AnalizarParticipante $analisis): bool
    {
        return $this->dao->update($analisis);
    }

    public function eliminarAnalisis(int $id): bool
    {
            return $this->dao->delete($id);
    }
}