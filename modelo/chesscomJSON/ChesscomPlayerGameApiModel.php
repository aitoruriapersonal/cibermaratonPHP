<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\modelo\chesscomJSON\ChesscomPlayerGameApiModel.php

class ChesscomPlayerGameApiModel
{
    public ?string $url = null;
    public ?string $pgn = null;
    public ?string $time_control = null;
    public ?int $end_time = null;
    public ?bool $rated = null;
    public ?array $accuracies = null;
    public ?string $fen = null;
    public ?string $time_class = null;
    public ?string $rules = null;
    public ?array $white = null;
    public ?array $black = null;
    public ?string $eco = null;

    public static function fromJson(array $json): ChesscomPlayerGameApiModel
    {
        $model = new self();
        $model->url = $json['url'] ?? null;
        $model->pgn = $json['pgn'] ?? null;
        $model->time_control = $json['time_control'] ?? null;
        $model->end_time = $json['end_time'] ?? null;
        $model->rated = $json['rated'] ?? null;
        $model->accuracies = $json['accuracies'] ?? null;
        $model->fen = $json['fen'] ?? null;
        $model->time_class = $json['time_class'] ?? null;
        $model->rules = $json['rules'] ?? null;
        $model->white = $json['white'] ?? null;
        $model->black = $json['black'] ?? null;
        $model->eco = $json['eco'] ?? null;
        return $model;
    }
}