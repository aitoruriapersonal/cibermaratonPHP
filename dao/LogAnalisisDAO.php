<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\dao\LogAnalisisDAO.php

require_once __DIR__ . '/../modelos/LogAnalisis.php';

class LogAnalisisDAO
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create(LogAnalisis $log): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO log_analisis (analisis_id, paso, comentario, fecha_alta, fecha_modificacion)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $log->analisis_id,
            $log->paso,
            $log->comentario,
            $log->fecha_alta,
            $log->fecha_modificacion
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function getById(int $id): ?LogAnalisis
    {
        $stmt = $this->pdo->prepare("SELECT * FROM log_analisis WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new LogAnalisis(
            $row['id'],
            $row['analisis_id'],
            $row['paso'],
            $row['comentario'],
            $row['fecha_alta'],
            $row['fecha_modificacion']
        ) : null;
    }

    public function getByAnalisisId(int $analisisId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM log_analisis WHERE analisis_id = ? ORDER BY fecha_alta ASC");
        $stmt->execute([$analisisId]);
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new LogAnalisis(
                $row['id'],
                $row['analisis_id'],
                $row['paso'],
                $row['comentario'],
                $row['fecha_alta'],
                $row['fecha_modificacion']
            );
        }
        return $result;
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM log_analisis ORDER BY fecha_alta DESC");
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new LogAnalisis(
                $row['id'],
                $row['analisis_id'],
                $row['paso'],
                $row['comentario'],
                $row['fecha_alta'],
                $row['fecha_modificacion']
            );
        }
        return $result;
    }

    public function update(LogAnalisis $log): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE log_analisis
            SET paso = ?, comentario = ?, fecha_modificacion = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $log->paso,
            $log->comentario,
            $log->fecha_modificacion ?? date('Y-m-d H:i:s'),
            $log->id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM log_analisis WHERE id = ?");
        return $stmt->execute([$id]);
    }
}