<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\dao\CampeonatoParticipanteDAO.php

class ParticipantesDAO
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    // READ (by id)
    public function getParticipanteById(int $id): ?Participante
    {
        $stmt = $this->pdo->prepare("
            SELECT *
            FROM participantes p
            WHERE p.id = ?
        ");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new Participante(
            $row['id'],
            $row['campeonato_id'],
            $row['nombre'],
            $row['apellidos'],
            $row['dni'],
            $row['telefono'],
            $row['email'],
            $row['fecha_nacimiento'],
            $row['nick'],
            $row['puntos'],
            $row['estudio_id'],
            $row['club_id'],
            $row['estado'],
            $row['terminado'],
            $row['creditos_totales'],
            $row['fecha_finalizado'],
            $row['fecha_alta'],
            $row['fecha_modificacion']
        ) : null;
    }

    public function getParticipantesByNick(string $nick): array
    {
        $stmt = $this->pdo->prepare("
            SELECT *
            FROM participantes p
            WHERE p.nick = ?
        ");
        $stmt->execute([$nick]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // READ (all by campeonato)
    public function getParticipantesByCampeonato(int $campeonatoId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT p.id, campeonato_id, p.nombre, nick AS participante, puntos AS partidas, puntos, terminado, p.estado, 
	            CASE WHEN c.nombre IS NULL THEN CONCAT (ce.nombre_eus, ' / ', ce.nombre_esp) ELSE c.nombre END facultadClub,
                CASE WHEN c.nombre IS NULL THEN 'Universidad / Unibertsitatea' ELSE 'Federado / Federatua' END categoria
            FROM participantes p
            LEFT JOIN clubs c ON club_id = c.id
            LEFT JOIN estudios e ON estudio_id = e.id
            LEFT JOIN centros ce ON ce.id = e.centro_id AND ce.estado = 1
            WHERE p.campeonato_id = ?
            ORDER BY puntos DESC, estado, p.id ASC, p.nick ASC
        ");
        $stmt->execute([$campeonatoId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByCampeonatoId(int $campeonatoId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM participantes WHERE campeonato_id = ? ORDER BY id ASC");
        $stmt->execute([$campeonatoId]);
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new Participante(
                $row['id'],
                $row['campeonato_id'],
                $row['nombre'],
                $row['apellidos'],
                $row['dni'],
                $row['telefono'],
                $row['email'],
                $row['fecha_nacimiento'],
                $row['nick'],
                $row['puntos'],
                $row['estudio_id'],
                $row['club_id'],
                $row['estado'],
                $row['terminado'],
                $row['creditos_totales'],
                $row['fecha_finalizado'],
                $row['fecha_alta'],
                $row['fecha_modificacion']
            );
        }
        return $result;
    }

    public function getParticipantesUniversitariosByCampeonato(int $campeonatoId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT p.id, campeonato_id, p.nombre, nick AS participante, puntos AS partidas, puntos, terminado, p.estado, 
	            CONCAT (ce.nombre_eus, ' / ', ce.nombre_esp) AS facultadClub,
                'Universidad / Unibertsitatea' AS categoria
            FROM participantes p
            LEFT JOIN estudios e ON estudio_id = e.id
            LEFT JOIN centros ce ON ce.id = e.centro_id AND ce.estado = 1
            WHERE p.campeonato_id = ?
            AND p.estudio_id IS NOT NULL
            AND p.club_id IS NULL
            ORDER BY puntos DESC, estado, p.id ASC, p.nick ASC
        ");
        $stmt->execute([$campeonatoId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getParticipantesFederadosByCampeonato(int $campeonatoId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT p.id, campeonato_id, p.nombre, nick AS participante, puntos AS partidas, puntos, terminado, p.estado, 
	            c.nombre AS club,
                'Federado / Federatua' AS categoria
            FROM participantes p
            LEFT JOIN clubs c ON club_id = c.id
            WHERE p.campeonato_id = ?
            AND p.estudio_id IS NULL
            AND p.club_id IS NOT NULL
            ORDER BY puntos DESC, estado, p.id ASC, p.nick ASC
        ");
        $stmt->execute([$campeonatoId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // CREATE
    public function createParticipante(array $data): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO participantes (
                campeonato_id, nombre, apellidos, dni, telefono, email, fecha_nacimiento, nick,
                estudio_id, club_id
            ) VALUES (
                :campeonato_id, :nombre, :apellidos, :dni, :telefono, :email, :fecha_nacimiento, :nick,
                :estudio_id, :club_id
            )
        ");
        //echo var_dump($data); // Debugging line to check data before insert
        $stmt->execute([
            ':campeonato_id'    => $data['campeonato'],
            ':nombre'           => $data['nombre'],
            ':apellidos'        => $data['apellidos'],
            ':dni'              => $data['dni'],
            ':telefono'         => $data['telefono'],
            ':email'            => $data['email'],
            ':fecha_nacimiento' => $data['nacimiento'],
            ':nick'             => $data['nick'],
            //':puntos'           => $data['puntos'] ?? 0,
            ':estudio_id'       => $data['estudio'] ?? null,
            ':club_id'          => $data['club'] ?? null
            //':estado'           => $data['estado'] ?? 'Sin registro chesscom',
            //':terminado'        => $data['terminado'] ?? 0,
            //':creditos_totales' => $data['creditos_totales'] ?? 0,
            //':fecha_finalizado' => $data['fecha_finalizado'] ?? date('Y-m-d H:i:s'),
            //':fecha_alta'       => $data['fecha_alta'] ?? date('Y-m-d H:i:s'),
            //':fecha_modificacion' => $data['fecha_modificacion'] ?? date('Y-m-d H:i:s'),
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function create(Participante $p): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO participantes (
                campeonato_id, nombre, apellidos, dni, telefono, email, fecha_nacimiento, nick, puntos,
                estudio_id, club_id, estado, terminado, creditos_totales, fecha_finalizado, fecha_alta, fecha_modificacion
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $p->campeonato_id,
            $p->nombre,
            $p->apellidos,
            $p->dni,
            $p->telefono,
            $p->email,
            $p->fecha_nacimiento,
            $p->nick,
            $p->puntos,
            $p->estudio_id,
            $p->club_id,
            $p->estado,
            $p->terminado,
            $p->creditos_totales,
            $p->fecha_finalizado,
            $p->fecha_alta,
            $p->fecha_modificacion
        ]);
        return (int)$this->pdo->lastInsertId();
    }

     public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM participantes ORDER BY id ASC");
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new Participante(
                $row['id'],
                $row['campeonato_id'],
                $row['nombre'],
                $row['apellidos'],
                $row['dni'],
                $row['telefono'],
                $row['email'],
                $row['fecha_nacimiento'],
                $row['nick'],
                $row['puntos'],
                $row['estudio_id'],
                $row['club_id'],
                $row['estado'],
                $row['terminado'],
                $row['creditos_totales'],
                $row['fecha_finalizado'],
                $row['fecha_alta'],
                $row['fecha_modificacion']
            );
        }
        return $result;
    }

    // UPDATE
    public function updateParticipante(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE participantes SET
                nombre = :nombre,
                apellidos = :apellidos,
                dni = :dni,
                telefono = :telefono,
                email = :email,
                fecha_nacimiento = :fecha_nacimiento,
                nick = :nick,
                puntos = :puntos,
                estudio_id = :estudio_id,
                club_id = :club_id,
                estado = :estado,
                terminado = :terminado,
                creditos_totales = :creditos_totales,
                fecha_finalizado = :fecha_finalizado,
                fecha_modificacion = :fecha_modificacion
            WHERE id = :id
        ");
        return $stmt->execute([
            ':nombre'           => $data['nombre'],
            ':apellidos'        => $data['apellidos'],
            ':dni'              => $data['dni'],
            ':telefono'         => $data['telefono'],
            ':email'            => $data['email'],
            ':fecha_nacimiento' => $data['fecha_nacimiento'],
            ':nick'             => $data['nick'],
            ':puntos'           => $data['puntos'] ?? 0,
            ':estudio_id'       => $data['estudio_id'] ?? null,
            ':club_id'          => $data['club_id'] ?? null,
            ':estado'           => $data['estado'] ?? 'Sin registro chesscom',
            ':terminado'        => $data['terminado'] ?? 0,
            ':creditos_totales' => $data['creditos_totales'] ?? 0,
            ':fecha_finalizado' => $data['fecha_finalizado'] ?? date('Y-m-d H:i:s'),
            ':fecha_modificacion' => $data['fecha_modificacion'] ?? date('Y-m-d H:i:s'),
            ':id'               => $id
        ]);
    }

    // DELETE
    public function deleteParticipante(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM participantes WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function update(Participante $p): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE participantes SET
                campeonato_id = ?, nombre = ?, apellidos = ?, dni = ?, telefono = ?, email = ?, fecha_nacimiento = ?, nick = ?, puntos = ?,
                estudio_id = ?, club_id = ?, estado = ?, terminado = ?, creditos_totales = ?, fecha_finalizado = ?, fecha_modificacion = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $p->campeonato_id,
            $p->nombre,
            $p->apellidos,
            $p->dni,
            $p->telefono,
            $p->email,
            $p->fecha_nacimiento,
            $p->nick,
            $p->puntos,
            $p->estudio_id,
            $p->club_id,
            $p->estado,
            $p->terminado,
            $p->creditos_totales,
            $p->fecha_finalizado,
            $p->fecha_modificacion ?? date('Y-m-d H:i:s'),
            $p->id
        ]);
    }

     // READ (by id)
    public function searchParticipanteNick(string $nick): string
    {
        $stmt = $this->pdo->prepare("SELECT nick FROM participantes WHERE nick LIKE ? ORDER BY nick DESC LIMIT 1");
        $stmt->execute([$nick]);
        $result = $stmt->fetchColumn();
        return $result ?: '';
    }

    public function searchDniDuplicadoByCampeonato(int $campeonatoId, string $dni): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM participantes WHERE campeonato_id = ? AND dni = ?");
        $stmt->execute([$campeonatoId, $dni]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
