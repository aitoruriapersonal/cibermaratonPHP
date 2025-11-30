<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\modelo\chesscomJSON\ChesscomPlayerGameApiModel.php

class ChesscomPlayerGameApiModel
{
    public int $game_id;
    public int $participante_id;
    public ?string $url; // Esta es la propiedad principal que usa el bot
    public ?string $chesscom_game_url;
    public ?string $pgn;
    public ?string $time_control;
    public ?string $time_class;
    public ?string $rules;
    public ?int $rated;
    public ?string $white_username;
    public ?int $white_rating;
    public ?string $white_result;
    public ?string $black_username;
    public ?int $black_rating;
    public ?string $black_result;
    public ?int $end_time;
    public ?string $eco;
    
    // NUEVOS CAMPOS FALTANTES
    public ?float $accuracy_white;
    public ?float $accuracy_black;
    public ?string $white_uuid;
    public ?string $black_uuid;
    public ?int $start_time;
    public ?string $tournament;
    public ?string $match;
    
    public string $fecha_alta;
    public ?string $fecha_modificacion;
    public array $games;

    public function __construct(
        int $game_id,
        int $participante_id,
        ?string $url = null,
        ?string $chesscom_game_url = null,
        ?string $pgn = null,
        ?string $time_control = null,
        ?string $time_class = null,
        ?string $rules = null,
        ?int $rated = null,
        ?string $white_username = null,
        ?int $white_rating = null,
        ?string $white_result = null,
        ?string $black_username = null,
        ?int $black_rating = null,
        ?string $black_result = null,
        ?int $end_time = null,
        ?string $eco = null,
        ?float $accuracy_white = null,
        ?float $accuracy_black = null,
        ?string $white_uuid = null,
        ?string $black_uuid = null,
        ?int $start_time = null,
        ?string $tournament = null,
        ?string $match = null,
        array $games = [],
        ?string $fecha_alta = null,
        ?string $fecha_modificacion = null
    ) {
        $this->game_id = $game_id;
        $this->participante_id = $participante_id;
        $this->url = $url; // Campo principal que usa el bot
        $this->chesscom_game_url = $chesscom_game_url ?? $url;
        $this->pgn = $pgn;
        $this->time_control = $time_control;
        $this->time_class = $time_class;
        $this->rules = $rules;
        $this->rated = $rated;
        $this->white_username = $white_username;
        $this->white_rating = $white_rating;
        $this->white_result = $white_result;
        $this->black_username = $black_username;
        $this->black_rating = $black_rating;
        $this->black_result = $black_result;
        $this->end_time = $end_time;
        $this->eco = $eco;
        $this->accuracy_white = $accuracy_white;
        $this->accuracy_black = $accuracy_black;
        $this->white_uuid = $white_uuid;
        $this->black_uuid = $black_uuid;
        $this->start_time = $start_time;
        $this->tournament = $tournament;
        $this->match = $match;
        $this->games = $games;
        $this->fecha_alta = $fecha_alta ?? date('Y-m-d H:i:s');
        $this->fecha_modificacion = $fecha_modificacion;
    }

    // Método para crear un objeto desde el JSON de la API
    public static function fromApiJson(array $json, int $participante_id): ChesscomPlayerGameApiModel
    {
        $games = $json['games'] ?? [];
        $firstGame = !empty($games) ? $games[0] : [];
        
        return new ChesscomPlayerGameApiModel(
            0,
            $participante_id,
            $firstGame['url'] ?? null, // Mapear directamente games.url a la propiedad url
            $firstGame['url'] ?? null,
            $firstGame['pgn'] ?? null,
            $firstGame['time_control'] ?? null,
            $firstGame['time_class'] ?? null,
            $firstGame['rules'] ?? null,
            isset($firstGame['rated']) ? (int)$firstGame['rated'] : null,
            $firstGame['white']['username'] ?? null,
            $firstGame['white']['rating'] ?? null,
            $firstGame['white']['result'] ?? null,
            $firstGame['black']['username'] ?? null,
            $firstGame['black']['rating'] ?? null,
            $firstGame['black']['result'] ?? null,
            $firstGame['end_time'] ?? null,
            self::extractEcoCode($firstGame['eco'] ?? null),
            // NUEVOS CAMPOS
            isset($firstGame['accuracies']['white']) ? (float)$firstGame['accuracies']['white'] : null,
            isset($firstGame['accuracies']['black']) ? (float)$firstGame['accuracies']['black'] : null,
            $firstGame['white']['uuid'] ?? null,
            $firstGame['black']['uuid'] ?? null,
            $firstGame['start_time'] ?? null,
            $firstGame['tournament'] ?? null,
            $firstGame['match'] ?? null,
            $games
        );
    }

    // Método para procesar múltiples partidas individuales - ESTE ES EL QUE USA EL BOT
    public static function createMultipleFromGames(array $games, int $participante_id): array
    {
        $gameModels = [];
        
        foreach ($games as $game) {
            $gameModel = new ChesscomPlayerGameApiModel(
                0,
                $participante_id,
                $game['url'] ?? null, // CRÍTICO: Mapear games.url a la propiedad url
                $game['url'] ?? null,
                $game['pgn'] ?? null,
                $game['time_control'] ?? null,
                $game['time_class'] ?? null,
                $game['rules'] ?? null,
                isset($game['rated']) ? (int)$game['rated'] : null,
                $game['white']['username'] ?? null,
                $game['white']['rating'] ?? null,
                $game['white']['result'] ?? null,
                $game['black']['username'] ?? null,
                $game['black']['rating'] ?? null,
                $game['black']['result'] ?? null,
                $game['end_time'] ?? null,
                self::extractEcoCode($game['eco'] ?? null),
                // NUEVOS CAMPOS - MAPEO CORRECTO DEL JSON
                isset($game['accuracies']['white']) ? (float)$game['accuracies']['white'] : null,
                isset($game['accuracies']['black']) ? (float)$game['accuracies']['black'] : null,
                $game['white']['uuid'] ?? null,
                $game['black']['uuid'] ?? null,
                $game['start_time'] ?? null,
                $game['tournament'] ?? null,
                $game['match'] ?? null,
                [$game]
            );
            
            $gameModels[] = $gameModel;
        }
        
        return $gameModels;
    }

    private static function extractEcoCode(?string $ecoUrl): ?string
    {
        if (!$ecoUrl) {
            return null;
        }
        
        if (filter_var($ecoUrl, FILTER_VALIDATE_URL)) {
            $path = parse_url($ecoUrl, PHP_URL_PATH);
            $segments = explode('/', trim($path, '/'));
            $lastSegment = end($segments);
            
            if (preg_match('/^[A-E]\d{2}/', $lastSegment, $matches)) {
                return $matches[0];
            }
            
            return $lastSegment;
        }
        
        return $ecoUrl;
    }
}