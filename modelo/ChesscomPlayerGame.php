<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\modelos\ChesscomPlayerGame.php

class ChesscomPlayerGame
{
    private const DATE_FORMAT = 'Y-m-d H:i:s';
    
    // Datos generales
    public int $gameId;
    public int $participanteId;
    public ?string $username;
    public ?string $chesscomGameUrl;
    public ?string $pgn;
    public string $fechaAlta;
    public ?string $fechaModificacion;
    
    // Datos de las blancas
    public ?string $whiteUsername;
    public ?int $whiteRating;
    public ?string $whiteResult;
    public ?float $accuracyWhite;
    public ?string $whiteUUID;
    
    // Datos de las negras
    public ?string $blackUsername;
    public ?int $blackRating;
    public ?string $blackResult;
    public ?float $accuracyBlack;
    public ?string $blackUUID;
    
    // Datos de la partida
    public ?string $timeControl;
    public ?string $timeClass;
    public ?string $rules;
    public ?int $rated;
    public ?int $endTime;
    public ?int $startTime;
    public ?string $eco;
    public ?string $tournament;
    public ?string $match;

    // Variable para controlar si es la creación inicial
    private bool $esCreacionInicial = true;

    /**
     * Constructor sin parámetros
     */
    public function __construct()
    {
        // Datos generales - valores por defecto
        $this->gameId = 0;
        $this->participanteId = 0;
        $this->username = null; // AÑADIR ESTA LÍNEA
        $this->chesscomGameUrl = null;
        $this->pgn = null;
        $this->fechaAlta = date(self::DATE_FORMAT); // sysdate en la creación
        $this->fechaModificacion = null; // null en la creación
        
        // Datos de las blancas
        $this->whiteUsername = null;
        $this->whiteRating = null;
        $this->whiteResult = null;
        $this->accuracyWhite = null;
        $this->whiteUUID = null;
        
        // Datos de las negras
        $this->blackUsername = null;
        $this->blackRating = null;
        $this->blackResult = null;
        $this->accuracyBlack = null;
        $this->blackUUID = null;
        
        // Datos de la partida
        $this->timeControl = null;
        $this->timeClass = null;
        $this->rules = null;
        $this->rated = null;
        $this->endTime = null;
        $this->startTime = null;
        $this->eco = null;
        $this->tournament = null;
        $this->match = null;
    }
    
    /**
     * Marcar que la creación inicial ha terminado
     */
    private function finalizarCreacionInicial(): void
    {
        $this->esCreacionInicial = false;
    }
    
    /**
     * Establecer datos generales de la partida
     */
    public function setDatosGenerales(
        int $gameId,
        int $participanteId,
        ?string $username = null, // AÑADIR ESTE PARÁMETRO
        ?string $chesscomGameUrl = null,
        ?string $pgn = null
    ): self {
        $this->gameId = $gameId;
        $this->participanteId = $participanteId;
        $this->username = $username; // AÑADIR ESTA LÍNEA
        $this->chesscomGameUrl = $chesscomGameUrl;
        $this->pgn = $pgn;
        $this->actualizarFechaModificacion();
        return $this;
    }
    
    /**
     * Establecer datos del jugador con piezas blancas
     */
    public function setDatosBlancas(
        ?string $username = null,
        ?int $rating = null,
        ?string $result = null,
        ?float $accuracy = null,
        ?string $uuid = null
    ): self {
        $this->whiteUsername = $username;
        $this->whiteRating = $rating;
        $this->whiteResult = $result;
        $this->accuracyWhite = $accuracy;
        $this->whiteUUID = $uuid;
        $this->actualizarFechaModificacion();
        return $this;
    }
    
    /**
     * Establecer datos del jugador con piezas negras
     */
    public function setDatosNegras(
        ?string $username = null,
        ?int $rating = null,
        ?string $result = null,
        ?float $accuracy = null,
        ?string $uuid = null
    ): self {
        $this->blackUsername = $username;
        $this->blackRating = $rating;
        $this->blackResult = $result;
        $this->accuracyBlack = $accuracy;
        $this->blackUUID = $uuid;
        $this->actualizarFechaModificacion();
        return $this;
    }
    
    /**
     * Establecer datos de la partida (tiempo, reglas, etc.)
     */
    public function setDatosPartida(
        ?string $timeControl = null,
        ?string $timeClass = null,
        ?string $rules = null,
        ?int $rated = null,
        ?int $endTime = null,
        ?int $startTime = null,
        ?string $eco = null,
        ?string $tournament = null,
        ?string $match = null
    ): self {
        $this->timeControl = $timeControl;
        $this->timeClass = $timeClass;
        $this->rules = $rules;
        $this->rated = $rated;
        $this->endTime = $endTime;
        $this->startTime = $startTime;
        $this->eco = $eco;
        $this->tournament = $tournament;
        $this->match = $match;
        $this->actualizarFechaModificacion();
        return $this;
    }
    
    /**
     * Método alternativo con array para datos de partida
     */
    public function setDatosPartidaArray(array $datosPartida = []): self 
    {
        return $this->setDatosPartida(
            $datosPartida['timeControl'] ?? null,
            $datosPartida['timeClass'] ?? null,
            $datosPartida['rules'] ?? null,
            $datosPartida['rated'] ?? null,
            $datosPartida['endTime'] ?? null,
            $datosPartida['startTime'] ?? null,
            $datosPartida['eco'] ?? null,
            $datosPartida['tournament'] ?? null,
            $datosPartida['match'] ?? null
        );
    }
    
    /**
     * Finalizar la configuración inicial del objeto
     */
    public function finalizarConfiguracion(): self
    {
        $this->finalizarCreacionInicial();
        return $this;
    }
    
    /**
     * Métodos de utilidad para campos individuales
     */
    public function setGameId(int $gameId): self
    {
        $this->gameId = $gameId;
        $this->actualizarFechaModificacion();
        return $this;
    }
    
    public function setParticipanteId(int $participanteId): self
    {
        $this->participanteId = $participanteId;
        $this->actualizarFechaModificacion();
        return $this;
    }
    
    public function setChesscomGameUrl(?string $url): self
    {
        $this->chesscomGameUrl = $url;
        $this->actualizarFechaModificacion();
        return $this;
    }
    
    public function setPgn(?string $pgn): self
    {
        $this->pgn = $pgn;
        $this->actualizarFechaModificacion();
        return $this;
    }
    
    public function setUsername(?string $username): self
    {
        $this->username = $username;
        $this->actualizarFechaModificacion();
        return $this;
    }
    
    /**
     * Actualizar fecha de modificación
     * - En la creación inicial: fechaModificacion = null
     * - En modificaciones posteriores: fechaModificacion = sysdate
     */
    private function actualizarFechaModificacion(): void
    {
        // Solo actualizar fechaModificacion si NO es la creación inicial
        if (!$this->esCreacionInicial) {
            $this->fechaModificacion = date(self::DATE_FORMAT);
        }
        // Durante la creación inicial, fechaModificacion permanece null
    }
    
    /**
     * Validar que todos los campos obligatorios están completos
     */
    public function validar(): array
    {
        $errores = [];
        
        if ($this->participanteId <= 0) {
            $errores[] = 'participanteId debe ser mayor que 0';
        }
        
        if (empty($this->chesscomGameUrl)) {
            $errores[] = 'chesscomGameUrl es obligatorio';
        }
        
        if (empty($this->whiteUsername) && empty($this->blackUsername)) {
            $errores[] = 'Debe haber al menos un jugador (blancas o negras)';
        }
        
        return $errores;
    }
    
    /**
     * Verificar si el objeto está completo
     */
    public function estaCompleto(): bool
    {
        return empty($this->validar());
    }
    
    /**
     * Método toString para debug
     */
    public function __toString(): string
    {
        return sprintf(
            "ChesscomPlayerGame[id=%d, participante=%d, %s vs %s, url=%s, fechaAlta=%s, fechaMod=%s]",
            $this->gameId,
            $this->participanteId,
            $this->whiteUsername ?? 'Unknown',
            $this->blackUsername ?? 'Unknown',
            $this->chesscomGameUrl ?? 'No URL',
            $this->fechaAlta,
            $this->fechaModificacion ?? 'NULL'
        );
    }
    
    /**
     * Método estático para crear desde array
     */
    public static function fromArray(array $data): self
    {
        $game = new self();
        
        // Datos generales
        if (isset($data['gameId'], $data['participanteId'])) {
            $game->setDatosGenerales(
                $data['gameId'],
                $data['participanteId'],
                $data['username'] ?? null, // AÑADIR ESTA LÍNEA
                $data['chesscomGameUrl'] ?? null,
                $data['pgn'] ?? null
            );
        }
        
        // Datos blancas
        if (isset($data['whiteUsername']) || isset($data['whiteRating'])) {
            $game->setDatosBlancas(
                $data['whiteUsername'] ?? null,
                $data['whiteRating'] ?? null,
                $data['whiteResult'] ?? null,
                $data['accuracyWhite'] ?? null,
                $data['whiteUUID'] ?? null
            );
        }
        
        // Datos negras
        if (isset($data['blackUsername']) || isset($data['blackRating'])) {
            $game->setDatosNegras(
                $data['blackUsername'] ?? null,
                $data['blackRating'] ?? null,
                $data['blackResult'] ?? null,
                $data['accuracyBlack'] ?? null,
                $data['blackUUID'] ?? null
            );
        }
        
        // Datos partida
        if (isset($data['timeControl']) || isset($data['timeClass'])) {
            $game->setDatosPartida(
                $data['timeControl'] ?? null,
                $data['timeClass'] ?? null,
                $data['rules'] ?? null,
                $data['rated'] ?? null,
                $data['endTime'] ?? null,
                $data['startTime'] ?? null,
                $data['eco'] ?? null,
                $data['tournament'] ?? null,
                $data['match'] ?? null
            );
        }
        
        // Finalizar la configuración inicial
        $game->finalizarConfiguracion();
        
        return $game;
    }
}
