<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\dao\ResultadoDAO.php

require_once __DIR__ . '/../modelo/Resultado.php';

class ResultadoDAO
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create(Resultado $r): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO resultados (
                participante_id, username, color, elo, rival, color_rival, elo_rival, resultado, resultado_desc,
                fecha_partida, url_partida, numero_partida, fecha_alta, fecha_modificacion
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $r->participante_id,
            $r->username,
            $r->color,
            $r->elo,
            $r->rival,
            $r->color_rival,
            $r->elo_rival,
            $r->resultado,
            $r->resultado_desc,
            $r->fecha_partida,
            $r->url_partida,
            $r->numero_partida,
            $r->fecha_alta,
            $r->fecha_modificacion
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function getById(int $id): ?Resultado
    {
        $stmt = $this->pdo->prepare("SELECT * FROM resultados WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new Resultado(
            $row['id'],
            $row['participante_id'],
            $row['username'],
            $row['color'],
            $row['elo'],
            $row['rival'],
            $row['color_rival'],
            $row['elo_rival'],
            $row['resultado'],
            $row['resultado_desc'],
            $row['fecha_partida'],
            $row['url_partida'],
            $row['numero_partida'],
            $row['fecha_alta'],
            $row['fecha_modificacion']
        ) : null;
    }

    public function getByParticipanteId(int $participanteId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM resultados WHERE participante_id = ? ORDER BY numero_partida ASC");
        $stmt->execute([$participanteId]);
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new Resultado(
                $row['id'],
                $row['participante_id'],
                $row['username'],
                $row['color'],
                $row['elo'],
                $row['rival'],
                $row['color_rival'],
                $row['elo_rival'],
                $row['resultado'],
                $row['resultado_desc'],
                $row['fecha_partida'],
                $row['url_partida'],
                $row['numero_partida'],
                $row['fecha_alta'],
                $row['fecha_modificacion']
            );
        }
        return $result;
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM resultados ORDER BY fecha_partida DESC, id DESC");
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new Resultado(
                $row['id'],
                $row['participante_id'],
                $row['username'],
                $row['color'],
                $row['elo'],
                $row['rival'],
                $row['color_rival'],
                $row['elo_rival'],
                $row['resultado'],
                $row['resultado_desc'],
                $row['fecha_partida'],
                $row['url_partida'],
                $row['numero_partida'],
                $row['fecha_alta'],
                $row['fecha_modificacion']
            );
        }
        return $result;
    }

    public function update(Resultado $r): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE resultados
            SET participante_id = ?, username = ?, color = ?, elo = ?, rival = ?, color_rival = ?, elo_rival = ?,
                resultado = ?, resultado_desc = ?, fecha_partida = ?, url_partida = ?, numero_partida = ?, fecha_modificacion = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $r->participante_id,
            $r->username,
            $r->color,
            $r->elo,
            $r->rival,
            $r->color_rival,
            $r->elo_rival,
            $r->resultado,
            $r->resultado_desc,
            $r->fecha_partida,
            $r->url_partida,
            $r->numero_partida,
            $r->fecha_modificacion ?? date('Y-m-d H:i:s'),
            $r->id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM resultados WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Obtiene los resultados por participante.
     *
     * @param int $participanteId
     * @return array
     */
    public function getResultadosPorParticipante($participanteId) {
        $stmt = $this->pdo->prepare("SELECT * FROM resultados WHERE participante_id = :participante_id");
        $stmt->execute(['participante_id' => $participanteId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Obtiene los resultados por el nickname del participante.
     */
    public function getResultadosPorParticipanteNickName(string $username): array
{
    $stmt = $this->pdo->prepare("SELECT * FROM resultados WHERE username = ? ORDER BY fecha_partida DESC, id DESC");
    $stmt->execute([$username]);
    $result = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $result[] = new Resultado(
            $row['id'],
            $row['participante_id'],
            $row['username'],
            $row['color'],
            $row['elo'],
            $row['rival'],
            $row['color_rival'],
            $row['elo_rival'],
            $row['resultado'],
            $row['resultado_desc'],
            $row['fecha_partida'],
            $row['url_partida'],
            $row['numero_partida'],
            $row['fecha_alta'],
            $row['fecha_modificacion']
        );
    }
    return $result;
}

}