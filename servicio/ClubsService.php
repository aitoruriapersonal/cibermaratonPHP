<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\servicio\CentrosService.php

require_once __DIR__ . '/../dao/ClubsDAO.php';

class ClubsService
{
    private ClubsDAO $dao;

    public function __construct(PDO $pdo)
    {
        $this->dao = new ClubsDAO($pdo);
    }

    // All Clubs
    public function getClubs(): array
    {
        return $this->dao->getClubs();
    }

    // Clubs by id
    public function getClubsById(int $id): ?array
    {
        return $this->dao->getClubsById($id);
    }

    // Clubs by provincia
    public function getClubsByProvincia(int $provinciaId): array
    {
        return $this->dao->getClubsByProvincia($provinciaId);
    }
}