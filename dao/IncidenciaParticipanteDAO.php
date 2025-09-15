<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\dao\IncidenciaParticipanteDAO.php

require_once __DIR__ . '/../modelo/IncidenciaParticipante.php';

class IncidenciaParticipanteDAO
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create(IncidenciaParticipante $incidencia): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO incidencias_participantes (participante_id, agente, motivo, comentario, fecha_alta, fecha_modificacion)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $incidencia->participante_id,
            $incidencia->agente,
            $incidencia->motivo,
            $incidencia->comentario,
            $incidencia->fecha_alta,
            $incidencia->fecha_modificacion
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function getById(int $id): ?IncidenciaParticipante
    {
        $stmt = $this->pdo->prepare("SELECT * FROM incidencias_participantes WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new IncidenciaParticipante(
            $row['id'],
            $row['participante_id'],
            $row['agente'],
            $row['motivo'],
            $row['comentario'],
            $row['fecha_alta'],
            $row['fecha_modificacion']
        ) : null;
    }

    public function getByParticipanteId(int $participanteId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM incidencias_participantes WHERE participante_id = ? ORDER BY fecha_alta DESC");
        $stmt->execute([$participanteId]);
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new IncidenciaParticipante(
                $row['id'],
                $row['participante_id'],
                $row['agente'],
                $row['motivo'],
                $row['comentario'],
                $row['fecha_alta'],
                $row['fecha_modificacion']
            );
        }
        return $result;
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM incidencias_participantes ORDER BY fecha_alta DESC");
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new IncidenciaParticipante(
                $row['id'],
                $row['participante_id'],
                $row['agente'],
                $row['motivo'],
                $row['comentario'],
                $row['fecha_alta'],
                $row['fecha_modificacion']
            );
        }
        return $result;
    }

    public function update(IncidenciaParticipante $incidencia): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE incidencias_participantes
            SET agente = ?, motivo = ?, comentario = ?, fecha_modificacion = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $incidencia->agente,
            $incidencia->motivo,
            $incidencia->comentario,
            $incidencia->fecha_modificacion ?? date('Y-m-d H:i:s'),
            $incidencia->id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM incidencias_participantes WHERE id = ?");
        return $stmt->execute([$id]);
    }
}