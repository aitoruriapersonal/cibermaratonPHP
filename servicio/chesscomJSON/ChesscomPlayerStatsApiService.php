<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\servicio\ChesscomPlayerStatsApiService.php

require_once __DIR__ . '/../modelo/chesscomJSON/ChesscomPlayerStatsApiModel.php';

class ChesscomPlayerStatsApiService
{
    public function fetchStats(string $username): ?ChesscomPlayerStatsApiModel
    {
        $url = "https://api.chess.com/pub/player/" . urlencode($username) . "/stats";
        $json = @file_get_contents($url);
        if ($json === false) {
            return null;
        }
        $data = json_decode($json, true);
        if (!is_array($data)) {
            return null;
        }
        return ChesscomPlayerStatsApiModel::fromJson($data);
    }
}