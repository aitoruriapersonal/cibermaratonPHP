<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\dao\ChesscomPlayerGameDAO.php

require_once __DIR__ . '/../modelo/ChesscomPlayerGame.php';

class ChesscomPlayerGameDAO
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create(ChesscomPlayerGame $game): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO chesscom_player_games (
                participante_id, username, url, pgn, time_control, end_time, rated, accuracy_white, accuracy_black, fen,
                time_class, rules, white_rating, white_result, white_id, white_username, white_uuid,
                black_rating, black_result, black_id, black_username, black_uuid, eco, fecha_alta, fecha_modificacaion
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $game->participante_id,
            $game->username,
            $game->url,
            $game->pgn,
            $game->time_control,
            $game->end_time,
            $game->rated,
            $game->accuracy_white,
            $game->accuracy_black,
            $game->fen,
            $game->time_class,
            $game->rules,
            $game->white_rating,
            $game->white_result,
            $game->white_id,
            $game->white_username,
            $game->white_uuid,
            $game->black_rating,
            $game->black_result,
            $game->black_id,
            $game->black_username,
            $game->black_uuid,
            $game->eco,
            $game->fecha_alta,
            $game->fecha_modificacaion
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function getById(int $id): ?ChesscomPlayerGame
    {
        $stmt = $this->pdo->prepare("SELECT * FROM chesscom_player_games WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new ChesscomPlayerGame(
            $row['id'],
            $row['participante_id'],
            $row['username'],
            $row['url'],
            $row['pgn'],
            $row['time_control'],
            $row['end_time'],
            $row['rated'],
            $row['accuracy_white'],
            $row['accuracy_black'],
            $row['fen'],
            $row['time_class'],
            $row['rules'],
            $row['white_rating'],
            $row['white_result'],
            $row['white_id'],
            $row['white_username'],
            $row['white_uuid'],
            $row['black_rating'],
            $row['black_result'],
            $row['black_id'],
            $row['black_username'],
            $row['black_uuid'],
            $row['eco'],
            $row['fecha_alta'],
            $row['fecha_modificacaion']
        ) : null;
    }

    public function getByParticipanteId(int $participante_id): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM chesscom_player_games WHERE participante_id = ? ORDER BY end_time DESC");
        $stmt->execute([$participante_id]);
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new ChesscomPlayerGame(
                $row['id'],
                $row['participante_id'],
                $row['username'],
                $row['url'],
                $row['pgn'],
                $row['time_control'],
                $row['end_time'],
                $row['rated'],
                $row['accuracy_white'],
                $row['accuracy_black'],
                $row['fen'],
                $row['time_class'],
                $row['rules'],
                $row['white_rating'],
                $row['white_result'],
                $row['white_id'],
                $row['white_username'],
                $row['white_uuid'],
                $row['black_rating'],
                $row['black_result'],
                $row['black_id'],
                $row['black_username'],
                $row['black_uuid'],
                $row['eco'],
                $row['fecha_alta'],
                $row['fecha_modificacaion']
            );
        }
        return $result;
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM chesscom_player_games ORDER BY end_time DESC, id DESC");
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new ChesscomPlayerGame(
                $row['id'],
                $row['participante_id'],
                $row['username'],
                $row['url'],
                $row['pgn'],
                $row['time_control'],
                $row['end_time'],
                $row['rated'],
                $row['accuracy_white'],
                $row['accuracy_black'],
                $row['fen'],
                $row['time_class'],
                $row['rules'],
                $row['white_rating'],
                $row['white_result'],
                $row['white_id'],
                $row['white_username'],
                $row['white_uuid'],
                $row['black_rating'],
                $row['black_result'],
                $row['black_id'],
                $row['black_username'],
                $row['black_uuid'],
                $row['eco'],
                $row['fecha_alta'],
                $row['fecha_modificacaion']
            );
        }
        return $result;
    }

    public function update(ChesscomPlayerGame $game): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE chesscom_player_games SET
                participante_id = ?, username = ?, url = ?, pgn = ?, time_control = ?, end_time = ?, rated = ?, accuracy_white = ?, accuracy_black = ?, fen = ?,
                time_class = ?, rules = ?, white_rating = ?, white_result = ?, white_id = ?, white_username = ?, white_uuid = ?,
                black_rating = ?, black_result = ?, black_id = ?, black_username = ?, black_uuid = ?, eco = ?, fecha_modificacaion = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $game->participante_id,
            $game->username,
            $game->url,
            $game->pgn,
            $game->time_control,
            $game->end_time,
            $game->rated,
            $game->accuracy_white,
            $game->accuracy_black,
            $game->fen,
            $game->time_class,
            $game->rules,
            $game->white_rating,
            $game->white_result,
            $game->white_id,
            $game->white_username,
            $game->white_uuid,
            $game->black_rating,
            $game->black_result,
            $game->black_id,
            $game->black_username,
            $game->black_uuid,
            $game->eco,
            $game->fecha_modificacaion ?? date('Y-m-d H:i:s'),
            $game->id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM chesscom_player_games WHERE id = ?");
        return $stmt->execute([$id]);
    }
}