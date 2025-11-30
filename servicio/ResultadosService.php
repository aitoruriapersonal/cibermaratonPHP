<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\servicio\ResultadosService.php

require_once __DIR__ . '/../dao/ResultadosDAO.php';

class ResultadosService
{
    private ResultadoDAO $dao;

    public function __construct(PDO $pdo)
    {
        $this->dao = new ResultadoDAO($pdo);
    }

    public function crearResultado(Resultado $r): int
    {
        return $this->dao->create($r);
    }

    public function obtenerResultadoPorId(int $id): ?Resultado
    {
        return $this->dao->getById($id);
    }

    public function obtenerResultadosPorParticipante(int $participanteId): array
    {
        return $this->dao->getByParticipanteId($participanteId);
    }

    public function obtenerTodos(): array
    {
        return $this->dao->getAll();
    }

    public function actualizarResultado(Resultado $r): bool
    {
        return $this->dao->update($r);
    }

    public function eliminarResultado(int $id): bool
    {
        return $this->dao->delete($id);
    }

    public function getResultadosPorParticipante(int $participanteId): array
    {
        return $this->dao->getResultadosPorParticipante($participanteId);
    }

    public function getResultadosByParticipanteNickName(string $nickName): array
    {
        return $this->dao->getResultadosPorParticipanteNickName($nickName);
    }

    public function obtenerUltimoNumeroPartida(int $participanteId): int
    {
        return $this->dao->obtenerUltimoNumeroPartida($participanteId);
    }
    
    /**
     * DEPRECATED: Usar obtenerUltimoNumeroPartida() en su lugar
     */
    public function obtenerSiguienteNumeroPartida(int $participanteId): int
    {
        return $this->obtenerUltimoNumeroPartida($participanteId) + 1;
    }

}