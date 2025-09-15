<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\modelos\ChesscomProfile.php

class ChesscomProfile
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
}