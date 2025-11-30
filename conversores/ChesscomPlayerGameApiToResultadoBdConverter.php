<?php
// filepath: c:\xampp\htdocs\deporteuniversitario\ajedrez\backend\conversores\ChesscomPlayerGameApiToResultadoBdConverter.php

require_once __DIR__ . '/../modelo/chesscomJSON/ChesscomPlayerGameApiModel.php';
require_once __DIR__ . '/../modelo/Resultados.php';

class ChesscomPlayerGameApiToResultadoBdConverter
{
    /**
     * Convertir ChesscomPlayerGameApiModel a Resultado
     */
    public function convert(ChesscomPlayerGameApiModel $apiModel, string $username): ?Resultado
    {
        echo '<br/>DEBUG RESULTADO CONVERTER: Iniciando conversión de API a Resultado';
        echo '<br/>DEBUG RESULTADO: Username recibido: ' . $username;
        
        try {
            // Verificar que el modelo API tiene datos básicos
            if (!$apiModel) {
                echo '<br/>ERROR RESULTADO: apiModel es null';
                return null;
            }
            
            echo '<br/>DEBUG RESULTADO: Verificando datos del apiModel...';
            echo '<br/>  - URL: ' . ($apiModel->url ?? 'NULL');
            echo '<br/>  - White username: ' . ($apiModel->white_username ?? 'NULL');
            echo '<br/>  - Black username: ' . ($apiModel->black_username ?? 'NULL');
            echo '<br/>  - White result: ' . ($apiModel->white_result ?? 'NULL');
            echo '<br/>  - Black result: ' . ($apiModel->black_result ?? 'NULL');
            echo '<br/>  - End time: ' . ($apiModel->end_time ?? 'NULL');
            
            // Verificar que la clase Resultado existe
            if (!class_exists('Resultado')) {
                echo '<br/>ERROR RESULTADO: Clase Resultado no existe';
                return null;
            }
            
            // Determinar si el usuario es blancas o negras
            $esBlancas = ($apiModel->white_username === $username);
            $esNegras = ($apiModel->black_username === $username);
            
            echo '<br/>DEBUG RESULTADO: ¿Es blancas?: ' . ($esBlancas ? 'SÍ' : 'NO');
            echo '<br/>DEBUG RESULTADO: ¿Es negras?: ' . ($esNegras ? 'SÍ' : 'NO');
            
            if (!$esBlancas && !$esNegras) {
                echo '<br/>ERROR RESULTADO: El username "' . $username . '" no participa en esta partida';
                echo '<br/>  - Blancas: ' . ($apiModel->white_username ?? 'NULL');
                echo '<br/>  - Negras: ' . ($apiModel->black_username ?? 'NULL');
                return null;
            }
            
            // Obtener datos del usuario y del oponente
            if ($esBlancas) {
                $miRating = $apiModel->white_rating ?? 0;
                $oponenteUsername = $apiModel->black_username ?? '';
                $oponenteRating = $apiModel->black_rating ?? 0;
                $miColor = 'white';
                $colorRival = 'black';
            } else {
                $miRating = $apiModel->black_rating ?? 0;
                $oponenteUsername = $apiModel->white_username ?? '';
                $oponenteRating = $apiModel->white_rating ?? 0;
                $miColor = 'black';
                $colorRival = 'white';
            }
            
            echo '<br/>DEBUG RESULTADO: Datos extraídos:';
            echo '<br/>  - Mi rating: ' . $miRating;
            echo '<br/>  - Mi color: ' . $miColor;
            echo '<br/>  - Oponente: ' . $oponenteUsername;
            echo '<br/>  - Rating oponente: ' . $oponenteRating;
            echo '<br/>  - Color rival: ' . $colorRival;
            
            // Mapear resultado usando la nueva lógica
            $resultadoData = $this->mapearResultado($apiModel, $username, $esBlancas);
            $resultado = $resultadoData['resultado'];
            $resultadoDesc = $resultadoData['descripcion'];
            
            echo '<br/>DEBUG RESULTADO: Resultado calculado: ' . $resultado . ' (' . $resultadoDesc . ')';
            
            // Convertir timestamp a fecha
            $fechaPartida = null;
            if ($apiModel->end_time) {
                $fechaPartida = date('Y-m-d H:i:s', $apiModel->end_time);
                echo '<br/>DEBUG RESULTADO: Fecha partida: ' . $fechaPartida;
            } else {
                echo '<br/>WARNING RESULTADO: No hay end_time, usando fecha actual';
                $fechaPartida = date('Y-m-d H:i:s');
            }
            
            // Crear objeto Resultado usando los métodos correctos
            echo '<br/>DEBUG RESULTADO: Creando objeto Resultado...';
            $resultadoObj = new Resultado();
            echo '<br/>DEBUG RESULTADO: ✓ Constructor exitoso';
            
            // Establecer datos usando los métodos de la clase
            $resultadoObj->setDatosBasicos(null, 0); // id = null, participanteId se asignará después
            echo '<br/>DEBUG RESULTADO: ✓ Datos básicos establecidos';
            
            $resultadoObj->setDatosUsuario($username, $miColor, $miRating);
            echo '<br/>DEBUG RESULTADO: ✓ Datos usuario establecidos: ' . $username . ', ' . $miColor . ', ' . $miRating;
            
            $resultadoObj->setDatosRival($oponenteUsername, $colorRival, $oponenteRating);
            echo '<br/>DEBUG RESULTADO: ✓ Datos rival establecidos: ' . $oponenteUsername . ', ' . $colorRival . ', ' . $oponenteRating;
            
            $resultadoObj->setDatosPartida(
                $resultado,
                $resultadoDesc,
                $fechaPartida,
                $apiModel->url ?? '',
                0 // numeroPartida - se calculará después
            );
            echo '<br/>DEBUG RESULTADO: ✓ Datos partida establecidos';
            
            // Validar el objeto antes de retornarlo
            $errores = $resultadoObj->validar();
            if (!empty($errores)) {
                echo '<br/>WARNING RESULTADO: Errores de validación:';
                foreach ($errores as $error) {
                    echo '<br/>  - ' . $error;
                }
            } else {
                echo '<br/>DEBUG RESULTADO: ✓ Validación exitosa';
            }
            
            echo '<br/>DEBUG RESULTADO: ✓ Conversión exitosa';
            echo '<br/>DEBUG RESULTADO: Objeto creado: ' . $resultadoObj->__toString();
            
            return $resultadoObj;
            
        } catch (Exception $e) {
            echo '<br/>ERROR FATAL RESULTADO en conversión: ' . $e->getMessage();
            echo '<br/>Línea del error: ' . $e->getLine();
            return null;
        } catch (Error $e) {
            echo '<br/>ERROR FATAL RESULTADO (Error) en conversión: ' . $e->getMessage();
            echo '<br/>Línea del error: ' . $e->getLine();
            return null;
        }
    }

    /**
     * Mapear resultado usando la nueva lógica con formato estándar de ajedrez
     */
    private function mapearResultado(ChesscomPlayerGameApiModel $apiModel, string $username, bool $esBlancas): array
    {
        $whiteResult = strtolower(trim($apiModel->white_result ?? ''));
        $blackResult = strtolower(trim($apiModel->black_result ?? ''));
        
        echo '<br/>DEBUG MAPEO: Analizando resultados - White: "' . $whiteResult . '", Black: "' . $blackResult . '"';
        echo '<br/>DEBUG MAPEO: Usuario "' . $username . '" es ' . ($esBlancas ? 'BLANCAS' : 'NEGRAS');
        
        // Determinar quién ganó la partida
        $blancasGanaron = ($whiteResult === 'win');
        $negrasGanaron = ($blackResult === 'win');
        $esEmpate = (!$blancasGanaron && !$negrasGanaron);
        
        echo '<br/>DEBUG MAPEO: ¿Blancas ganaron?: ' . ($blancasGanaron ? 'SÍ' : 'NO');
        echo '<br/>DEBUG MAPEO: ¿Negras ganaron?: ' . ($negrasGanaron ? 'SÍ' : 'NO');
        echo '<br/>DEBUG MAPEO: ¿Es empate?: ' . ($esEmpate ? 'SÍ' : 'NO');
        
        if ($esEmpate) {
            // Empate/Tablas
            $resultado = '0.5-0.5';
            $descripcion = 'Tablas';
            echo '<br/>DEBUG MAPEO: → TABLAS';
        } elseif ($blancasGanaron) {
            // Las blancas ganaron
            $resultado = '1-0';
            if ($esBlancas) {
                $descripcion = 'Victoria';
                echo '<br/>DEBUG MAPEO: → VICTORIA (usuario era blancas y blancas ganaron)';
            } else {
                $descripcion = 'Derrota';
                echo '<br/>DEBUG MAPEO: → DERROTA (usuario era negras y blancas ganaron)';
            }
        } else {
            // Las negras ganaron
            $resultado = '0-1';
            if ($esBlancas) {
                $descripcion = 'Derrota';
                echo '<br/>DEBUG MAPEO: → DERROTA (usuario era blancas y negras ganaron)';
            } else {
                $descripcion = 'Victoria';
                echo '<br/>DEBUG MAPEO: → VICTORIA (usuario era negras y negras ganaron)';
            }
        }
        
        echo '<br/>DEBUG MAPEO: Resultado final: "' . $resultado . '" - "' . $descripcion . '"';
        
        return [
            'resultado' => $resultado,
            'descripcion' => $descripcion
        ];
    }

    /**
     * DEPRECATED: Usar mapearResultado() en su lugar
     */
    private function mapearResultadoAPuntos(?string $resultado): string
    {
        // Método mantenido por compatibilidad, pero no se usa
        return '0.5-0.5';
    }

    /**
     * DEPRECATED: Usar mapearResultado() en su lugar
     */
    private function mapearResultadoADescripcion(?string $resultado): string
    {
        // Método mantenido por compatibilidad, pero no se usa
        return 'Tablas';
    }

    /**
     * Método para debug - verificar conversión
     */
    public function debugConversion(ChesscomPlayerGameApiModel $apiModel, string $username): void
    {
        echo '<br/>DEBUG RESULTADO CONVERTER - Verificando datos para conversión:';
        echo '<br/>  - API Model class: ' . get_class($apiModel);
        echo '<br/>  - Username: ' . $username;
        echo '<br/>  - URL: ' . ($apiModel->url ?? 'NULL');
        echo '<br/>  - White player: ' . ($apiModel->white_username ?? 'NULL');
        echo '<br/>  - Black player: ' . ($apiModel->black_username ?? 'NULL');
        echo '<br/>  - White result: ' . ($apiModel->white_result ?? 'NULL');
        echo '<br/>  - Black result: ' . ($apiModel->black_result ?? 'NULL');
        echo '<br/>  - End time: ' . ($apiModel->end_time ?? 'NULL');
        
        // Verificar si el username participa en la partida
        $esBlancas = ($apiModel->white_username === $username);
        $esNegras = ($apiModel->black_username === $username);
        echo '<br/>  - ¿Participa como blancas?: ' . ($esBlancas ? 'SÍ' : 'NO');
        echo '<br/>  - ¿Participa como negras?: ' . ($esNegras ? 'SÍ' : 'NO');
        
        if (!$esBlancas && !$esNegras) {
            echo '<br/>  - ⚠ WARNING: El username no participa en esta partida';
        } else {
            // Mostrar resultado que se calcularía
            $resultadoData = $this->mapearResultado($apiModel, $username, $esBlancas);
            echo '<br/>  - Resultado calculado: "' . $resultadoData['resultado'] . '" - "' . $resultadoData['descripcion'] . '"';
        }
        
        // Verificar que la clase Resultado existe
        if (class_exists('Resultado')) {
            echo '<br/>  - ✓ Clase Resultado existe';
            try {
                $test = new Resultado();
                echo '<br/>  - ✓ Constructor Resultado funciona';
                echo '<br/>  - ✓ Métodos disponibles: setDatosBasicos, setDatosUsuario, setDatosRival, setDatosPartida';
                echo '<br/>  - ✓ Validación disponible: validar(), estaCompleto()';
                
            } catch (Exception $e) {
                echo '<br/>  - ✗ Error en constructor Resultado: ' . $e->getMessage();
            }
        } else {
            echo '<br/>  - ✗ Clase Resultado NO existe';
        }
    }
}