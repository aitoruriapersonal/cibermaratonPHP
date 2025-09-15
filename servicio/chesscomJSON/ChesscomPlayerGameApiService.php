<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\servicio\ChesscomPlayerGameApiService.php

require_once __DIR__ . '/../../modelo/chesscomJSON/ChesscomPlayerGameApiModel.php';

class ChesscomPlayerGameApiService
{
    /**
     * Devuelve un array de ChesscomPlayerGameApiModel
     */
    public function fetchGames(string $username): array
    {
        $url = "https://api.chess.com/pub/player/" . urlencode($username) . "/games";
        $json = @file_get_contents($url);
        if ($json === false) {
            return [];
        }
        $data = json_decode($json, true);
        if (!is_array($data) || !isset($data['games'])) {
            return [];
        }
        $result = [];
        foreach ($data['games'] as $gameJson) {
            $result[] = ChesscomPlayerGameApiModel::fromJson($gameJson);
        }
        return $result;
    }

    /**
     * Devuelve un array de ChesscomPlayerGameApiModel
     */
    public function fetchGamesPorRitmo(string $username, string $ritmo): array
    {
        $url = "https://api.chess.com/pub/player/" . urlencode($username) . "/games/live/".$ritmo;
        $json = @file_get_contents($url);
        if ($json === false) {
            return [];
        }
        $data = json_decode($json, true);
        if (!is_array($data) || !isset($data['games'])) {
            return [];
        }
        $result = [];
        foreach ($data['games'] as $gameJson) {
            $result[] = ChesscomPlayerGameApiModel::fromJson($gameJson);
        }
        return $result;
    }


}