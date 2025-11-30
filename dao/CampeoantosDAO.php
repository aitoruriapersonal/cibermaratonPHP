<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\dao\CampeonatoDAO.php

class CampeonatoDAO
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getCampeonatos(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM campeonato");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCampeonatoById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM campeonato WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }
}