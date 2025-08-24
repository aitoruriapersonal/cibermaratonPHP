<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\dao\ProvinciasDAO.php

class ProvinciasDAO
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getProvincias(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM provincias");
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new Provincia(
                $row['id'],
                $row['nombre_esp'],
                $row['nombre_eus'],
                $row['cod_provincia'],
                $row['fecha_alta'],
                $row['fecha_modificacion']
            );
        }
        return $result;
    }

     // Provincias by id
    public function getProvinciasById(int $id): ?Provincia
    {
        $stmt = $this->pdo->prepare("SELECT * FROM provincias WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? new Provincia(
            $result['id'],
            $result['nombre_esp'],
            $result['nombre_eus'],
            $result['cod_provincia'],
            $result['fecha_alta'],
            $result['fecha_modificacion']
        ) : null;
    }

    public function create(Provincia $provincia): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO provincias (nombre_esp, nombre_eus, cod_provincia, fecha_alta, fecha_modificacion)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $provincia->nombre_esp,
            $provincia->nombre_eus,
            $provincia->cod_provincia,
            $provincia->fecha_alta,
            $provincia->fecha_modificacion
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function update(Provincia $provincia): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE provincias
            SET nombre_esp = ?, nombre_eus = ?, cod_provincia = ?, fecha_modificacion = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $provincia->nombre_esp,
            $provincia->nombre_eus,
            $provincia->cod_provincia,
            $provincia->fecha_modificacion ?? date('Y-m-d H:i:s'),
            $provincia->id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM provincias WHERE id = ?");
        return $stmt->execute([$id]);
    }

}