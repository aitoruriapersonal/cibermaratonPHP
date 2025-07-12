<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\Player.php

class Player
{
    public int $player_id;
    public string $id;
    public string $url;
    public string $username;
    public int $followers;
    public string $country;
    public int $last_online;
    public int $joined;
    public string $status;
    public bool $is_streamer;
    public bool $verified;
    public string $league;
    public array $streaming_platforms;

    public function __construct(
        int $player_id,
        string $id,
        string $url,
        string $username,
        int $followers,
        string $country,
        int $last_online,
        int $joined,
        string $status,
        bool $is_streamer,
        bool $verified,
        string $league,
        array $streaming_platforms
    ) {
        $this->player_id = $player_id;
        $this->id = $id;
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
        $this->streaming_platforms = $streaming_platforms;
    }

    /**
     * Crea una instancia de Player desde un array asociativo (por ejemplo, resultado de json_decode).
     */
    public static function fromArray(array $data): Player
    {
        return new Player(
            $data['player_id'],
            $data['@id'],
            $data['url'],
            $data['username'],
            $data['followers'],
            $data['country'],
            $data['last_online'],
            $data['joined'],
            $data['status'],
            $data['is_streamer'],
            $data['verified'],
            $data['league'],
            $data['streaming_platforms']
        );
    }
}