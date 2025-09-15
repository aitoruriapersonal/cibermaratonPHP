<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\modelos\ChesscomPlayerGame.php

class ChesscomPlayerGame
{
    public ?int $id;
    public int $participante_id;
    public string $username;
    public string $url;
    public ?string $pgn;
    public ?string $time_control;
    public ?int $end_time;
    public ?int $rated;
    public ?float $accuracy_white;
    public ?float $accuracy_black;
    public ?string $fen;
    public ?string $time_class;
    public ?string $rules;
    public ?int $white_rating;
    public ?string $white_result;
    public ?string $white_id;
    public ?string $white_username;
    public ?string $white_uuid;
    public ?int $black_rating;
    public ?string $black_result;
    public ?string $black_id;
    public ?string $black_username;
    public ?string $black_uuid;
    public ?string $eco;
    public string $fecha_alta;
    public ?string $fecha_modificacaion;

    public function __construct(
        ?int $id,
        int $participante_id,
        string $username,
        string $url,
        ?string $pgn = null,
        ?string $time_control = null,
        ?int $end_time = null,
        ?int $rated = null,
        ?float $accuracy_white = null,
        ?float $accuracy_black = null,
        ?string $fen = null,
        ?string $time_class = null,
        ?string $rules = null,
        ?int $white_rating = null,
        ?string $white_result = null,
        ?string $white_id = null,
        ?string $white_username = null,
        ?string $white_uuid = null,
        ?int $black_rating = null,
        ?string $black_result = null,
        ?string $black_id = null,
        ?string $black_username = null,
        ?string $black_uuid = null,
        ?string $eco = null,
        ?string $fecha_alta = null,
        ?string $fecha_modificacaion = null
    ) {
        $this->id = $id;
        $this->participante_id = $participante_id;
        $this->username = $username;
        $this->url = $url;
        $this->pgn = $pgn;
        $this->time_control = $time_control;
        $this->end_time = $end_time;
        $this->rated = $rated;
        $this->accuracy_white = $accuracy_white;
        $this->accuracy_black = $accuracy_black;
        $this->fen = $fen;
        $this->time_class = $time_class;
        $this->rules = $rules;
        $this->white_rating = $white_rating;
        $this->white_result = $white_result;
        $this->white_id = $white_id;
        $this->white_username = $white_username;
        $this->white_uuid = $white_uuid;
        $this->black_rating = $black_rating;
        $this->black_result = $black_result;
        $this->black_id = $black_id;
        $this->black_username = $black_username;
        $this->black_uuid = $black_uuid;
        $this->eco = $eco;
        $this->fecha_alta = $fecha_alta ?? date('Y-m-d H:i:s');
        $this->fecha_modificacaion = $fecha_modificacaion;
    }
}