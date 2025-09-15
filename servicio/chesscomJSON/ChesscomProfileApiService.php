<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\servicio\ChesscomProfileApiService.php

require_once __DIR__ . '/../../modelo/chesscomJSON/ChesscomProfileApiModel.php';

class ChesscomProfileApiService
{
    public function fetchProfile(string $username, int $participante_id): ?ChesscomProfileApiModel
    {
        $url = "https://api.chess.com/pub/player/" . $username;
        $json = @file_get_contents($url);
        //echo '<br/> URL: ' . $url;
        //echo '<br/> JSON: ' . $json;
        if ($json === false) {
            return null;
        }
        $data = json_decode($json, true);
        //echo '<br/> DATA: ' . $json;
        if (!is_array($data) || !isset($data['player_id'])) {
            return null;
        }
        //echo '<br/> Conversion: ';
        return ChesscomProfileApiModel::fromApiJson($data, $participante_id);
    }
}