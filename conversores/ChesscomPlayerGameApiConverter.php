<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\conversores\ChesscomPlayerGameApiConverter.php

require_once __DIR__ . '/../modelo/chesscomJSON/ChesscomPlayerGameApiModel.php';
require_once __DIR__ . '/../modelo/ChesscomPlayerGame.php';

class ChesscomPlayerGameApiConverter
{
    // Método principal que usa el bot
    public function convert(ChesscomPlayerGameApiModel $apiModel, $participanteId, $username): ?ChesscomPlayerGame
    {
        echo '<br/>DEBUG: Iniciando conversión con nuevo patrón ChesscomPlayerGame';
        
        try {
            // Crear con constructor vacío
            $chesscomPlayerGame = new ChesscomPlayerGame();
            echo '<br/>DEBUG: ✓ Constructor vacío creado (fechaAlta=' . $chesscomPlayerGame->fechaAlta . ', fechaMod=' . ($chesscomPlayerGame->fechaModificacion ?? 'NULL') . ')';
            
            // Establecer datos por grupos
            $chesscomPlayerGame->setDatosGenerales(
                0, // gameId autoincremental
                $participanteId,
                $username, // AÑADIR ESTA LÍNEA
                $apiModel->url,
                $apiModel->pgn
            );
            echo '<br/>DEBUG: ✓ Datos generales establecidos';
            
            $chesscomPlayerGame->setDatosBlancas(
                $apiModel->white_username,
                $apiModel->white_rating,
                $apiModel->white_result,
                $apiModel->accuracy_white,
                $apiModel->white_uuid
            );
            echo '<br/>DEBUG: ✓ Datos blancas establecidos';
            
            $chesscomPlayerGame->setDatosNegras(
                $apiModel->black_username,
                $apiModel->black_rating,
                $apiModel->black_result,
                $apiModel->accuracy_black,
                $apiModel->black_uuid
            );
            echo '<br/>DEBUG: ✓ Datos negras establecidos';
            
            $chesscomPlayerGame->setDatosPartida(
                $apiModel->time_control,
                $apiModel->time_class,
                $apiModel->rules,
                $apiModel->rated,
                $apiModel->end_time,
                $apiModel->start_time,
                $apiModel->eco,
                $apiModel->tournament,
                $apiModel->match
            );
            echo '<br/>DEBUG: ✓ Datos partida establecidos';
            
            // IMPORTANTE: Finalizar la configuración inicial
            $chesscomPlayerGame->finalizarConfiguracion();
            echo '<br/>DEBUG: ✓ Configuración inicial finalizada (fechaAlta=' . $chesscomPlayerGame->fechaAlta . ', fechaMod=' . ($chesscomPlayerGame->fechaModificacion ?? 'NULL') . ')';
            
            // Validar
            $errores = $chesscomPlayerGame->validar();
            if (!empty($errores)) {
                echo '<br/>WARNING: Objeto incompleto: ' . implode(', ', $errores);
            }
            
            echo '<br/>DEBUG: ✓ Conversión exitosa - ' . $chesscomPlayerGame;
            return $chesscomPlayerGame;
            
        } catch (Exception $e) {
            echo '<br/>ERROR FATAL en conversión: ' . $e->getMessage();
            return null;
        } catch (Error $e) {
            echo '<br/>ERROR FATAL (Error) en conversión: ' . $e->getMessage();
            return null;
        }
    }

    // Método adicional para verificar el constructor de ChesscomPlayerGame
    public function testChesscomPlayerGameConstructor(): void
    {
        echo '<br/>TESTING: Probando nuevo patrón ChesscomPlayerGame...';
        
        try {
            // Verificar que la clase existe
            if (!class_exists('ChesscomPlayerGame')) {
                echo '<br/>✗ ERROR: Clase ChesscomPlayerGame no existe';
                return;
            }
            echo '<br/>✓ Clase ChesscomPlayerGame existe';
            
            // Probar constructor vacío
            $test = new ChesscomPlayerGame();
            echo '<br/>✓ Constructor vacío funciona';
            
            // Probar métodos de configuración por grupos
            $test->setDatosGenerales(0, 1, 'https://test.com', '[Event "Test"]')
                 ->setDatosBlancas('player1', 1500, 'win', 95.5, 'uuid1')
                 ->setDatosNegras('player2', 1400, 'resigned', 89.2, 'uuid2')
                 ->setDatosPartida('600', 'rapid', 'chess', 1, 1234567890, 1234567880, 'A00');
            
            echo '<br/>✓ Métodos de configuración por grupos funcionan';
            echo '<br/>✓ Resultado del test: ' . $test;
            
            // Validar
            $errores = $test->validar();
            if (empty($errores)) {
                echo '<br/>✓ Validación exitosa';
            } else {
                echo '<br/>⚠ Errores de validación: ' . implode(', ', $errores);
            }
            
        } catch (Exception $e) {
            echo '<br/>ERROR en test: ' . $e->getMessage();
        } catch (Error $e) {
            echo '<br/>ERROR FATAL en test: ' . $e->getMessage();
        }
    }

    // Método para debug - verificar datos antes de conversión
    public function debugApiModel(ChesscomPlayerGameApiModel $apiModel): void
    {
        echo '<br/>DEBUG - ChesscomPlayerGameApiModel:';
        echo '<br/>  - participante_id: ' . ($apiModel->participante_id ?? 'NULL');
        echo '<br/>  - url: ' . ($apiModel->url ?? 'NULL');
        echo '<br/>  - pgn length: ' . strlen($apiModel->pgn ?? '');
        echo '<br/>  - white_username: ' . ($apiModel->white_username ?? 'NULL');
        echo '<br/>  - white_rating: ' . ($apiModel->white_rating ?? 'NULL');
        echo '<br/>  - white_result: ' . ($apiModel->white_result ?? 'NULL');
        echo '<br/>  - black_username: ' . ($apiModel->black_username ?? 'NULL');
        echo '<br/>  - black_rating: ' . ($apiModel->black_rating ?? 'NULL');
        echo '<br/>  - black_result: ' . ($apiModel->black_result ?? 'NULL');
        echo '<br/>  - time_control: ' . ($apiModel->time_control ?? 'NULL');
        echo '<br/>  - time_class: ' . ($apiModel->time_class ?? 'NULL');
        echo '<br/>  - end_time: ' . ($apiModel->end_time ?? 'NULL');
    }
}