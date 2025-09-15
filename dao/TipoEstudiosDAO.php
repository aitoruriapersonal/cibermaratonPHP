<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\dao\TipoEstudiosDAO.php

require_once __DIR__ . '/../modelo/TipoEstudio.php';

class TipoEstudiosDAO
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Tipos de estudio by id
    public function getTipoEstudios(): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM tipo_estudios ");
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new TipoEstudio(
                $row['id'],
                $row['universidad_id'],
                $row['nombre_eus'],
                $row['nombre_esp'],
                $row['fecha_alta'],
                $row['fecha_modificacion']
            );
        }
        return $result;
    }

    // Tipos de estudio by id
    public function getTipoEstudioById(int $id): ?TipoEstudio
    {
        $stmt = $this->pdo->prepare("SELECT * FROM tipo_estudios WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? new TipoEstudio(
            $result['id'],
            $result['universidad_id'],
            $result['nombre_eus'],
            $result['nombre_esp'],
            $result['fecha_alta'],
            $result['fecha_modificacion']
        ) : null;
    }

    // Tipos de estudio by universidad
    public function getTipoEstudioByUniversidad(int $universidadId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM tipo_estudios WHERE universidad_id = ?");
        $stmt->execute([$universidadId]);
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new TipoEstudio(
                $row['id'],
                $row['universidad_id'],
                $row['nombre_eus'],
                $row['nombre_esp'],
                $row['fecha_alta'],
                $row['fecha_modificacion']
            );
        }
        return $result;
    }

    public function create(TipoEstudio $tipo): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO tipo_estudios (universidad_id, nombre_eus, nombre_esp, fecha_alta, fecha_modificacion)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $tipo->universidad_id,
            $tipo->nombre_eus,
            $tipo->nombre_esp,
            $tipo->fecha_alta,
            $tipo->fecha_modificacion
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function update(TipoEstudio $tipo): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE tipo_estudios
            SET universidad_id = ?, nombre_eus = ?, nombre_esp = ?, fecha_modificacion = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $tipo->universidad_id,
            $tipo->nombre_eus,
            $tipo->nombre_esp,
            $tipo->fecha_modificacion ?? date('Y-m-d H:i:s'),
            $tipo->id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM tipo_estudios WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
}