<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\dao\AnalizarParticipanteDAO.php

require_once __DIR__ . '/../modelo/AnalizarParticipante.php';

class AnalizarParticipanteDAO
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // CREATE
    public function create(AnalizarParticipante $analisis): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO analizar_participantes (participante_id, estado, comentario, fecha_alta, fecha_modificacion)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $analisis->participante_id,
            $analisis->estado,
            $analisis->comentario,
            $analisis->fecha_alta,
            $analisis->fecha_modificacion
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    // READ by id
    public function getById(int $id): ?AnalizarParticipante
    {
        $stmt = $this->pdo->prepare("SELECT * FROM analizar_participantes WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new AnalizarParticipante(
            $row['id'],
            $row['participante_id'],
            $row['estado'],
            $row['comentario'],
            $row['fecha_alta'],
            $row['fecha_modificacion']
        ) : null;
    }

    // READ by participante_id
    public function getByParticipanteId(int $participanteId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM analizar_participantes WHERE participante_id = ?");
        $stmt->execute([$participanteId]);
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new AnalizarParticipante(
                $row['id'],
                $row['participante_id'],
                $row['estado'],
                $row['comentario'],
                $row['fecha_alta'],
                $row['fecha_modificacion']
            );
        }
        return $result;
    }

    // UPDATE
    public function update(AnalizarParticipante $analisis): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE analizar_participantes
            SET estado = ?, comentario = ?, fecha_modificacion = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $analisis->estado,
            $analisis->comentario,
            $analisis->fecha_modificacion ?? date('Y-m-d H:i:s'),
            $analisis->id
        ]);
    }

    // DELETE
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM analizar_participantes WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function existeAnalisisPendiente(int $participante_id): bool
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM analizar_participantes WHERE participante_id = ? AND estado IN (0,1)");
        $stmt->execute([$participante_id]);
        return $stmt->fetchColumn() > 0;
    }

    public function insertarPendiente(int $participante_id): void
    {
        $stmt = $this->pdo->prepare("INSERT INTO analizar_participantes (participante_id, estado, fecha_alta) VALUES (?, 0, NOW())");
        $stmt->execute([$participante_id]);
    }

    public function obtenerAnalisisPendientes(): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM analizar_participantes WHERE estado = 0 ");
        $stmt->execute();
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new AnalizarParticipante(
                $row['id'],
                $row['participante_id'],
                $row['estado'],
                $row['comentario'],
                $row['fecha_alta'],
                $row['fecha_modificacion']
            );
        }
        return $result;
    }

    public function obtenerPrimerAnalisisPendiente(): ?AnalizarParticipante
    {
        $stmt = $this->pdo->prepare("SELECT * FROM analizar_participantes WHERE estado = 0 LIMIT 1");
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new AnalizarParticipante(
                $row['id'],
                $row['participante_id'],
                $row['estado'],
                $row['comentario'],
                $row['fecha_alta'],
                $row['fecha_modificacion']
            );
        }
        return null;
    }

    // Cambia el estado de un registro en analizar_participantes
    public function cambiarEstado($id, $estado) {
        $stmt = $this->pdo->prepare("UPDATE analizar_participantes SET estado = :estado,
                fecha_modificacion = NOW() WHERE id = :id");
        $stmt->execute([':estado' => $estado, ':id' => $id]);
    }
}