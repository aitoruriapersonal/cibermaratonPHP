<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\ChesscomStats.php

class ChesscomStats
{
    public ?ChessGameStats $chess_rapid;
    public ?ChessGameStats $chess_bullet;
    public ?ChessGameStats $chess_blitz;
    public ?TacticsStats $tactics;
    public ?PuzzleRushStats $puzzle_rush;

    public function __construct(
        ?ChessGameStats $chess_rapid,
        ?ChessGameStats $chess_bullet,
        ?ChessGameStats $chess_blitz,
        ?TacticsStats $tactics,
        ?PuzzleRushStats $puzzle_rush
    ) {
        $this->chess_rapid = $chess_rapid;
        $this->chess_bullet = $chess_bullet;
        $this->chess_blitz = $chess_blitz;
        $this->tactics = $tactics;
        $this->puzzle_rush = $puzzle_rush;
    }

    public static function fromArray(array $data): ChesscomStats
    {
        return new ChesscomStats(
            isset($data['chess_rapid']) ? ChessGameStats::fromArray($data['chess_rapid']) : null,
            isset($data['chess_bullet']) ? ChessGameStats::fromArray($data['chess_bullet']) : null,
            isset($data['chess_blitz']) ? ChessGameStats::fromArray($data['chess_blitz']) : null,
            isset($data['tactics']) ? TacticsStats::fromArray($data['tactics']) : null,
            isset($data['puzzle_rush']) ? PuzzleRushStats::fromArray($data['puzzle_rush']) : null
        );
    }
}

class ChessGameStats
{
    public ?ChessRating $last;
    public ?ChessBest $best;
    public ?ChessRecord $record;

    public function __construct(
        ?ChessRating $last,
        ?ChessBest $best,
        ?ChessRecord $record
    ) {
        $this->last = $last;
        $this->best = $best;
        $this->record = $record;
    }

    public static function fromArray(array $data): ChessGameStats
    {
        return new ChessGameStats(
            isset($data['last']) ? ChessRating::fromArray($data['last']) : null,
            isset($data['best']) ? ChessBest::fromArray($data['best']) : null,
            isset($data['record']) ? ChessRecord::fromArray($data['record']) : null
        );
    }
}

class ChessRating
{
    public int $rating;
    public int $date;
    public ?int $rd;

    public function __construct(int $rating, int $date, ?int $rd = null)
    {
        $this->rating = $rating;
        $this->date = $date;
        $this->rd = $rd;
    }

    public static function fromArray(array $data): ChessRating
    {
        return new ChessRating(
            $data['rating'],
            $data['date'],
            $data['rd'] ?? null
        );
    }
}

class ChessBest
{
    public int $rating;
    public int $date;
    public ?string $game;

    public function __construct(int $rating, int $date, ?string $game = null)
    {
        $this->rating = $rating;
        $this->date = $date;
        $this->game = $game;
    }

    public static function fromArray(array $data): ChessBest
    {
        return new ChessBest(
            $data['rating'],
            $data['date'],
            $data['game'] ?? null
        );
    }
}

class ChessRecord
{
    public int $win;
    public int $loss;
    public int $draw;

    public function __construct(int $win, int $loss, int $draw)
    {
        $this->win = $win;
        $this->loss = $loss;
        $this->draw = $draw;
    }

    public static function fromArray(array $data): ChessRecord
    {
        return new ChessRecord(
            $data['win'],
            $data['loss'],
            $data['draw']
        );
    }
}

class TacticsStats
{
    public ?TacticsRating $highest;
    public ?TacticsRating $lowest;

    public function __construct(?TacticsRating $highest, ?TacticsRating $lowest)
    {
        $this->highest = $highest;
        $this->lowest = $lowest;
    }

    public static function fromArray(array $data): TacticsStats
    {
        return new TacticsStats(
            isset($data['highest']) ? TacticsRating::fromArray($data['highest']) : null,
            isset($data['lowest']) ? TacticsRating::fromArray($data['lowest']) : null
        );
    }
}

class TacticsRating
{
    public int $rating;
    public int $date;

    public function __construct(int $rating, int $date)
    {
        $this->rating = $rating;
        $this->date = $date;
    }

    public static function fromArray(array $data): TacticsRating
    {
        return new TacticsRating(
            $data['rating'],
            $data['date']
        );
    }
}

class PuzzleRushStats
{
    public ?PuzzleRushBest $best;

    public function __construct(?PuzzleRushBest $best)
    {
        $this->best = $best;
    }

    public static function fromArray(array $data): PuzzleRushStats
    {
        return new PuzzleRushStats(
            isset($data['best']) ? PuzzleRushBest::fromArray($data['best']) : null
        );
    }
}

class PuzzleRushBest
{
    public int $total_attempts;
    public int $score;

    public function __construct(int $total_attempts, int $score)
    {
        $this->total_attempts = $total_attempts;
        $this->score = $score;
    }

    public static function fromArray(array $data): PuzzleRushBest
    {
        return new PuzzleRushBest(
            $data['total_attempts'],
            $data['score']
        );
    }
}