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

    public function existeAnalisisPendiente(int $participante_id): bool
    {
        return $this->dao->existeAnalisisPendiente($participante_id);
    }

    public function insertarPendiente(int $participante_id): void
    {
        $this->dao->insertarPendiente($participante_id);
    }

    public function obtenerAnalisisPendientes(): array
    {
        return $this->dao->obtenerAnalisisPendientes();
    }

    public function obtenerPrimerAnalisisPendiente(): ?AnalizarParticipante
    {
        return $this->dao->obtenerPrimerAnalisisPendiente();
    }

    public function cambiarEstado(int $id, int $estado): void
    {
        $this->dao->cambiarEstado($id, $estado);
    }

}