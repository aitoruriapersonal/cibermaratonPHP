<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\dao\CentrosDAO.php

require_once __DIR__ . '/../modelo/Centro.php';

class CentrosDAO
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getCentros(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM centros");
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new Centro(
                $row['id'],
                $row['campus_id'],
                $row['nombre_eus'],
                $row['nombre_esp'],
                $row['estado'],
                $row['fecha_alta'],
                $row['fecha_modificacion']
            );
        }
        return $result;
    }

     // Centros by id
    public function getCentrosById(int $id): ?Centro
    {
        $stmt = $this->pdo->prepare("SELECT * FROM centros WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? new Centro(
            $result['id'],
            $result['campus_id'],
            $result['nombre_eus'],
            $result['nombre_esp'],
            $result['estado'],
            $result['fecha_alta'],
            $result['fecha_modificacion']
        ) : null;
    }

    // Centros by campus
    public function getCentrosByCampus(int $campusId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM centros WHERE campus_id = ?");
        $stmt->execute([$campusId]);
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new Centro(
                $row['id'],
                $row['campus_id'],
                $row['nombre_eus'],
                $row['nombre_esp'],
                $row['estado'],
                $row['fecha_alta'],
                $row['fecha_modificacion']
            );
        }
        return $result;
    }
    // CREATE
    public function create(Centro $centro): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO centros (campus_id, nombre_eus, nombre_esp, estado, fecha_alta, fecha_modificacion)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $centro->campus_id,
            $centro->nombre_eus,
            $centro->nombre_esp,
            $centro->estado,
            $centro->fecha_alta,
            $centro->fecha_modificacion
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function update(Centro $centro): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE centros
            SET campus_id = ?, nombre_eus = ?, nombre_esp = ?, estado = ?, fecha_modificacion = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $centro->campus_id,
            $centro->nombre_eus,
            $centro->nombre_esp,
            $centro->estado,
            $centro->fecha_modificacion ?? date('Y-m-d H:i:s'),
            $centro->id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM centros WHERE id = ?");
        return $stmt->execute([$id]);
    }
}