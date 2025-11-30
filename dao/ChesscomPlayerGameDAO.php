<?php
// filepath: c:\xampp\htdocs\deporteuniversitario\ajedrez\backend\dao\ChesscomPlayerGameDAO.php

require_once __DIR__ . '/../modelo/ChesscomPlayerGame.php';

class ChesscomPlayerGameDAO
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Crear una nueva partida en la base de datos
     */
    public function create(ChesscomPlayerGame $game): int
    {
        try {
            echo '<br/>DEBUG DAO: Iniciando inserción en chesscom_player_games';
            echo '<br/>DEBUG DAO: Datos del objeto:';
            echo '<br/>  - participanteId: ' . $game->participanteId;
            echo '<br/>  - username: ' . ($game->username ?? 'NULL');
            echo '<br/>  - chesscomGameUrl: ' . ($game->chesscomGameUrl ?? 'NULL');
            echo '<br/>  - whiteUsername: ' . ($game->whiteUsername ?? 'NULL');
            echo '<br/>  - blackUsername: ' . ($game->blackUsername ?? 'NULL');
            
            // Verificar estructura de tabla primero
            $this->debugTableStructure();
            
            $stmt = $this->pdo->prepare("
                INSERT INTO chesscom_player_games (
                    participante_id, username, game_url, pgn, time_control, time_class, rules, rated,
                    white_username, white_rating, white_result, accuracy_white, white_uuid,
                    black_username, black_rating, black_result, accuracy_black, black_uuid,
                    end_time, eco, fecha_alta, fecha_modificacion
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $params = [
                $game->participanteId,
                $game->username,
                $game->chesscomGameUrl,
                $game->pgn,
                $game->timeControl,
                $game->timeClass,
                $game->rules,
                $game->rated,
                $game->whiteUsername,
                $game->whiteRating,
                $game->whiteResult,
                $game->accuracyWhite,
                $game->whiteUUID,
                $game->blackUsername,
                $game->blackRating,
                $game->blackResult,
                $game->accuracyBlack,
                $game->blackUUID,
                $game->endTime,
                $game->eco,
                // Eliminado tournament y match
                $game->fechaAlta,
                $game->fechaModificacion
            ];
            
            echo '<br/>DEBUG DAO: Parámetros para inserción:';
            for ($i = 0; $i < count($params); $i++) {
                echo '<br/>  [' . $i . '] = ' . ($params[$i] === null ? 'NULL' : '"' . $params[$i] . '"');
            }
            
            echo '<br/>DEBUG DAO: Ejecutando query...';
            $result = $stmt->execute($params);
            
            if (!$result) {
                echo '<br/>ERROR DAO: Error en execute()';
                $errorInfo = $stmt->errorInfo();
                echo '<br/>  - SQLSTATE: ' . $errorInfo[0];
                echo '<br/>  - Error Code: ' . $errorInfo[1];
                echo '<br/>  - Error Message: ' . $errorInfo[2];
                return 0;
            }
            
            $lastId = (int)$this->pdo->lastInsertId();
            echo '<br/>DEBUG DAO: ✓ Inserción exitosa - ID generado: ' . $lastId;
            
            return $lastId;
            
        } catch (PDOException $e) {
            echo '<br/>ERROR PDO en create(): ' . $e->getMessage();
            echo '<br/>  - Código: ' . $e->getCode();
            echo '<br/>  - SQL State: ' . $e->errorInfo[0] ?? 'N/A';
            return 0;
        } catch (Exception $e) {
            echo '<br/>ERROR GENERAL en create(): ' . $e->getMessage();
            echo '<br/>  - Línea: ' . $e->getLine();
            echo '<br/>  - Archivo: ' . $e->getFile();
            return 0;
        }
    }

    /**
     * Obtener una partida por ID
     */
    public function getById(int $id): ?ChesscomPlayerGame
    {
        $stmt = $this->pdo->prepare("SELECT * FROM chesscom_player_games WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$row) {
            return null;
        }
        
        return $this->mapRowToObject($row);
    }

    /**
     * Obtener partidas por ID de participante
     */
    /*public function getByParticipanteId(int $participante_id): array
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM chesscom_player_games
            WHERE participante_id = ?
            ORDER BY end_time DESC, game_id DESC
        ");
        $stmt->execute([$participante_id]);
        
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $this->mapRowToObject($row);
        }
        
        return $result;
    }*/

       /**
     * Obtener partidas por ID de participante
     */
    public function obtenerGamesPorParticipanteId(int $participante_id): array
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM chesscom_player_games
            WHERE participante_id = ?
            ORDER BY end_time DESC, id DESC
        ");
        $stmt->execute([$participante_id]);
        
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $this->mapRowToObject($row);
        }
        
        return $result;
    }

    /**
     * Obtener partidas por username
     */
    public function obtenerGamesPorUsername(string $username): array
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM chesscom_player_games
            WHERE username = ?
            ORDER BY end_time DESC, id DESC
        ");
        $stmt->execute([$username]);

        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $this->mapRowToObject($row);
        }

        return $result;
    }

    /**
     * Obtener todas las partidas
     */
    public function getAll(): array
    {
        $stmt = $this->pdo->query("
            SELECT * FROM chesscom_player_games
            ORDER BY end_time DESC, id DESC
        ");
        
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $this->mapRowToObject($row);
        }
        
        return $result;
    }

    /**
     * NUEVO: Verificar si existe una partida por URL
     */
    public function existsByUrl(string $url): bool
    {
        try {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM chesscom_player_games WHERE game_url = ?");
            $stmt->execute([$url]);
            $count = $stmt->fetchColumn();
            return $count > 0;
        } catch (Exception $e) {
            echo '<br/>ERROR en ChesscomPlayerGameDAO existsByUrl(): ' . $e->getMessage();
            return false;
        }
    }

    /**
     * Obtener partidas por URL
     */
    public function getByUrl(string $url): ?ChesscomPlayerGame
    {
        $stmt = $this->pdo->prepare("SELECT * FROM chesscom_player_games WHERE game_url = ?");
        $stmt->execute([$url]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row ? $this->mapRowToObject($row) : null;
    }

    /**
     * Actualizar una partida existente
     */
    public function update(ChesscomPlayerGame $game): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE chesscom_player_games SET
                participante_id = ?, username = ?, game_url = ?, pgn = ?, time_control = ?, time_class = ?, rules = ?, rated = ?,
                white_username = ?, white_rating = ?, white_result = ?, accuracy_white = ?, white_uuid = ?,
                black_username = ?, black_rating = ?, black_result = ?, accuracy_black = ?, black_uuid = ?,
                end_time = ?, eco = ?, fecha_modificacion = ?
            WHERE game_id = ?
        ");
        
        return $stmt->execute([
            $game->participanteId,
            $game->username,
            $game->chesscomGameUrl,
            $game->pgn,
            $game->timeControl,
            $game->timeClass,
            $game->rules,
            $game->rated,
            $game->whiteUsername,
            $game->whiteRating,
            $game->whiteResult,
            $game->accuracyWhite,
            $game->whiteUUID,
            $game->blackUsername,
            $game->blackRating,
            $game->blackResult,
            $game->accuracyBlack,
            $game->blackUUID,
            $game->endTime,
            $game->eco,
            // Eliminado tournament y match
            date('Y-m-d H:i:s'), // fecha_modificacion siempre actual en updates
            $game->gameId
        ]);
    }

    /**
     * Eliminar una partida
     */
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM chesscom_player_games WHERE game_id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Contar partidas por participante
     */
    public function countByParticipanteId(int $participante_id): int
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM chesscom_player_games WHERE participante_id = ?");
        $stmt->execute([$participante_id]);
        return (int)$stmt->fetchColumn();
    }

    /**
     * Mapear una fila de la base de datos a un objeto ChesscomPlayerGame
     */
    private function mapRowToObject(array $row): ChesscomPlayerGame
    {
        $game = new ChesscomPlayerGame();
        
        // Establecer datos por grupos usando el nuevo patrón
        $game->setDatosGenerales(
            (int)$row['id'],
            (int)$row['participante_id'],
            $row['username'] ?? null,
            $row['game_url'] ?? null,
            $row['pgn'] ?? null
        );
        
        $game->setDatosBlancas(
            $row['white_username'] ?? null,
            isset($row['white_rating']) && $row['white_rating'] ? (int)$row['white_rating'] : null,
            $row['white_result'] ?? null,
            isset($row['accuracy_white']) && $row['accuracy_white'] ? (float)$row['accuracy_white'] : null,
            $row['white_uuid'] ?? null
        );
        
        $game->setDatosNegras(
            $row['black_username'] ?? null,
            isset($row['black_rating']) && $row['black_rating'] ? (int)$row['black_rating'] : null,
            $row['black_result'] ?? null,
            isset($row['accuracy_black']) && $row['accuracy_black'] ? (float)$row['accuracy_black'] : null,
            $row['black_uuid'] ?? null
        );
        
        $game->setDatosPartida(
            $row['time_control'] ?? null,
            $row['time_class'] ?? null,
            $row['rules'] ?? null,
            isset($row['rated']) && $row['rated'] ? (int)$row['rated'] : null,
            isset($row['end_time']) && $row['end_time'] ? (int)$row['end_time'] : null,
            null, // start_time - no existe en tabla
            $row['eco'] ?? null,
            null, // tournament - no existe en tabla
            null  // match - no existe en tabla
        );
        
        // Establecer fechas manualmente (sin triggerar actualizarFechaModificacion)
        $game->fechaAlta = $row['fecha_alta'] ?? date('Y-m-d H:i:s');
        $game->fechaModificacion = $row['fecha_modificacion'] ?? null;
        
        // Finalizar configuración para permitir futuras modificaciones
        $game->finalizarConfiguracion();
        
        return $game;
    }

    /**
     * Método para probar la inserción
     */
    public function testInsert(): void
    {
        try {
            echo '<br/>TESTING: Probando inserción en chesscom_player_games...';
            
            $testGame = new ChesscomPlayerGame();
            $testGame->setDatosGenerales(0, 1, 'testuser', 'https://test.com/game/123', '[Event "Test"]')
                     ->setDatosBlancas('testuser1', 1500, 'win', 95.5, 'uuid1')
                     ->setDatosNegras('testuser2', 1400, 'resigned', 89.2, 'uuid2')
                     ->setDatosPartida('600', 'rapid', 'chess', 1, 1234567890, null, 'A00', null, null) // start_time, tournament y match = null
                     ->finalizarConfiguracion();
            
            echo '<br/>✓ Objeto de test creado: ' . $testGame;
            
            // Intentar insertar (comentar para evitar inserción real)
            // $id = $this->create($testGame);
            // echo '<br/>✓ Inserción exitosa con ID: ' . $id;
            
        } catch (Exception $e) {
            echo '<br/>ERROR en test de inserción: ' . $e->getMessage();
        }
    }

    /**
     * Método de debug para verificar la estructura de la tabla
     */
    public function debugTableStructure(): void
    {
        try {
            $stmt = $this->pdo->query("DESCRIBE chesscom_player_games");
            echo '<br/>DEBUG - Estructura de tabla chesscom_player_games:';
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<br/>  - ' . $row['Field'] . ' (' . $row['Type'] . ')' . 
                     ($row['Null'] === 'YES' ? ' NULL' : ' NOT NULL') . 
                     ($row['Key'] === 'PRI' ? ' PRIMARY KEY' : '') .
                     ($row['Extra'] ? ' ' . $row['Extra'] : '');
            }
        } catch (Exception $e) {
            echo '<br/>ERROR verificando estructura de tabla: ' . $e->getMessage();
        }
    }

    /**
     * Método para testing básico de conexión
     */
    public function testConnection(): void
    {
        try {
            echo '<br/>TESTING: Verificando conexión PDO...';
            $stmt = $this->pdo->query("SELECT 1");
            echo '<br/>✓ Conexión PDO funciona';
            
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM chesscom_player_games");
            $count = $stmt->fetchColumn();
            echo '<br/>✓ Tabla chesscom_player_games accesible - Registros: ' . $count;
            
        } catch (Exception $e) {
            echo '<br/>ERROR en test de conexión: ' . $e->getMessage();
        }
    }
}