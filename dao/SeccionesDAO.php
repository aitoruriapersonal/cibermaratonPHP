<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\dao\CampeonatoDAO.php

class SeccionesDAO
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getSecciones(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM secciones_bases");
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new SeccionBase(
                $row['id'],
                $row['base_id'],
                $row['titulo'],
                $row['orden'],
                $row['fecha_alta'],
                $row['fecha_modificacion']
            );
        }
        return $result;
    }

    public function getSeccionById(int $id): ?SeccionBase
    {
        $stmt = $this->pdo->prepare("SELECT * FROM secciones_bases WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new SeccionBase(
            $row['id'],
            $row['base_id'],
            $row['titulo'],
            $row['orden'],
            $row['fecha_alta'],
            $row['fecha_modificacion']
        ) : null;
    }
    
    public function getSeccionesByBasesId(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM secciones_bases WHERE bases_id = ? ORDER BY orden ASC");
        $stmt->execute([$id]);
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new SeccionBase(
                $row['id'],
                $row['base_id'],
                $row['titulo'],
                $row['orden'],
                $row['fecha_alta'],
                $row['fecha_modificacion']
            );
        }
        return $result;
    }

    public function create(SeccionBase $seccion): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO secciones_bases (base_id, titulo, orden, fecha_alta, fecha_modificacion)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $seccion->base_id,
            $seccion->titulo,
            $seccion->orden,
            $seccion->fecha_alta,
            $seccion->fecha_modificacion
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function update(SeccionBase $seccion): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE secciones_bases
            SET base_id = ?, titulo = ?, orden = ?, fecha_modificacion = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $seccion->base_id,
            $seccion->titulo,
            $seccion->orden,
            $seccion->fecha_modificacion ?? date('Y-m-d H:i:s'),
            $seccion->id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM secciones_bases WHERE id = ?");
        return $stmt->execute([$id]);
    }
}