<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\modelo\ChesscomProfile.php

class ChesscomProfileApiModel
{
    public int $player_id;
    public ?string $chesscom_id;
    public int $participante_id;
    public ?string $url;
    public string $username;
    public ?int $followers;
    public ?string $country;
    public ?int $last_online;
    public ?int $joined;
    public ?string $status;
    public ?int $is_streamer;
    public ?int $verified;
    public ?string $league;
    public string $fecha_alta;
    public ?string $fecha_modificacion;

    public function __construct(
        int $player_id,
        ?string $chesscom_id,
        int $participante_id,
        ?string $url,
        string $username,
        ?int $followers = null,
        ?string $country = null,
        ?int $last_online = null,
        ?int $joined = null,
        ?string $status = null,
        ?int $is_streamer = null,
        ?int $verified = null,
        ?string $league = null,
        ?string $fecha_alta = null,
        ?string $fecha_modificacion = null
    ) {
        $this->player_id = $player_id;
        $this->chesscom_id = $chesscom_id;
        $this->participante_id = $participante_id;
        $this->url = $url;
        $this->username = $username;
        $this->followers = $followers;
        $this->country = $country;
        $this->last_online = $last_online;
        $this->joined = $joined;
        $this->status = $status;
        $this->is_streamer = $is_streamer;
        $this->verified = $verified;
        $this->league = $league;
        $this->fecha_alta = $fecha_alta ?? date('Y-m-d H:i:s');
        $this->fecha_modificacion = $fecha_modificacion;
    }

    // Método para crear un objeto desde el JSON de la API
    public static function fromApiJson(array $json, int $participante_id): ChesscomProfileApiModel
    {
        return new ChesscomProfileApiModel(
            $json['player_id'],
            $json['@id'] ?? null,
            $participante_id,
            $json['url'] ?? null,
            $json['username'],
            $json['followers'] ?? null,
            $json['country'] ?? null,
            $json['last_online'] ?? null,
            $json['joined'] ?? null,
            $json['status'] ?? null,
            isset($json['is_streamer']) ? (int)$json['is_streamer'] : null,
            isset($json['verified']) ? (int)$json['verified'] : null,
            $json['league'] ?? null
        );
    }
}