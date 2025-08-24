<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\servicio\ChesscomProfileApiService.php

require_once __DIR__ . '/../modelo/chesscomJSON/ChesscomProfileApiModel.php';

class ChesscomProfileApiService
{
    public function fetchProfile(string $username, int $participante_id): ?ChesscomProfileApiModel
    {
        $url = "https://api.chess.com/pub/player/" . urlencode($username);
        $json = @file_get_contents($url);
        if ($json === false) {
            return null;
        }
        $data = json_decode($json, true);
        if (!is_array($data) || !isset($data['player_id'])) {
            return null;
        }
        return ChesscomProfileApiModel::fromApiJson($data, $participante_id);
    }
}