<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\modelo\chesscomJSON\ChesscomPlayerStatsApiModel.php

class ChesscomPlayerStatsApiModel
{
    public int $stats_id;
    public int $participante_id;
    
    // Chess Rapid
    public ?int $chess_rapid_last_rating;
    public ?int $chess_rapid_last_date;
    public ?int $chess_rapid_last_rd;
    public ?int $chess_rapid_best_rating;
    public ?int $chess_rapid_best_date;
    public ?string $chess_rapid_best_game;
    public ?int $chess_rapid_record_win;
    public ?int $chess_rapid_record_loss;
    public ?int $chess_rapid_record_draw;
    
    // Chess Bullet
    public ?int $chess_bullet_last_rating;
    public ?int $chess_bullet_last_date;
    public ?int $chess_bullet_last_rd;
    public ?int $chess_bullet_best_rating;
    public ?int $chess_bullet_best_date;
    public ?string $chess_bullet_best_game;
    public ?int $chess_bullet_record_win;
    public ?int $chess_bullet_record_loss;
    public ?int $chess_bullet_record_draw;
    
    // Chess Blitz
    public ?int $chess_blitz_last_rating;
    public ?int $chess_blitz_last_date;
    public ?int $chess_blitz_last_rd;
    public ?int $chess_blitz_best_rating;
    public ?int $chess_blitz_best_date;
    public ?string $chess_blitz_best_game;
    public ?int $chess_blitz_record_win;
    public ?int $chess_blitz_record_loss;
    public ?int $chess_blitz_record_draw;
    
    // Tactics
    public ?int $tactics_highest_rating;
    public ?int $tactics_highest_date;
    public ?int $tactics_lowest_rating;
    public ?int $tactics_lowest_date;
    
    // Puzzle Rush
    public ?int $puzzle_rush_best_total_attempts;
    public ?int $puzzle_rush_best_score;
    
    public string $fecha_alta;
    public ?string $fecha_modificacion;

    public function __construct(
        int $stats_id,
        int $participante_id,
        ?int $chess_rapid_last_rating = null,
        ?int $chess_rapid_last_date = null,
        ?int $chess_rapid_last_rd = null,
        ?int $chess_rapid_best_rating = null,
        ?int $chess_rapid_best_date = null,
        ?string $chess_rapid_best_game = null,
        ?int $chess_rapid_record_win = null,
        ?int $chess_rapid_record_loss = null,
        ?int $chess_rapid_record_draw = null,
        ?int $chess_bullet_last_rating = null,
        ?int $chess_bullet_last_date = null,
        ?int $chess_bullet_last_rd = null,
        ?int $chess_bullet_best_rating = null,
        ?int $chess_bullet_best_date = null,
        ?string $chess_bullet_best_game = null,
        ?int $chess_bullet_record_win = null,
        ?int $chess_bullet_record_loss = null,
        ?int $chess_bullet_record_draw = null,
        ?int $chess_blitz_last_rating = null,
        ?int $chess_blitz_last_date = null,
        ?int $chess_blitz_last_rd = null,
        ?int $chess_blitz_best_rating = null,
        ?int $chess_blitz_best_date = null,
        ?string $chess_blitz_best_game = null,
        ?int $chess_blitz_record_win = null,
        ?int $chess_blitz_record_loss = null,
        ?int $chess_blitz_record_draw = null,
        ?int $tactics_highest_rating = null,
        ?int $tactics_highest_date = null,
        ?int $tactics_lowest_rating = null,
        ?int $tactics_lowest_date = null,
        ?int $puzzle_rush_best_total_attempts = null,
        ?int $puzzle_rush_best_score = null,
        ?string $fecha_alta = null,
        ?string $fecha_modificacion = null
    ) {
        $this->stats_id = $stats_id;
        $this->participante_id = $participante_id;
        
        // Chess Rapid
        $this->chess_rapid_last_rating = $chess_rapid_last_rating;
        $this->chess_rapid_last_date = $chess_rapid_last_date;
        $this->chess_rapid_last_rd = $chess_rapid_last_rd;
        $this->chess_rapid_best_rating = $chess_rapid_best_rating;
        $this->chess_rapid_best_date = $chess_rapid_best_date;
        $this->chess_rapid_best_game = $chess_rapid_best_game;
        $this->chess_rapid_record_win = $chess_rapid_record_win;
        $this->chess_rapid_record_loss = $chess_rapid_record_loss;
        $this->chess_rapid_record_draw = $chess_rapid_record_draw;
        
        // Chess Bullet
        $this->chess_bullet_last_rating = $chess_bullet_last_rating;
        $this->chess_bullet_last_date = $chess_bullet_last_date;
        $this->chess_bullet_last_rd = $chess_bullet_last_rd;
        $this->chess_bullet_best_rating = $chess_bullet_best_rating;
        $this->chess_bullet_best_date = $chess_bullet_best_date;
        $this->chess_bullet_best_game = $chess_bullet_best_game;
        $this->chess_bullet_record_win = $chess_bullet_record_win;
        $this->chess_bullet_record_loss = $chess_bullet_record_loss;
        $this->chess_bullet_record_draw = $chess_bullet_record_draw;
        
        // Chess Blitz
        $this->chess_blitz_last_rating = $chess_blitz_last_rating;
        $this->chess_blitz_last_date = $chess_blitz_last_date;
        $this->chess_blitz_last_rd = $chess_blitz_last_rd;
        $this->chess_blitz_best_rating = $chess_blitz_best_rating;
        $this->chess_blitz_best_date = $chess_blitz_best_date;
        $this->chess_blitz_best_game = $chess_blitz_best_game;
        $this->chess_blitz_record_win = $chess_blitz_record_win;
        $this->chess_blitz_record_loss = $chess_blitz_record_loss;
        $this->chess_blitz_record_draw = $chess_blitz_record_draw;
        
        // Tactics
        $this->tactics_highest_rating = $tactics_highest_rating;
        $this->tactics_highest_date = $tactics_highest_date;
        $this->tactics_lowest_rating = $tactics_lowest_rating;
        $this->tactics_lowest_date = $tactics_lowest_date;
        
        // Puzzle Rush
        $this->puzzle_rush_best_total_attempts = $puzzle_rush_best_total_attempts;
        $this->puzzle_rush_best_score = $puzzle_rush_best_score;
        
        $this->fecha_alta = $fecha_alta ?? date('Y-m-d H:i:s');
        $this->fecha_modificacion = $fecha_modificacion;
    }

    // Método para crear un objeto desde el JSON de la API
    public static function fromApiJson(array $json, int $participante_id): ChesscomPlayerStatsApiModel
    {
        return new ChesscomPlayerStatsApiModel(
            0, // stats_id será asignado por la base de datos
            $participante_id,
            
            // Chess Rapid
            $json['chess_rapid']['last']['rating'] ?? null,
            $json['chess_rapid']['last']['date'] ?? null,
            $json['chess_rapid']['last']['rd'] ?? null,
            $json['chess_rapid']['best']['rating'] ?? null,
            $json['chess_rapid']['best']['date'] ?? null,
            $json['chess_rapid']['best']['game'] ?? null,
            $json['chess_rapid']['record']['win'] ?? null,
            $json['chess_rapid']['record']['loss'] ?? null,
            $json['chess_rapid']['record']['draw'] ?? null,
            
            // Chess Bullet
            $json['chess_bullet']['last']['rating'] ?? null,
            $json['chess_bullet']['last']['date'] ?? null,
            $json['chess_bullet']['last']['rd'] ?? null,
            $json['chess_bullet']['best']['rating'] ?? null,
            $json['chess_bullet']['best']['date'] ?? null,
            $json['chess_bullet']['best']['game'] ?? null,
            $json['chess_bullet']['record']['win'] ?? null,
            $json['chess_bullet']['record']['loss'] ?? null,
            $json['chess_bullet']['record']['draw'] ?? null,
            
            // Chess Blitz
            $json['chess_blitz']['last']['rating'] ?? null,
            $json['chess_blitz']['last']['date'] ?? null,
            $json['chess_blitz']['last']['rd'] ?? null,
            $json['chess_blitz']['best']['rating'] ?? null,
            $json['chess_blitz']['best']['date'] ?? null,
            $json['chess_blitz']['best']['game'] ?? null,
            $json['chess_blitz']['record']['win'] ?? null,
            $json['chess_blitz']['record']['loss'] ?? null,
            $json['chess_blitz']['record']['draw'] ?? null,
            
            // Tactics
            $json['tactics']['highest']['rating'] ?? null,
            $json['tactics']['highest']['date'] ?? null,
            $json['tactics']['lowest']['rating'] ?? null,
            $json['tactics']['lowest']['date'] ?? null,
            
            // Puzzle Rush
            $json['puzzle_rush']['best']['total_attempts'] ?? null,
            $json['puzzle_rush']['best']['score'] ?? null
        );
    }
}