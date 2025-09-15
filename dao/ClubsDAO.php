<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\dao\ClubsDAO.php

require_once __DIR__ . '/../modelo/Club.php';

class ClubsDAO
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getClubs(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM clubs");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

     // Clubs by id
    public function getClubsById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM clubs WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    // Clubs by provincia
    public function getClubsByProvincia(int $provinciaId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM clubs WHERE provincia_id = ?");
        $stmt->execute([$provinciaId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}