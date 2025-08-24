<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\dao\CampeonatoDAO.php

class BasesDAO
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getBases(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM bases_campeonatos");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBasesById(int $id): ?BaseCampeonato
    {
        $stmt = $this->pdo->prepare("SELECT * FROM bases_campeonatos WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? new BaseCampeonato(
            $result['id'],
            $result['campeonato_id'],
            $result['titulo'],
            $result['fecha_alta'],
            $result['fecha_modificacion']
        ) : null;
    }

    public function getBasesByCampeonatoId(int $id): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM bases_campeonatos WHERE campeonato_id = ?");
        $stmt->execute([$id]);
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new BaseCampeonato(
                $row['id'],
                $row['campeonato_id'],
                $row['titulo'],
                $row['fecha_alta'],
                $row['fecha_modificacion']
            );
        }
        return $result;
    }

    public function create(BaseCampeonato $base): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO bases_campeonatos (campeonato_id, titulo, fecha_alta, fecha_modificacion)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([
            $base->campeonato_id,
            $base->titulo,
            $base->fecha_alta,
            $base->fecha_modificacion
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function update(BaseCampeonato $base): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE bases_campeonatos
            SET campeonato_id = ?, titulo = ?, fecha_modificacion = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $base->campeonato_id,
            $base->titulo,
            $base->fecha_modificacion ?? date('Y-m-d H:i:s'),
            $base->id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM bases_campeonatos WHERE id = ?");
        return $stmt->execute([$id]);
    }

}