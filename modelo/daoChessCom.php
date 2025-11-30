<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\dao\ChesscomDAO.php

class ChesscomDAO
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Perfil del jugador
    public function getPlayerProfile($playerId): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM chesscom_player_profile WHERE player_id = ?");
        $stmt->execute([$playerId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    // Plataformas de streaming
    public function getPlayerStreamingPlatforms($playerId): array
    {
        $stmt = $this->pdo->prepare("SELECT platform FROM chesscom_player_streaming_platform WHERE player_id = ?");
        $stmt->execute([$playerId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // Estadísticas generales
    public function getPlayerStats($playerId): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM chesscom_player_stats WHERE player_id = ?");
        $stmt->execute([$playerId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    // Estadísticas de modalidad de juego
    public function getGameStats($statsId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM chesscom_game_stats WHERE stats_id = ?");
        $stmt->execute([$statsId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Estadísticas de táctica
    public function getTacticsStats($statsId): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM chesscom_tactics_stats WHERE stats_id = ?");
        $stmt->execute([$statsId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    // Estadísticas de puzzle rush
    public function getPuzzleRushStats($statsId): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM chesscom_puzzle_rush_stats WHERE stats_id = ?");
        $stmt->execute([$statsId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    // Archivos mensuales de partidas
    public function getPlayerArchives($playerId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM chesscom_player_games_archive WHERE player_id = ?");
        $stmt->execute([$playerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Partidas de un mes
    public function getMonthGames($archiveId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM chesscom_player_month_game WHERE archive_id = ?");
        $stmt->execute([$archiveId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Partidas en vivo
    public function getLiveGames($playerId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM chesscom_player_live_game WHERE player_id = ?");
        $stmt->execute([$playerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Jugadores de una partida en vivo
    public function getLiveGamePlayers($liveGameId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM chesscom_player_live_game_player WHERE live_game_id = ?");
        $stmt->execute([$liveGameId]);
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}