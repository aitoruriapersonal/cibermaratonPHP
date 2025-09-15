<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\modelos\ChesscomPlayerStats.php

class ChesscomPlayerStats
{
    public ?int $id;
    public int $participante_id;
    public string $username;
    public ?int $rapid_last_rating;
    public ?int $rapid_last_date;
    public ?int $rapid_last_rd;
    public ?int $rapid_best_rating;
    public ?int $rapid_best_date;
    public ?string $rapid_best_game;
    public ?int $rapid_record_win;
    public ?int $rapid_record_loss;
    public ?int $rapid_record_draw;
    public ?int $bullet_last_rating;
    public ?int $bullet_last_date;
    public ?int $bullet_last_rd;
    public ?int $bullet_best_rating;
    public ?int $bullet_best_date;
    public ?string $bullet_best_game;
    public ?int $bullet_record_win;
    public ?int $bullet_record_loss;
    public ?int $bullet_record_draw;
    public ?int $blitz_last_rating;
    public ?int $blitz_last_date;
    public ?int $blitz_last_rd;
    public ?int $blitz_best_rating;
    public ?int $blitz_best_date;
    public ?string $blitz_best_game;
    public ?int $blitz_record_win;
    public ?int $blitz_record_loss;
    public ?int $blitz_record_draw;
    public ?int $tactics_highest_rating;
    public ?int $tactics_highest_date;
    public ?int $tactics_lowest_rating;
    public ?int $tactics_lowest_date;
    public ?int $puzzle_rush_best_total_attempts;
    public ?int $puzzle_rush_best_score;
    public string $fecha_alta;
    public ?string $fecha_modificacion;

    public function __construct(
        ?int $id,
        int $participante_id,
        string $username,
        ?int $rapid_last_rating = null,
        ?int $rapid_last_date = null,
        ?int $rapid_last_rd = null,
        ?int $rapid_best_rating = null,
        ?int $rapid_best_date = null,
        ?string $rapid_best_game = null,
        ?int $rapid_record_win = null,
        ?int $rapid_record_loss = null,
        ?int $rapid_record_draw = null,
        ?int $bullet_last_rating = null,
        ?int $bullet_last_date = null,
        ?int $bullet_last_rd = null,
        ?int $bullet_best_rating = null,
        ?int $bullet_best_date = null,
        ?string $bullet_best_game = null,
        ?int $bullet_record_win = null,
        ?int $bullet_record_loss = null,
        ?int $bullet_record_draw = null,
        ?int $blitz_last_rating = null,
        ?int $blitz_last_date = null,
        ?int $blitz_last_rd = null,
        ?int $blitz_best_rating = null,
        ?int $blitz_best_date = null,
        ?string $blitz_best_game = null,
        ?int $blitz_record_win = null,
        ?int $blitz_record_loss = null,
        ?int $blitz_record_draw = null,
        ?int $tactics_highest_rating = null,
        ?int $tactics_highest_date = null,
        ?int $tactics_lowest_rating = null,
        ?int $tactics_lowest_date = null,
        ?int $puzzle_rush_best_total_attempts = null,
        ?int $puzzle_rush_best_score = null,
        ?string $fecha_alta = null,
        ?string $fecha_modificacion = null
    ) {
        $this->id = $id;
        $this->participante_id = $participante_id;
        $this->username = $username;
        $this->rapid_last_rating = $rapid_last_rating;
        $this->rapid_last_date = $rapid_last_date;
        $this->rapid_last_rd = $rapid_last_rd;
        $this->rapid_best_rating = $rapid_best_rating;
        $this->rapid_best_date = $rapid_best_date;
        $this->rapid_best_game = $rapid_best_game;
        $this->rapid_record_win = $rapid_record_win;
        $this->rapid_record_loss = $rapid_record_loss;
        $this->rapid_record_draw = $rapid_record_draw;
        $this->bullet_last_rating = $bullet_last_rating;
        $this->bullet_last_date = $bullet_last_date;
        $this->bullet_last_rd = $bullet_last_rd;
        $this->bullet_best_rating = $bullet_best_rating;
        $this->bullet_best_date = $bullet_best_date;
        $this->bullet_best_game = $bullet_best_game;
        $this->bullet_record_win = $bullet_record_win;
        $this->bullet_record_loss = $bullet_record_loss;
        $this->bullet_record_draw = $bullet_record_draw;
        $this->blitz_last_rating = $blitz_last_rating;
        $this->blitz_last_date = $blitz_last_date;
        $this->blitz_last_rd = $blitz_last_rd;
        $this->blitz_best_rating = $blitz_best_rating;
        $this->blitz_best_date = $blitz_best_date;
        $this->blitz_best_game = $blitz_best_game;
        $this->blitz_record_win = $blitz_record_win;
        $this->blitz_record_loss = $blitz_record_loss;
        $this->blitz_record_draw = $blitz_record_draw;
        $this->tactics_highest_rating = $tactics_highest_rating;
        $this->tactics_highest_date = $tactics_highest_date;
        $this->tactics_lowest_rating = $tactics_lowest_rating;
        $this->tactics_lowest_date = $tactics_lowest_date;
        $this->puzzle_rush_best_total_attempts = $puzzle_rush_best_total_attempts;
        $this->puzzle_rush_best_score = $puzzle_rush_best_score;
        $this->fecha_alta = $fecha_alta ?? date('Y-m-d H:i:s');
        $this->fecha_modificacion = $fecha_modificacion;
    }
}