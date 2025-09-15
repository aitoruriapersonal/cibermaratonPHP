<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\conversores\ChesscomPlayerGameApiConverter.php

require_once __DIR__ . '/../modelo/ChesscomPlayerGame.php';
require_once __DIR__ . '/../modelo/chesscomJSON/ChesscomPlayerGameApiModel.php';

class ChesscomPlayerGameApiConverter
{
    // Convierte un modelo API a modelo de tabla
    public static function toChesscomPlayerGame(
        ChesscomPlayerGameApiModel $apiModel,
        int $participante_id,
        string $username
    ): ChesscomPlayerGame {
        return new ChesscomPlayerGame(
            null, // id autoincremental
            $participante_id,
            $username,
            $apiModel->url,
            $apiModel->pgn ?? null,
            $apiModel->time_control ?? null,
            $apiModel->end_time ?? null,
            isset($apiModel->rated) ? (int)$apiModel->rated : null,
            $apiModel->accuracies['white'] ?? null,
            $apiModel->accuracies['black'] ?? null,
            $apiModel->fen ?? null,
            $apiModel->time_class ?? null,
            $apiModel->rules ?? null,
            $apiModel->white['rating'] ?? null,
            $apiModel->white['result'] ?? null,
            $apiModel->white['@id'] ?? null,
            $apiModel->white['username'] ?? null,
            $apiModel->white['uuid'] ?? null,
            $apiModel->black['rating'] ?? null,
            $apiModel->black['result'] ?? null,
            $apiModel->black['@id'] ?? null,
            $apiModel->black['username'] ?? null,
            $apiModel->black['uuid'] ?? null,
            $apiModel->eco ?? null,
            date('Y-m-d H:i:s'),
            null
        );
    }

    // Convierte un modelo de tabla a modelo API
    public static function toChesscomPlayerGameApiModel(ChesscomPlayerGame $game): ChesscomPlayerGameApiModel
    {
        $apiModel = new ChesscomPlayerGameApiModel();
        $apiModel->url = $game->url;
        $apiModel->pgn = $game->pgn;
        $apiModel->time_control = $game->time_control;
        $apiModel->end_time = $game->end_time;
        $apiModel->rated = $game->rated;
        $apiModel->accuracies = [
            'white' => $game->accuracy_white,
            'black' => $game->accuracy_black
        ];
        $apiModel->fen = $game->fen;
        $apiModel->time_class = $game->time_class;
        $apiModel->rules = $game->rules;
        $apiModel->white = [
            'rating' => $game->white_rating,
            'result' => $game->white_result,
            '@id' => $game->white_id,
            'username' => $game->white_username,
            'uuid' => $game->white_uuid
        ];
        $apiModel->black = [
            'rating' => $game->black_rating,
            'result' => $game->black_result,
            '@id' => $game->black_id,
            'username' => $game->black_username,
            'uuid' => $game->black_uuid
        ];
        $apiModel->eco = $game->eco;
        return $apiModel;
    }
}