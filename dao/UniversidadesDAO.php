<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\dao\UniversidadesDAO.php

class UniversidadesDAO
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Universidad
    public function getUniversidades(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM universidades");
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new Universidad(
                $row['id'],
                $row['nombre_eus'],
                $row['siglas_eus'],
                $row['nombre_esp'],
                $row['siglas_esp'],
                $row['estado'],
                $row['fecha_alta'],
                $row['fecha_modificacion']
            );
        }
        return $result;
    }

    public function getUniversidadById(int $id): ?Universidad
    {
        $stmt = $this->pdo->prepare("SELECT * FROM universidades WHERE id = ? AND estado = 1");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? new Universidad(
            $result['id'],
            $result['nombre_eus'],
            $result['siglas_eus'],
            $result['nombre_esp'],
            $result['siglas_esp'],
            $result['estado'],
            $result['fecha_alta'],
            $result['fecha_modificacion']
        ) : null;
    }

    public function create(Universidad $uni): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO universidades (nombre_eus, siglas_eus, nombre_esp, siglas_esp, estado, fecha_alta, fecha_modificacion)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $uni->nombre_eus,
            $uni->siglas_eus,
            $uni->nombre_esp,
            $uni->siglas_esp,
            $uni->estado,
            $uni->fecha_alta,
            $uni->fecha_modificacion
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function update(Universidad $uni): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE universidades
            SET nombre_eus = ?, siglas_eus = ?, nombre_esp = ?, siglas_esp = ?, estado = ?, fecha_modificacion = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $uni->nombre_eus,
            $uni->siglas_eus,
            $uni->nombre_esp,
            $uni->siglas_esp,
            $uni->estado,
            $uni->fecha_modificacion ?? date('Y-m-d H:i:s'),
            $uni->id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM universidades WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
}