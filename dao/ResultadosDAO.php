<?php
// filepath: c:\xampp\htdocs\deporteuniversitario\ajedrez\backend\dao\ResultadosDAO.php

require_once __DIR__ . '/../modelo/Resultados.php';

class ResultadoDAO
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create(Resultado $r): int
    {
        try {
            echo '<br/>DEBUG DAO: Iniciando inserción en resultados';
            
            // Verificar que el objeto está completo
            if (!$r->estaCompleto()) {
                echo '<br/>ERROR DAO: El objeto Resultado no está completo';
                $errores = $r->validar();
                foreach ($errores as $error) {
                    echo '<br/>  - ' . $error;
                }
                return 0;
            }
            
            $stmt = $this->pdo->prepare("
                INSERT INTO resultados (
                    participante_id, username, color, elo, rival, color_rival, elo_rival, resultado, resultado_desc,
                    fecha_partida, url_partida, numero_partida, fecha_alta, fecha_modificacion
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $fechaActual = date('Y-m-d H:i:s');
            
            $params = [
                $r->participanteId,      // Usar propiedades públicas
                $r->username,
                $r->color,
                $r->elo,
                $r->rival,
                $r->colorRival,
                $r->eloRival,
                $r->resultado,
                $r->resultadoDesc ?? '',
                $r->fechaPartida ?? $fechaActual,
                $r->urlPartida ?? '',
                $r->numeroPartida ?? 1,
                $fechaActual,            // fecha_alta
                $fechaActual             // fecha_modificacion
            ];
            
            echo '<br/>DEBUG DAO: Ejecutando inserción con datos:';
            echo '<br/>  - participante_id: ' . $r->participanteId;
            echo '<br/>  - username: ' . $r->username;
            echo '<br/>  - rival: ' . $r->rival;
            echo '<br/>  - resultado: ' . $r->resultado;
            
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
            echo '<br/>ERROR PDO en ResultadoDAO create(): ' . $e->getMessage();
            return 0;
        } catch (Exception $e) {
            echo '<br/>ERROR GENERAL en ResultadoDAO create(): ' . $e->getMessage();
            return 0;
        }
    }

    public function getById(int $id): ?Resultado
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM resultados WHERE id = ?");
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$row) {
                return null;
            }
            
            return $this->mapRowToObject($row);
            
        } catch (Exception $e) {
            echo '<br/>ERROR en ResultadoDAO getById(): ' . $e->getMessage();
            return null;
        }
    }

    public function getByParticipanteId(int $participanteId): array
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM resultados WHERE participante_id = ? ORDER BY numero_partida ASC");
            $stmt->execute([$participanteId]);
            $result = [];
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $resultado = $this->mapRowToObject($row);
                if ($resultado) {
                    $result[] = $resultado;
                }
            }
            
            return $result;
            
        } catch (Exception $e) {
            echo '<br/>ERROR en ResultadoDAO getByParticipanteId(): ' . $e->getMessage();
            return [];
        }
    }

    public function getAll(): array
    {
        try {
            $stmt = $this->pdo->query("SELECT * FROM resultados ORDER BY fecha_partida DESC, id DESC");
            $result = [];
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $resultado = $this->mapRowToObject($row);
                if ($resultado) {
                    $result[] = $resultado;
                }
            }
            
            return $result;
            
        } catch (Exception $e) {
            echo '<br/>ERROR en ResultadoDAO getAll(): ' . $e->getMessage();
            return [];
        }
    }

    public function update(Resultado $r): bool
    {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE resultados
                SET participante_id = ?, username = ?, color = ?, elo = ?, rival = ?, color_rival = ?, elo_rival = ?,
                    resultado = ?, resultado_desc = ?, fecha_partida = ?, url_partida = ?, numero_partida = ?, fecha_modificacion = ?
                WHERE id = ?
            ");
            
            return $stmt->execute([
                $r->participanteId,
                $r->username,
                $r->color,
                $r->elo,
                $r->rival,
                $r->colorRival,
                $r->eloRival,
                $r->resultado,
                $r->resultadoDesc ?? '',
                $r->fechaPartida ?? date('Y-m-d H:i:s'),
                $r->urlPartida ?? '',
                $r->numeroPartida ?? 1,
                date('Y-m-d H:i:s'), // fecha_modificacion siempre actual
                $r->id
            ]);
            
        } catch (Exception $e) {
            echo '<br/>ERROR en ResultadoDAO update(): ' . $e->getMessage();
            return false;
        }
    }

    public function delete(int $id): bool
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM resultados WHERE id = ?");
            return $stmt->execute([$id]);
            
        } catch (Exception $e) {
            echo '<br/>ERROR en ResultadoDAO delete(): ' . $e->getMessage();
            return false;
        }
    }

    /**
     * Obtiene los resultados por participante (versión mejorada)
     */
    public function getResultadosPorParticipante($participanteId): array
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM resultados WHERE participante_id = ? ORDER BY fecha_partida DESC, numero_partida ASC");
            $stmt->execute([$participanteId]);
            $result = [];
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $resultado = $this->mapRowToObject($row);
                if ($resultado) {
                    $result[] = $resultado;
                }
            }
            
            return $result;
            
        } catch (Exception $e) {
            echo '<br/>ERROR en ResultadoDAO getResultadosPorParticipante(): ' . $e->getMessage();
            return [];
        }
    }

    /**
     * Verificar si existe un resultado por URL
     */
    public function existsByUrl(string $url): bool
    {
        try {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM resultados WHERE url_partida = ?");
            $stmt->execute([$url]);
            $count = $stmt->fetchColumn();
            return $count > 0;
        } catch (Exception $e) {
            echo '<br/>ERROR en ResultadoDAO existsByUrl(): ' . $e->getMessage();
            return false;
        }
    }

    /**
     * Obtiene los resultados por el nickname/username del participante
     */
    public function getResultadosPorParticipanteNickName(string $nickName): array
    {
        try {
            //echo '<br/>DEBUG DAO: Buscando resultados por nickname: ' . $nickName;
            
            $stmt = $this->pdo->prepare("SELECT * FROM resultados WHERE username = ? ORDER BY fecha_partida DESC, numero_partida ASC");
            $stmt->execute([$nickName]);
            $result = [];
            
            $contador = 0;
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $resultado = $this->mapRowToObject($row);
                if ($resultado) {
                    $result[] = $resultado;
                    $contador++;
                }
            }

            //echo '<br/>DEBUG DAO: ✓ Encontrados ' . $contador . ' resultados para nickname: ' . $nickName;

            return $result;
            
        } catch (Exception $e) {
            echo '<br/>ERROR en ResultadoDAO getResultadosPorParticipanteNickName(): ' . $e->getMessage();
            echo '<br/>  - Nickname buscado: ' . $nickName;
            return [];
        }
    }

    /**
     * Obtener el último número de partida para un participante (número máximo actual)
     */
    public function obtenerUltimoNumeroPartida(int $participanteId): int
    {
        try {
            echo '<br/>DEBUG SERVICE: Obteniendo último número de partida para participante_id=' . $participanteId;
            
            // Obtener el número máximo actual
            $stmt = $this->pdo->prepare("SELECT MAX(numero_partida) as max_numero FROM resultados WHERE participante_id = ?");
            $stmt->execute([$participanteId]);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $ultimoNumero = (int)($resultado['max_numero'] ?? 0);
            
            echo '<br/>DEBUG SERVICE: Último número de partida: ' . $ultimoNumero;
            
            return $ultimoNumero;
            
        } catch (Exception $e) {
            echo '<br/>ERROR en ResultadosService obtenerUltimoNumeroPartida(): ' . $e->getMessage();
            // En caso de error, devolver 0 como fallback
            return 0;
        }
    }

    /**
     * Mapear una fila de la base de datos a un objeto Resultado
     */
    private function mapRowToObject(array $row): ?Resultado
    {
        try {
            $resultado = new Resultado();
            
            // Establecer datos usando los métodos de la clase
            $resultado->setDatosBasicos(
                isset($row['id']) ? (int)$row['id'] : null,
                (int)$row['participante_id']
            );
            
            $resultado->setDatosUsuario(
                $row['username'] ?? '',
                $row['color'] ?? 'white',
                (int)($row['elo'] ?? 0)
            );
            
            $resultado->setDatosRival(
                $row['rival'] ?? '',
                $row['color_rival'] ?? 'black',
                (int)($row['elo_rival'] ?? 0)
            );
            
            $resultado->setDatosPartida(
                $row['resultado'] ?? '0',
                $row['resultado_desc'] ?? '',
                $row['fecha_partida'] ?? date('Y-m-d H:i:s'),
                $row['url_partida'] ?? '',
                (int)($row['numero_partida'] ?? 1)
            );
            
            return $resultado;
            
        } catch (Exception $e) {
            echo '<br/>ERROR en ResultadoDAO mapRowToObject(): ' . $e->getMessage();
            echo '<br/>  - Datos de la fila: ' . print_r($row, true);
            return null;
        }
    }

    /**
     * Método de test para verificar la conexión
     */
    public function testConnection(): void
    {
        try {
            echo '<br/>TESTING ResultadoDAO: Verificando conexión...';
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM resultados");
            $count = $stmt->fetchColumn();
            echo '<br/>✓ Conexión exitosa - Registros en resultados: ' . $count;
        } catch (Exception $e) {
            echo '<br/>ERROR en test de conexión ResultadoDAO: ' . $e->getMessage();
        }
    }

    /**
     * Debug: Mostrar estructura de tabla
     */
    public function debugTableStructure(): void
    {
        try {
            echo '<br/>DEBUG: Estructura de tabla resultados:';
            $stmt = $this->pdo->query("DESCRIBE resultados");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<br/>  - ' . $row['Field'] . ' (' . $row['Type'] . ')' . 
                     ($row['Null'] === 'YES' ? ' NULL' : ' NOT NULL') .
                     ($row['Key'] === 'PRI' ? ' PRIMARY KEY' : '') .
                     ($row['Extra'] ? ' ' . $row['Extra'] : '');
            }
        } catch (Exception $e) {
            echo '<br/>ERROR al mostrar estructura: ' . $e->getMessage();
        }
    }
}