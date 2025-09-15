<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\conversores\ChesscomGameApiModelToResultadoConverter.php

require_once __DIR__ . '/../modelo/Resultado.php';
require_once __DIR__ . '/../modelo/chesscomJSON/ChesscomPlayerGameApiModel.php';

class ChesscomGameApiModelToResultadoBdConverter
{
    public static function toResultado(ChesscomPlayerGameApiModel $gameApiModel, Participante $participante): Resultado
    {
        $color = null;
        $colorRival = null;
        $elo = 0;
        $rival = "";
        $eloRival = 0;
        $resultado = null;
        $resultadoDesc = null;
        $gameEnd = date('Y-m-d H:i:s', $gameApiModel->end_time ?? time());

        if($gameApiModel->white['username'] === $participante->nick){
            $color = 'white';
            $colorRival = 'black';
            $elo = $gameApiModel->white['rating'] ?? 0;
            $rival = $gameApiModel->black['username'] ?? '';
            $eloRival = $gameApiModel->black['rating'] ?? 0;
            $resultado = $gameApiModel->white['result'] ?? 'unknown';
            if ($gameApiModel->white['result'] === 'win') {
                $resultadoDesc = 'Victoria';
            } elseif ($gameApiModel->white['result'] === 'loss') {
                $resultadoDesc = 'Derrota';
            } else {
                $resultadoDesc = 'Empate';
            }
        }else{
            $color = 'black';
            $colorRival = 'white';
            $elo = $gameApiModel->black['rating'] ?? 0;
            $rival = $gameApiModel->white['username'] ?? '';
            $eloRival = $gameApiModel->white['rating'] ?? 0;
            $resultado = $gameApiModel->black['result'] ?? 'unknown';
            if ($gameApiModel->black['result'] === 'win') {
                $resultadoDesc = 'Victoria';
            } elseif ($gameApiModel->black['result'] === 'loss') {
                $resultadoDesc = 'Derrota';
            } else {
                $resultadoDesc = 'Empate';
            }
        }
        return new Resultado(
            null, // id autoincremental
            $participante->id,
            $participante->nick,
            $color,
            $elo,
            $rival,
            $colorRival,
            $eloRival,
            $resultado,
            $resultadoDesc,
            $gameEnd,
            $gameApiModel->url ?? null,
            0, // numero_partida (se puede actualizar después si es necesario)
            date('Y-m-d H:i:s'),
            null
        );
    }
}