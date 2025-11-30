<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\servicio\ChesscomService.php

require_once __DIR__ . '/../modelo/ChesscomDAO.php';

class ChesscomService
{
    /** @var ChesscomDAO */
    private $dao;

    public function __construct(PDO $pdo)
    {
        $this->dao = new ChesscomDAO($pdo);
    }

    // Perfil del jugador
    public function getPlayerProfile($playerId): ?array
    {
        return $this->dao->getPlayerProfile($playerId);
    }

    // Plataformas de streaming
    public function getPlayerStreamingPlatforms($playerId): array
    {
        return $this->dao->getPlayerStreamingPlatforms($playerId);
    }

    // Estadísticas generales
    public function getPlayerStats($playerId): ?array
    {
        return $this->dao->getPlayerStats($playerId);
    }

    // Estadísticas de modalidad de juego
    public function getGameStats($statsId): array
    {
        return $this->dao->getGameStats($statsId);
    }

    // Estadísticas de táctica
    public function getTacticsStats($statsId): ?array
    {
        return $this->dao->getTacticsStats($statsId);
    }

    // Estadísticas de puzzle rush
    public function getPuzzleRushStats($statsId): ?array
    {
        return $this->dao->getPuzzleRushStats($statsId);
    }

    // Archivos mensuales de partidas
    public function getPlayerArchives($playerId): array
    {
        return $this->dao->getPlayerArchives($playerId);
    }

    // Partidas de un mes
    public function getMonthGames($archiveId): array
    {
        return $this->dao->getMonthGames($archiveId);
    }

    // Partidas en vivo
    public function getLiveGames($playerId): array
    {
        return $this->dao->getLiveGames($playerId);
    }

    // Jugadores de una partida en vivo
    public function getLiveGamePlayers($liveGameId): array
    {
        return $this->dao->getLiveGamePlayers($liveGameId);
    }
}