<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\modelo\chesscomJSON\ChesscomPlayerStatsApiModel.php

class ChesscomPlayerStatsApiModel
{
    public array $chess_rapid = [];
    public array $chess_bullet = [];
    public array $chess_blitz = [];
    public array $tactics = [];
    public array $puzzle_rush = [];

    public static function fromJson(array $json): ChesscomPlayerStatsApiModel
    {
        $model = new self();
        $model->chess_rapid = $json['chess_rapid'] ?? [];
        $model->chess_bullet = $json['chess_bullet'] ?? [];
        $model->chess_blitz = $json['chess_blitz'] ?? [];
        $model->tactics = $json['tactics'] ?? [];
        $model->puzzle_rush = $json['puzzle_rush'] ?? [];
        return $model;
    }
}