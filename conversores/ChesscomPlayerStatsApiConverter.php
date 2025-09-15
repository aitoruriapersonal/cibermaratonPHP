<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\conversores\ChesscomPlayerStatsApiConverter.php

require_once __DIR__ . '/../modelo/ChesscomPlayerStats.php';
require_once __DIR__ . '/../modelo/chesscomJSON/ChesscomPlayerStatsApiModel.php';

class ChesscomPlayerStatsApiConverter
{
    public static function toChesscomPlayerStats(ChesscomPlayerStatsApiModel $apiModel, Participante $participante): ChesscomPlayerStats
    {
        return new ChesscomPlayerStats(
            null, // id (autoincremental)
            $participante->id,
            $participante->nick,
            // Rapid
            $apiModel->chess_rapid['last']['rating'] ?? null,
            $apiModel->chess_rapid['last']['date'] ?? null,
            $apiModel->chess_rapid['last']['rd'] ?? null,
            $apiModel->chess_rapid['best']['rating'] ?? null,
            $apiModel->chess_rapid['best']['date'] ?? null,
            $apiModel->chess_rapid['best']['game'] ?? null,
            $apiModel->chess_rapid['record']['win'] ?? null,
            $apiModel->chess_rapid['record']['loss'] ?? null,
            $apiModel->chess_rapid['record']['draw'] ?? null,
            // Bullet
            $apiModel->chess_bullet['last']['rating'] ?? null,
            $apiModel->chess_bullet['last']['date'] ?? null,
            $apiModel->chess_bullet['last']['rd'] ?? null,
            $apiModel->chess_bullet['best']['rating'] ?? null,
            $apiModel->chess_bullet['best']['date'] ?? null,
            $apiModel->chess_bullet['best']['game'] ?? null,
            $apiModel->chess_bullet['record']['win'] ?? null,
            $apiModel->chess_bullet['record']['loss'] ?? null,
            $apiModel->chess_bullet['record']['draw'] ?? null,
            // Blitz
            $apiModel->chess_blitz['last']['rating'] ?? null,
            $apiModel->chess_blitz['last']['date'] ?? null,
            $apiModel->chess_blitz['last']['rd'] ?? null,
            $apiModel->chess_blitz['best']['rating'] ?? null,
            $apiModel->chess_blitz['best']['date'] ?? null,
            $apiModel->chess_blitz['best']['game'] ?? null,
            $apiModel->chess_blitz['record']['win'] ?? null,
            $apiModel->chess_blitz['record']['loss'] ?? null,
            $apiModel->chess_blitz['record']['draw'] ?? null,
            // Tactics
            $apiModel->tactics['highest']['rating'] ?? null,
            $apiModel->tactics['highest']['date'] ?? null,
            $apiModel->tactics['lowest']['rating'] ?? null,
            $apiModel->tactics['lowest']['date'] ?? null,
            // Puzzle rush
            $apiModel->puzzle_rush['best']['total_attempts'] ?? null,
            $apiModel->puzzle_rush['best']['score'] ?? null,
            // fechas
            date('Y-m-d H:i:s'),
            null
        );
    }

    public static function toChesscomPlayerStatsApiModel(ChesscomPlayerStats $stats): ChesscomPlayerStatsApiModel
    {
        $apiModel = new ChesscomPlayerStatsApiModel();

        $apiModel->chess_rapid = [
            'last' => [
                'rating' => $stats->rapid_last_rating,
                'date' => $stats->rapid_last_date,
                'rd' => $stats->rapid_last_rd
            ],
            'best' => [
                'rating' => $stats->rapid_best_rating,
                'date' => $stats->rapid_best_date,
                'game' => $stats->rapid_best_game
            ],
            'record' => [
                'win' => $stats->rapid_record_win,
                'loss' => $stats->rapid_record_loss,
                'draw' => $stats->rapid_record_draw
            ]
        ];

        $apiModel->chess_bullet = [
            'last' => [
                'rating' => $stats->bullet_last_rating,
                'date' => $stats->bullet_last_date,
                'rd' => $stats->bullet_last_rd
            ],
            'best' => [
                'rating' => $stats->bullet_best_rating,
                'date' => $stats->bullet_best_date,
                'game' => $stats->bullet_best_game
            ],
            'record' => [
                'win' => $stats->bullet_record_win,
                'loss' => $stats->bullet_record_loss,
                'draw' => $stats->bullet_record_draw
            ]
        ];

        $apiModel->chess_blitz = [
            'last' => [
                'rating' => $stats->blitz_last_rating,
                'date' => $stats->blitz_last_date,
                'rd' => $stats->blitz_last_rd
            ],
            'best' => [
                'rating' => $stats->blitz_best_rating,
                'date' => $stats->blitz_best_date,
                'game' => $stats->blitz_best_game
            ],
            'record' => [
                'win' => $stats->blitz_record_win,
                'loss' => $stats->blitz_record_loss,
                'draw' => $stats->blitz_record_draw
            ]
        ];

        $apiModel->tactics = [
            'highest' => [
                'rating' => $stats->tactics_highest_rating,
                'date' => $stats->tactics_highest_date
            ],
            'lowest' => [
                'rating' => $stats->tactics_lowest_rating,
                'date' => $stats->tactics_lowest_date
            ]
        ];

        $apiModel->puzzle_rush = [
            'best' => [
                'total_attempts' => $stats->puzzle_rush_best_total_attempts,
                'score' => $stats->puzzle_rush_best_score
            ]
        ];

        return $apiModel;
    }
}