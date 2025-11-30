<?php
// filepath: c:\xampp\htdocs\deporteuniversitario\ajedrez\backend\servicio\ChesscomPlayerGameService.php

require_once __DIR__ . '/../dao/ChesscomPlayerGameDAO.php';

class ChesscomPlayerGameService
{
    private ChesscomPlayerGameDAO $dao;

    public function __construct(PDO $pdo)
    {
        $this->dao = new ChesscomPlayerGameDAO($pdo);
    }

    public function crearGame(ChesscomPlayerGame $game, $username): int
    {
        return $this->dao->create($game, $username);
    }

    public function obtenerGamePorId(int $id): ?ChesscomPlayerGame
    {
        return $this->dao->getById($id);
    }

    public function obtenerGamesPorParticipanteId(int $participante_id): array
    {
        return $this->dao->obtenerGamesPorParticipanteId($participante_id);
    }
    public function obtenerGamesPorUsername(string $username): array
    {
        return $this->dao->obtenerGamesPorUsername($username);
    }

    public function obtenerTodos(): array
    {
        return $this->dao->getAll();
    }

    public function actualizarGame(ChesscomPlayerGame $game): bool
    {
        return $this->dao->update($game);
    }

    public function eliminarGame(int $id): bool
    {
        return $this->dao->delete($id);
    }

    /**
     * NUEVO: Obtener cantidad de partidas existentes por participante
     */
    public function contarGamesPorParticipante(int $participante_id): int
    {
        try {
            $games = $this->dao->obtenerGamesPorParticipanteId($participante_id);
            $cantidad = count($games);
            echo '<br/>DEBUG SERVICE: Partidas existentes para participante_id=' . $participante_id . ': ' . $cantidad;
            return $cantidad;
        } catch (Exception $e) {
            echo '<br/>ERROR en ChesscomPlayerGameService contarGamesPorParticipante(): ' . $e->getMessage();
            return 0;
        }
    }

    /**
     * NUEVO: Obtener URLs de partidas existentes por participante
     */
    public function obtenerUrlsExistentesPorParticipante(int $participante_id): array
    {
        try {
            $games = $this->dao->obtenerGamesPorParticipanteId($participante_id);
            $urls = [];
            
            foreach ($games as $game) {
                if (property_exists($game, 'url') && !empty($game->url)) {
                    $urls[] = $game->url;
                }
            }
            
            echo '<br/>DEBUG SERVICE: URLs existentes para participante_id=' . $participante_id . ': ' . count($urls);
            return $urls;
        } catch (Exception $e) {
            echo '<br/>ERROR en ChesscomPlayerGameService obtenerUrlsExistentesPorParticipante(): ' . $e->getMessage();
            return [];
        }
    }

    /**
     * Verificar si existe una partida por URL
     */
    public function existeGamePorUrl(string $url): bool
    {
        try {
            // Usar el DAO para verificar existencia
            return $this->dao->existsByUrl($url);
        } catch (Exception $e) {
            echo '<br/>ERROR en ChesscomPlayerGameService existeGamePorUrl(): ' . $e->getMessage();
            return false;
        }
    }
}