<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\dao\ChesscomProfileDAO.php

require_once __DIR__ . '/../modelo/ChesscomProfile.php';

class ChesscomProfileDAO
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create(ChesscomProfile $profile): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO chesscom_profile (
                player_id, chesscom_id, participante_id, url, username, followers, country, last_online, joined, status,
                is_streamer, verified, league, fecha_alta, fecha_modificacion
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $profile->player_id,
            $profile->chesscom_id,
            $profile->participante_id,
            $profile->url,
            $profile->username,
            $profile->followers,
            $profile->country,
            $profile->last_online,
            $profile->joined,
            $profile->status,
            $profile->is_streamer,
            $profile->verified,
            $profile->league,
            $profile->fecha_alta,
            $profile->fecha_modificacion
        ]);
        return (int)$profile->player_id;
    }

    public function getById(int $player_id): ?ChesscomProfile
    {
        $stmt = $this->pdo->prepare("SELECT * FROM chesscom_profile WHERE player_id = ?");
        $stmt->execute([$player_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new ChesscomProfile(
            $row['player_id'],
            $row['chesscom_id'],
            $row['participante_id'],
            $row['url'],
            $row['username'],
            $row['followers'],
            $row['country'],
            $row['last_online'],
            $row['joined'],
            $row['status'],
            $row['is_streamer'],
            $row['verified'],
            $row['league'],
            $row['fecha_alta'],
            $row['fecha_modificacion']
        ) : null;
    }

    public function getByParticipanteId(int $participante_id): ?ChesscomProfile
    {
        $stmt = $this->pdo->prepare("SELECT * FROM chesscom_profile WHERE participante_id = ?");
        $stmt->execute([$participante_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new ChesscomProfile(
            $row['player_id'],
            $row['chesscom_id'],
            $row['participante_id'],
            $row['url'],
            $row['username'],
            $row['followers'],
            $row['country'],
            $row['last_online'],
            $row['joined'],
            $row['status'],
            $row['is_streamer'],
            $row['verified'],
            $row['league'],
            $row['fecha_alta'],
            $row['fecha_modificacion']
        ) : null;
    }

    public function update(ChesscomProfile $profile): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE chesscom_profile SET
                chesscom_id = ?, participante_id = ?, url = ?, username = ?, followers = ?, country = ?, last_online = ?, joined = ?, status = ?,
                is_streamer = ?, verified = ?, league = ?, fecha_modificacion = ?
            WHERE player_id = ?
        ");
        return $stmt->execute([
            $profile->chesscom_id,
            $profile->participante_id,
            $profile->url,
            $profile->username,
            $profile->followers,
            $profile->country,
            $profile->last_online,
            $profile->joined,
            $profile->status,
            $profile->is_streamer,
            $profile->verified,
            $profile->league,
            $profile->fecha_modificacion ?? date('Y-m-d H:i:s'),
            $profile->player_id
        ]);
    }

    public function delete(int $player_id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM chesscom_profile WHERE player_id = ?");
        return $stmt->execute([$player_id]);
    }

    // Verifica si existe un perfil de chess.com para el participante y username dados
    public function existeProfile($participanteId, $username) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM chesscom_profile WHERE participante_id = :participante_id AND username = :username");
        $stmt->execute([
            ':participante_id' => $participanteId,
            ':username' => $username
        ]);
        return $stmt->fetchColumn() > 0;
    }

}