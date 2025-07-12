<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\ChesscomLiveGames.php

class ChesscomPlayerGames
{
    /** @var ChesscomLiveGame[] */
    public array $games;

    public function __construct(array $games)
    {
        $this->games = $games;
    }

    public static function fromArray(array $data): ChesscomPlayerGames
    {
        $games = [];
        if (isset($data['games']) && is_array($data['games'])) {
            foreach ($data['games'] as $gameData) {
                $games[] = ChesscomLiveGame::fromArray($gameData);
            }
        }
        return new ChesscomPlayerGames($games);
    }
}

class ChesscomLiveGame
{
    public string $url;
    public string $pgn;
    public string $time_control;
    public int $end_time;
    public bool $rated;
    public ?array $accuracies;
    public string $tcn;
    public string $uuid;
    public string $initial_setup;
    public string $fen;
    public string $time_class;
    public string $rules;
    public ChesscomPlayer $white;
    public ChesscomPlayer $black;
    public string $eco;

    public function __construct(
        string $url,
        string $pgn,
        string $time_control,
        int $end_time,
        bool $rated,
        ?array $accuracies,
        string $tcn,
        string $uuid,
        string $initial_setup,
        string $fen,
        string $time_class,
        string $rules,
        ChesscomPlayer $white,
        ChesscomPlayer $black,
        string $eco
    ) {
        $this->url = $url;
        $this->pgn = $pgn;
        $this->time_control = $time_control;
        $this->end_time = $end_time;
        $this->rated = $rated;
        $this->accuracies = $accuracies;
        $this->tcn = $tcn;
        $this->uuid = $uuid;
        $this->initial_setup = $initial_setup;
        $this->fen = $fen;
        $this->time_class = $time_class;
        $this->rules = $rules;
        $this->white = $white;
        $this->black = $black;
        $this->eco = $eco;
    }

    public static function fromArray(array $data): ChesscomLiveGame
    {
        return new ChesscomLiveGame(
            $data['url'],
            $data['pgn'],
            $data['time_control'],
            $data['end_time'],
            $data['rated'],
            isset($data['accuracies']) ? $data['accuracies'] : null,
            $data['tcn'],
            $data['uuid'],
            $data['initial_setup'],
            $data['fen'],
            $data['time_class'],
            $data['rules'],
            ChesscomPlayer::fromArray($data['white']),
            ChesscomPlayer::fromArray($data['black']),
            $data['eco']
        );
    }
}

class ChesscomPlayer
{
    public int $rating;
    public string $result;
    public string $id;
    public string $username;
    public string $uuid;

    public function __construct(
        int $rating,
        string $result,
        string $id,
        string $username,
        string $uuid
    ) {
        $this->rating = $rating;
        $this->result = $result;
        $this->id = $id;
        $this->username = $username;
        $this->uuid = $uuid;
    }

    public static function fromArray(array $data): ChesscomPlayer
    {
        return new ChesscomPlayer(
            $data['rating'],
            $data['result'],
            $data['@id'],
            $data['username'],
            $data['uuid']
        );
    }
    /**
     * Convierte el objeto a un array asociativo.
     */
    public function toArray(): array
    {
        return [
            'rating' => $this->rating,
            'result' => $this->result,
            '@id' => $this->id,
            'username' => $this->username,
            'uuid' => $this->uuid
        ];
    }
    /**
     * Convierte el objeto a una cadena JSON.
     */
    public function toJson(): string
    {
        return json_encode($this->toArray());
    }
}