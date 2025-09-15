<?php
require_once __DIR__ . '/../modelo/ValorGenerico.php';

class ValoresGenericosDAO
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM valores_genericos ORDER BY tipo, valor ASC");
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new ValorGenerico(
                $row['id'],
                $row['tipo'],
                $row['valor'],
                $row['nombre_esp'],
                $row['nombre_eus'],
                $row['descripcion_esp'],
                $row['descripcion_eus'],
                $row['fecha_alta'],
                $row['fecha_modificacion']
            );
        }
        return $result;
    }

    public function getByTipo(string $tipo): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM valores_genericos WHERE tipo = ? ORDER BY valor ASC");
        $stmt->execute([$tipo]);
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new ValorGenerico(
                $row['id'],
                $row['tipo'],
                $row['valor'],
                $row['nombre_esp'],
                $row['nombre_eus'],
                $row['descripcion_esp'],
                $row['descripcion_eus'],
                $row['fecha_alta'],
                $row['fecha_modificacion']
            );
        }
        return $result;
    }

    public function getById(int $id): ?ValorGenerico
    {
        $stmt = $this->pdo->prepare("SELECT * FROM valores_genericos WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new ValorGenerico(
            $row['id'],
            $row['tipo'],
            $row['valor'],
            $row['nombre_esp'],
            $row['nombre_eus'],
            $row['descripcion_esp'],
            $row['descripcion_eus'],
            $row['fecha_alta'],
            $row['fecha_modificacion']
        ) : null;
    }

    public function create(ValorGenerico $valor): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO valores_genericos (tipo, valor, nombre_esp, nombre_eus, descripcion_esp, descripcion_eus, fecha_alta, fecha_modificacion)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $valor->tipo,
            $valor->valor,
            $valor->nombre_esp,
            $valor->nombre_eus,
            $valor->descripcion_esp,
            $valor->descripcion_eus,
            $valor->fecha_alta,
            $valor->fecha_modificacion
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function update(ValorGenerico $valor): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE valores_genericos SET
                tipo = ?,
                valor = ?,
                nombre_esp = ?,
                nombre_eus = ?,
                descripcion_esp = ?,
                descripcion_eus = ?,
                fecha_modificacion = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $valor->tipo,
            $valor->valor,
            $valor->nombre_esp,
            $valor->nombre_eus,
            $valor->descripcion_esp,
            $valor->descripcion_eus,
            date('Y-m-d H:i:s'),
            $valor->id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM valores_genericos WHERE id = ?");
        return $stmt->execute([$id]);
    }
}