<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\dao\ChesscomPlayerStatsDAO.php

require_once __DIR__ . '/../modelo/ChesscomPlayerStats.php';

class ChesscomPlayerStatsDAO
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create(ChesscomPlayerStats $stats): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO chesscom_player_stats (
                participante_id, username, rapid_last_rating, rapid_last_date, rapid_last_rd, 
                rapid_best_rating, rapid_best_date, rapid_best_game, rapid_record_win, rapid_record_loss, 
                rapid_record_draw, bullet_last_rating, bullet_last_date, bullet_last_rd, bullet_best_rating,
                bullet_best_date, bullet_best_game, bullet_record_win, bullet_record_loss, bullet_record_draw, 
                blitz_last_rating, blitz_last_date, blitz_last_rd, blitz_best_rating, blitz_best_date, 
                blitz_best_game, blitz_record_win, blitz_record_loss, blitz_record_draw, tactics_highest_rating, 
                tactics_highest_date, tactics_lowest_rating, tactics_lowest_date, puzzle_rush_best_total_attempts, puzzle_rush_best_score, 
                fecha_alta, fecha_modificacion
            ) VALUES (
             ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 
             ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 
             ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 
             ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $stats->participante_id,
            $stats->username,
            $stats->rapid_last_rating,
            $stats->rapid_last_date,
            $stats->rapid_last_rd,
            $stats->rapid_best_rating,
            $stats->rapid_best_date,
            $stats->rapid_best_game,
            $stats->rapid_record_win,
            $stats->rapid_record_loss,
            $stats->rapid_record_draw,
            $stats->bullet_last_rating,
            $stats->bullet_last_date,
            $stats->bullet_last_rd,
            $stats->bullet_best_rating,
            $stats->bullet_best_date,
            $stats->bullet_best_game,
            $stats->bullet_record_win,
            $stats->bullet_record_loss,
            $stats->bullet_record_draw,
            $stats->blitz_last_rating,
            $stats->blitz_last_date,
            $stats->blitz_last_rd,
            $stats->blitz_best_rating,
            $stats->blitz_best_date,
            $stats->blitz_best_game,
            $stats->blitz_record_win,
            $stats->blitz_record_loss,
            $stats->blitz_record_draw,
            $stats->tactics_highest_rating,
            $stats->tactics_highest_date,
            $stats->tactics_lowest_rating,
            $stats->tactics_lowest_date,
            $stats->puzzle_rush_best_total_attempts,
            $stats->puzzle_rush_best_score,
            $stats->fecha_alta,
            $stats->fecha_modificacion
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function getById(int $id): ?ChesscomPlayerStats
    {
        $stmt = $this->pdo->prepare("SELECT * FROM chesscom_player_stats WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new ChesscomPlayerStats(
            $row['id'],
            $row['participante_id'],
            $row['username'],
            $row['rapid_last_rating'],
            $row['rapid_last_date'],
            $row['rapid_last_rd'],
            $row['rapid_best_rating'],
            $row['rapid_best_date'],
            $row['rapid_best_game'],
            $row['rapid_record_win'],
            $row['rapid_record_loss'],
            $row['rapid_record_draw'],
            $row['bullet_last_rating'],
            $row['bullet_last_date'],
            $row['bullet_last_rd'],
            $row['bullet_best_rating'],
            $row['bullet_best_date'],
            $row['bullet_best_game'],
            $row['bullet_record_win'],
            $row['bullet_record_loss'],
            $row['bullet_record_draw'],
            $row['blitz_last_rating'],
            $row['blitz_last_date'],
            $row['blitz_last_rd'],
            $row['blitz_best_rating'],
            $row['blitz_best_date'],
            $row['blitz_best_game'],
            $row['blitz_record_win'],
            $row['blitz_record_loss'],
            $row['blitz_record_draw'],
            $row['tactics_highest_rating'],
            $row['tactics_highest_date'],
            $row['tactics_lowest_rating'],
            $row['tactics_lowest_date'],
            $row['puzzle_rush_best_total_attempts'],
            $row['puzzle_rush_best_score'],
            $row['fecha_alta'],
            $row['decha_modificacion']
        ) : null;
    }

    public function getByParticipanteId(int $participante_id): ?ChesscomPlayerStats
    {
        $stmt = $this->pdo->prepare("SELECT * FROM chesscom_player_stats WHERE participante_id = ?");
        $stmt->execute([$participante_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new ChesscomPlayerStats(
            $row['id'],
            $row['participante_id'],
            $row['username'],
            $row['rapid_last_rating'],
            $row['rapid_last_date'],
            $row['rapid_last_rd'],
            $row['rapid_best_rating'],
            $row['rapid_best_date'],
            $row['rapid_best_game'],
            $row['rapid_record_win'],
            $row['rapid_record_loss'],
            $row['rapid_record_draw'],
            $row['bullet_last_rating'],
            $row['bullet_last_date'],
            $row['bullet_last_rd'],
            $row['bullet_best_rating'],
            $row['bullet_best_date'],
            $row['bullet_best_game'],
            $row['bullet_record_win'],
            $row['bullet_record_loss'],
            $row['bullet_record_draw'],
            $row['blitz_last_rating'],
            $row['blitz_last_date'],
            $row['blitz_last_rd'],
            $row['blitz_best_rating'],
            $row['blitz_best_date'],
            $row['blitz_best_game'],
            $row['blitz_record_win'],
            $row['blitz_record_loss'],
            $row['blitz_record_draw'],
            $row['tactics_highest_rating'],
            $row['tactics_highest_date'],
            $row['tactics_lowest_rating'],
            $row['tactics_lowest_date'],
            $row['puzzle_rush_best_total_attempts'],
            $row['puzzle_rush_best_score'],
            $row['fecha_alta'],
            $row['decha_modificacion']
        ) : null;
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM chesscom_player_stats ORDER BY id ASC");
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new ChesscomPlayerStats(
                $row['id'],
                $row['participante_id'],
                $row['username'],
                $row['rapid_last_rating'],
                $row['rapid_last_date'],
                $row['rapid_last_rd'],
                $row['rapid_best_rating'],
                $row['rapid_best_date'],
                $row['rapid_best_game'],
                $row['rapid_record_win'],
                $row['rapid_record_loss'],
                $row['rapid_record_draw'],
                $row['bullet_last_rating'],
                $row['bullet_last_date'],
                $row['bullet_last_rd'],
                $row['bullet_best_rating'],
                $row['bullet_best_date'],
                $row['bullet_best_game'],
                $row['bullet_record_win'],
                $row['bullet_record_loss'],
                $row['bullet_record_draw'],
                $row['blitz_last_rating'],
                $row['blitz_last_date'],
                $row['blitz_last_rd'],
                $row['blitz_best_rating'],
                $row['blitz_best_date'],
                $row['blitz_best_game'],
                $row['blitz_record_win'],
                $row['blitz_record_loss'],
                $row['blitz_record_draw'],
                $row['tactics_highest_rating'],
                $row['tactics_highest_date'],
                $row['tactics_lowest_rating'],
                $row['tactics_lowest_date'],
                $row['puzzle_rush_best_total_attempts'],
                $row['puzzle_rush_best_score'],
                $row['fecha_alta'],
                $row['decha_modificacion']
            );
        }
        return $result;
    }

    public function update(ChesscomPlayerStats $stats): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE chesscom_player_stats SET
                participante_id = ?, username = ?, rapid_last_rating = ?, rapid_last_date = ?, rapid_last_rd = ?, rapid_best_rating = ?, rapid_best_date = ?, rapid_best_game = ?,
                rapid_record_win = ?, rapid_record_loss = ?, rapid_record_draw = ?, bullet_last_rating = ?, bullet_last_date = ?, bullet_last_rd = ?, bullet_best_rating = ?,
                bullet_best_date = ?, bullet_best_game = ?, bullet_record_win = ?, bullet_record_loss = ?, bullet_record_draw = ?, blitz_last_rating = ?, blitz_last_date = ?,
                blitz_last_rd = ?, blitz_best_rating = ?, blitz_best_date = ?, blitz_best_game = ?, blitz_record_win = ?, blitz_record_loss = ?, blitz_record_draw = ?,
                tactics_highest_rating = ?, tactics_highest_date = ?, tactics_lowest_rating = ?, tactics_lowest_date = ?, puzzle_rush_best_total_attempts = ?,
                puzzle_rush_best_score = ?, fecha_modificacion = NOW()
            WHERE participante_id = ?
        ");
        return $stmt->execute([
            $stats->participante_id,
            $stats->username,
            $stats->rapid_last_rating,
            $stats->rapid_last_date,
            $stats->rapid_last_rd,
            $stats->rapid_best_rating,
            $stats->rapid_best_date,
            $stats->rapid_best_game,
            $stats->rapid_record_win,
            $stats->rapid_record_loss,
            $stats->rapid_record_draw,
            $stats->bullet_last_rating,
            $stats->bullet_last_date,
            $stats->bullet_last_rd,
            $stats->bullet_best_rating,
            $stats->bullet_best_date,
            $stats->bullet_best_game,
            $stats->bullet_record_win,
            $stats->bullet_record_loss,
            $stats->bullet_record_draw,
            $stats->blitz_last_rating,
            $stats->blitz_last_date,
            $stats->blitz_last_rd,
            $stats->blitz_best_rating,
            $stats->blitz_best_date,
            $stats->blitz_best_game,
            $stats->blitz_record_win,
            $stats->blitz_record_loss,
            $stats->blitz_record_draw,
            $stats->tactics_highest_rating,
            $stats->tactics_highest_date,
            $stats->tactics_lowest_rating,
            $stats->tactics_lowest_date,
            $stats->puzzle_rush_best_total_attempts,
            $stats->puzzle_rush_best_score,
            $stats->id
        ]);
    }


    public function updateStats(ChesscomPlayerStats $stats): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE chesscom_player_stats SET
                rapid_last_rating = ?, rapid_last_date = ?, rapid_last_rd = ?, rapid_best_rating = ?, rapid_best_date = ?, rapid_best_game = ?,
                rapid_record_win = ?, rapid_record_loss = ?, rapid_record_draw = ?, bullet_last_rating = ?, bullet_last_date = ?, bullet_last_rd = ?, bullet_best_rating = ?,
                bullet_best_date = ?, bullet_best_game = ?, bullet_record_win = ?, bullet_record_loss = ?, bullet_record_draw = ?, blitz_last_rating = ?, blitz_last_date = ?,
                blitz_last_rd = ?, blitz_best_rating = ?, blitz_best_date = ?, blitz_best_game = ?, blitz_record_win = ?, blitz_record_loss = ?, blitz_record_draw = ?,
                tactics_highest_rating = ?, tactics_highest_date = ?, tactics_lowest_rating = ?, tactics_lowest_date = ?, puzzle_rush_best_total_attempts = ?,
                puzzle_rush_best_score = ?, fecha_modificacion = NOW()
            WHERE participante_id = ?
        ");
        return $stmt->execute([
            $stats->rapid_last_rating,
            $stats->rapid_last_date,
            $stats->rapid_last_rd,
            $stats->rapid_best_rating,
            $stats->rapid_best_date,
            $stats->rapid_best_game,
            $stats->rapid_record_win,
            $stats->rapid_record_loss,
            $stats->rapid_record_draw,
            $stats->bullet_last_rating,
            $stats->bullet_last_date,
            $stats->bullet_last_rd,
            $stats->bullet_best_rating,
            $stats->bullet_best_date,
            $stats->bullet_best_game,
            $stats->bullet_record_win,
            $stats->bullet_record_loss,
            $stats->bullet_record_draw,
            $stats->blitz_last_rating,
            $stats->blitz_last_date,
            $stats->blitz_last_rd,
            $stats->blitz_best_rating,
            $stats->blitz_best_date,
            $stats->blitz_best_game,
            $stats->blitz_record_win,
            $stats->blitz_record_loss,
            $stats->blitz_record_draw,
            $stats->tactics_highest_rating,
            $stats->tactics_highest_date,
            $stats->tactics_lowest_rating,
            $stats->tactics_lowest_date,
            $stats->puzzle_rush_best_total_attempts,
            $stats->puzzle_rush_best_score,
            $stats->participante_id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM chesscom_player_stats WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Verifica si existen stats para un participante dado
    public function existeStats($participanteId) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM chesscom_player_stats WHERE participante_id = ?");
        $stmt->execute([$participanteId]);
        return $stmt->fetchColumn() > 0;
    }
    
}