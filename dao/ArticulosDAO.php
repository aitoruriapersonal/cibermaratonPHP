<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\dao\CampeonatoDAO.php

require_once __DIR__ . '/../modelo/ArticuloSeccion.php';
class ArticulosDAO
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getArticulos(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM articulos_secciones");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getArticuloById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM articulos_secciones WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function getArticulosBySeccionId(int $seccionId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM articulos_secciones WHERE seccion_id = ? ORDER BY orden ASC");
        $stmt->execute([$seccionId]);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new ArticuloSeccion(
                $row['id'],
                $row['seccion_id'],
                $row['titulo_es'],
                $row['titulo_eu'],
                $row['descripcion_es'],
                $row['descripcion_eu'],
                $row['orden'],
                $row['fecha_alta'],
                $row['fecha_modificacion']
            );
        }
        return $result;
    }

    public function createArticulo(ArticuloSeccion $articulo): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO articulos_secciones (seccion_id, titulo_es, titulo_eu, descripcion_es, descripcion_eu, orden, fecha_alta, fecha_modificacion)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $articulo->seccion_id,
            $articulo->tituloES,
            $articulo->tituloEU,
            $articulo->descripcionES,
            $articulo->descripcionEU,
            $articulo->orden,
            $articulo->fecha_alta,
            $articulo->fecha_modificacion
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function updateArticulo(ArticuloSeccion $articulo): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE articulos_secciones
            SET seccion_id = ?, titulo_es = ?, titulo_eu = ?, descripcion_es = ?, descripcion_eu = ?, orden = ?, fecha_modificacion = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $articulo->seccion_id,
            $articulo->tituloES,
            $articulo->tituloEU,
            $articulo->descripcionES,
            $articulo->descripcionEU,
            $articulo->orden,
            $articulo->fecha_modificacion ?? date('Y-m-d H:i:s'),
            $articulo->id
        ]);
    }

    public function deleteArticulo(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM articulos_secciones WHERE id = ?");
        return $stmt->execute([$id]);
    }

}