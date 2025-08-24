<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\dao\CampusDAO.php

class CampusDAO
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getCampus(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM campus");
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new Campus(
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

        // Campus by id
    public function getCampusById(int $id): ?Campus
    {
        $stmt = $this->pdo->prepare("SELECT * FROM campus WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new Campus(
            $row['id'],
            $row['universidad_id'],
            $row['nombre_eus'],
            $row['nombre_esp'],
            $row['fecha_alta'],
            $row['fecha_modificacion']
        ) : null;
    }

    // Campus by universidad
    public function getCampusByUniversidad(int $universidadId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM campus WHERE universidad_id = ?");
        $stmt->execute([$universidadId]);
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new Campus(
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

    public function create(Campus $campus): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO campus (universidad_id, nombre_eus, nombre_esp, fecha_alta, fecha_modificacion)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $campus->universidad_id, // Make sure Campus class has public $universidad_id
            $campus->nombre_eus,
            $campus->nombre_esp,
            $campus->fecha_alta,
            $campus->fecha_modificacion
        ]);
        return (int)$this->pdo->lastInsertId();
    }
    public function update(Campus $campus): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE campus
            SET universidad_id = ?, nombre_eus = ?, nombre_esp = ?, fecha_modificacion = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $campus->universidad_id,
            $campus->nombre_eus,
            $campus->nombre_esp,
            $campus->fecha_modificacion ?? date('Y-m-d H:i:s'),
            $campus->id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM campus WHERE id = ?");
        return $stmt->execute([$id]);
    }
}