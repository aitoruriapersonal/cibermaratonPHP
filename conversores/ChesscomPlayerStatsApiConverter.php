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
            $apiModel->chess_rapid_last_rating,
            $apiModel->chess_rapid_last_date,
            $apiModel->chess_rapid_last_rd,
            $apiModel->chess_rapid_best_rating,
            $apiModel->chess_rapid_best_date,
            $apiModel->chess_rapid_best_game,
            $apiModel->chess_rapid_record_win,
            $apiModel->chess_rapid_record_loss,
            $apiModel->chess_rapid_record_draw,
            // Bullet
            $apiModel->chess_bullet_last_rating,
            $apiModel->chess_bullet_last_date,
            $apiModel->chess_bullet_last_rd,
            $apiModel->chess_bullet_best_rating,
            $apiModel->chess_bullet_best_date,
            $apiModel->chess_bullet_best_game,
            $apiModel->chess_bullet_record_win,
            $apiModel->chess_bullet_record_loss,
            $apiModel->chess_bullet_record_draw,
            // Blitz
            $apiModel->chess_blitz_last_rating,
            $apiModel->chess_blitz_last_date,
            $apiModel->chess_blitz_last_rd,
            $apiModel->chess_blitz_best_rating,
            $apiModel->chess_blitz_best_date,
            $apiModel->chess_blitz_best_game,
            $apiModel->chess_blitz_record_win,
            $apiModel->chess_blitz_record_loss,
            $apiModel->chess_blitz_record_draw,
            // Tactics
            $apiModel->tactics_highest_rating,
            $apiModel->tactics_highest_date,
            $apiModel->tactics_lowest_rating,
            $apiModel->tactics_lowest_date,
            // Puzzle rush
            $apiModel->puzzle_rush_best_total_attempts,
            $apiModel->puzzle_rush_best_score,
            // fechas
            date('Y-m-d H:i:s'),
            null
        );
    }

    public static function toChesscomPlayerStatsApiModel(ChesscomPlayerStats $stats): ChesscomPlayerStatsApiModel
    {
        return new ChesscomPlayerStatsApiModel(
            0, // stats_id
            $stats->participante_id ?? 0,
            // Rapid
            $stats->rapid_last_rating,
            $stats->rapid_last_date,
            $stats->rapid_last_rd,
            $stats->rapid_best_rating,
            $stats->rapid_best_date,
            $stats->rapid_best_game,
            $stats->rapid_record_win,
            $stats->rapid_record_loss,
            $stats->rapid_record_draw,
            // Bullet
            $stats->bullet_last_rating,
            $stats->bullet_last_date,
            $stats->bullet_last_rd,
            $stats->bullet_best_rating,
            $stats->bullet_best_date,
            $stats->bullet_best_game,
            $stats->bullet_record_win,
            $stats->bullet_record_loss,
            $stats->bullet_record_draw,
            // Blitz
            $stats->blitz_last_rating,
            $stats->blitz_last_date,
            $stats->blitz_last_rd,
            $stats->blitz_best_rating,
            $stats->blitz_best_date,
            $stats->blitz_best_game,
            $stats->blitz_record_win,
            $stats->blitz_record_loss,
            $stats->blitz_record_draw,
            // Tactics
            $stats->tactics_highest_rating,
            $stats->tactics_highest_date,
            $stats->tactics_lowest_rating,
            $stats->tactics_lowest_date,
            // Puzzle rush
            $stats->puzzle_rush_best_total_attempts,
            $stats->puzzle_rush_best_score,
            // fechas
            $stats->fecha_alta,
            $stats->fecha_modificacion
        );
    }
}