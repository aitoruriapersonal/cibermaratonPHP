<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\dao\CampeonatoParticipanteDAO.php

class CampeonatoParticipanteDAO
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getParticipantesByCampeonato(int $campeonatoId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT p.*, cp.tipo_estudio, cp.grado_id, cp.postgrado_id, cp.doctorado_id, cp.chesscom_player_id
            FROM participante cp
            JOIN participante p ON cp.participante_id = p.id
            WHERE cp.campeonato_id = ?
        ");
        $stmt->execute([$campeonatoId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addParticipanteToCampeonato(array $data): bool
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO campeonato_participante
            (campeonato_id, participante_id, tipo_estudio, grado_id, postgrado_id, doctorado_id, chesscom_player_id)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['campeonato_id'],
            $data['participante_id'],
            $data['tipo_estudio'],
            $data['grado_id'] ?? null,
            $data['postgrado_id'] ?? null,
            $data['doctorado_id'] ?? null,
            $data['chesscom_player_id'] ?? null
        ]);
    }
}