<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\ChesscomGamesList.php

class ChesscomPlayerMonthGames
{
    /** @var ChesscomGame[] */
    public array $games;

    public function __construct(array $games)
    {
        $this->games = $games;
    }

    public static function fromArray(array $data): ChesscomPlayerMonthGames
    {
        $games = [];
        if (isset($data['games']) && is_array($data['games'])) {
            foreach ($data['games'] as $gameData) {
                $games[] = ChesscomGame::fromArray($gameData);
            }
        }
        return new ChesscomPlayerMonthGames($games);
    }
}

class ChesscomGame
{
    public string $url;
    public int $move_by;
    public string $pgn;
    public string $time_control;
    public int $last_activity;
    public bool $rated;
    public string $turn;
    public string $fen;
    public int $start_time;
    public string $time_class;
    public string $rules;
    public string $white;
    public string $black;

    public function __construct(
        string $url,
        int $move_by,
        string $pgn,
        string $time_control,
        int $last_activity,
        bool $rated,
        string $turn,
        string $fen,
        int $start_time,
        string $time_class,
        string $rules,
        string $white,
        string $black
    ) {
        $this->url = $url;
        $this->move_by = $move_by;
        $this->pgn = $pgn;
        $this->time_control = $time_control;
        $this->last_activity = $last_activity;
        $this->rated = $rated;
        $this->turn = $turn;
        $this->fen = $fen;
        $this->start_time = $start_time;
        $this->time_class = $time_class;
        $this->rules = $rules;
        $this->white = $white;
        $this->black = $black;
    }

    public static function fromArray(array $data): ChesscomGame
    {
        return new ChesscomGame(
            $data['url'],
            $data['move_by'],
            $data['pgn'],
            $data['time_control'],
            $data['last_activity'],
            $data['rated'],
            $data['turn'],
            $data['fen'],
            $data['start_time'],
            $data['time_class'],
            $data['rules'],
            $data['white'],
            $data['black']
        );
    }
}