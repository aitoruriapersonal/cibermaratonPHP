<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\dao\EstudiosDAO.php

class EstudiosDAO
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Universidad
    public function getUniversidades(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM universidad");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUniversidadById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM universidad WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    // Tipos de estudio
    public function getTiposEstudio(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM tipo_estudio");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Campus
    public function getCampusByUniversidad(int $universidadId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM campus WHERE universidad_id = ?");
        $stmt->execute([$universidadId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Centros
    public function getCentrosByCampus(int $campusId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM centro WHERE campus_id = ?");
        $stmt->execute([$campusId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Grados
    public function getGradosByCentro(int $centroId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM grado WHERE centro_id = ?");
        $stmt->execute([$centroId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Postgrados
    public function getPostgradosByUniversidad(int $universidadId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM postgrado WHERE universidad_id = ?");
        $stmt->execute([$universidadId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Doctorados
    public function getDoctoradosByUniversidad(int $universidadId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM doctorado WHERE universidad_id = ?");
        $stmt->execute([$universidadId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}