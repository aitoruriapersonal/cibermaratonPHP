<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\dao\EstudiosDAO.php

class EstudiosDAO
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // All Estudios
    public function getEstudios(): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM estudios");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Estudios by centro and tipo estudios
    public function getEstudiosByCentroAndTipoEstudios(int $centroId, int $tipoEstudioId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM estudios WHERE centro_id = ? AND tipo_estudio_id = ?");
        $stmt->execute([$centroId, $tipoEstudioId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

        // Estudios by centro and tipo estudios
    public function getEstudiosByCentro(int $centroId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM estudios WHERE centro_id = ?");
        $stmt->execute([$centroId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

        // Estudios by centro and tipo estudios
    public function getEstudiosByTipoEstudios(int $tipoEstudioId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM estudios WHERE tipo_estudio_id = ?");
        $stmt->execute([$tipoEstudioId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}