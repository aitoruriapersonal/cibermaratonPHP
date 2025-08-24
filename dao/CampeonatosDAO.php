<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\dao\CampeonatoDAO.php

class CampeonatosDAO
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getCampeonatos(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM campeonatos");
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new Campeonato(
                $row['id'],
                $row['tipo_campeonato'],
                $row['nombre'],
                $row['estado'],
                $row['fecha_inicio'],
                $row['fecha_fin'],
                $row['descripcion'],
                $row['email_1'],
                $row['email_2'],
                $row['telefono_1'],
                $row['telefono_2'],
                $row['fecha_alta'],
                $row['fecha_modificacion']
            );
        }
        return $result;
    }

    public function getCampeonatoById(int $id): ?Campeonato
    {
        $stmt = $this->pdo->prepare("SELECT * FROM campeonatos WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new Campeonato(
            $row['id'],
            $row['tipo_campeonato'],
            $row['nombre'],
            $row['estado'],
            $row['fecha_inicio'],
            $row['fecha_fin'],
            $row['descripcion'],
            $row['email_1'],
            $row['email_2'],
            $row['telefono_1'],
            $row['telefono_2'],
            $row['fecha_alta'],
            $row['fecha_modificacion']
        ) : null;
    }

    public function getCampeonatoActivoByTipo(mixed $tipo): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM campeonatos WHERE tipo_campeonato = ? AND estado = 'activo'");
        $stmt->execute([$tipo]);
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new Campeonato(
                $row['id'],
                $row['tipo_campeonato'],
                $row['nombre'],
                $row['estado'],
                $row['fecha_inicio'],
                $row['fecha_fin'],
                $row['descripcion'],
                $row['email_1'],
                $row['email_2'],
                $row['telefono_1'],
                $row['telefono_2'],
                $row['fecha_alta'],
                $row['fecha_modificacion']
            );
        }
        return $result;
    }

    public function create(Campeonato $campeonato): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO campeonatos (
                tipo_campeonato, nombre, estado, fecha_inicio, fecha_fin, descripcion,
                email_1, email_2, telefono_1, telefono_2, fecha_alta, fecha_modificacion
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $campeonato->tipo_campeonato,
            $campeonato->nombre,
            $campeonato->estado,
            $campeonato->fecha_inicio,
            $campeonato->fecha_fin,
            $campeonato->descripcion,
            $campeonato->email_1,
            $campeonato->email_2,
            $campeonato->telefono_1,
            $campeonato->telefono_2,
            $campeonato->fecha_alta,
            $campeonato->fecha_modificacion
        ]);
        return (int)$this->pdo->lastInsertId();
    }
    public function update(Campeonato $campeonato): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE campeonatos SET
                tipo_campeonato = ?, nombre = ?, estado = ?, fecha_inicio = ?, fecha_fin = ?, descripcion = ?,
                email_1 = ?, email_2 = ?, telefono_1 = ?, telefono_2 = ?, fecha_modificacion = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $campeonato->tipo_campeonato,
            $campeonato->nombre,
            $campeonato->estado,
            $campeonato->fecha_inicio,
            $campeonato->fecha_fin,
            $campeonato->descripcion,
            $campeonato->email_1,
            $campeonato->email_2,
            $campeonato->telefono_1,
            $campeonato->telefono_2,
            $campeonato->fecha_modificacion ?? date('Y-m-d H:i:s'),
            $campeonato->id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM campeonatos WHERE id = ?");
        return $stmt->execute([$id]);
    }

}