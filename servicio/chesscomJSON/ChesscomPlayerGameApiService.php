<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\servicio\ChesscomPlayerGameApiService.php

require_once __DIR__ . '/../../modelo/chesscomJSON/ChesscomPlayerGameApiModel.php';

// Verificar si allow_url_fopen está habilitado
if (!ini_get('allow_url_fopen')) {
    echo 'allow_url_fopen está deshabilitado';
}

// Verificar si cURL está disponible
if (!function_exists('curl_init')) {
    echo 'cURL no está instalado';
}

class ChesscomPlayerGameApiService
{
    /**
     * Devuelve un array de ChesscomPlayerGameApiModel
     */
    public function fetchGames(string $username): array
    {
        $url = "https://api.chess.com/pub/player/" . urlencode($username) . "/games";
        
        // Usar cURL (más robusto) - MISMO PATRÓN QUE PROFILE Y STATS
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; PHP Chess App)');
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $json = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        echo '<br/> URL: ' . $url;
        echo '<br/> HTTP Code: ' . $httpCode;
        echo '<br/> cURL Error: ' . $error;
        
        if ($json === false || $httpCode !== 200) {
            return [];
        }
        
        $data = json_decode($json, true);
        if (!is_array($data) || !isset($data['games'])) {
            return [];
        }
        
        // Usar createMultipleFromGames para crear objetos individuales
        return ChesscomPlayerGameApiModel::createMultipleFromGames($data['games'], 0);
    }

    /**
     * Devuelve un array de ChesscomPlayerGameApiModel
     * @param string $username - nombre de usuario de chess.com
     * @param string $ritmo - formato "600/0" (10 min + 0 seg incremento)
     */
    public function fetchGamesPorRitmo(string $username, string $ritmo): array
    {
        // Convertir formato "10+0" a "600/0" si es necesario
        if (strpos($ritmo, '+') !== false) {
            $parts = explode('+', $ritmo);
            $minutos = (int)$parts[0];
            $incremento = (int)$parts[1];
            $ritmo = ($minutos * 60) . '/' . $incremento;
        }
        
        // URL corregida con formato /games/live/tiempo/incremento
        $url = "https://api.chess.com/pub/player/" . urlencode($username) . "/games/live/" . $ritmo;
        
        // Usar cURL (más robusto) - MISMO PATRÓN QUE PROFILE Y STATS
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; PHP Chess App)');
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $json = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        echo '<br/> URL: ' . $url;
        echo '<br/> HTTP Code: ' . $httpCode;
        echo '<br/> cURL Error: ' . $error;
        
        if ($json === false || $httpCode !== 200) {
            echo '<br/> ERROR: No se pudo obtener datos de la API';
            return [];
        }
        
        $data = json_decode($json, true);
        
        // DEBUG: Mostrar estructura del JSON
        echo '<br/> DEBUG JSON estructura:';
        echo '<br/> - Tiene "games": ' . (isset($data['games']) ? 'SÍ' : 'NO');
        if (isset($data['games'])) {
            echo '<br/> - Número de partidas: ' . count($data['games']);
            if (!empty($data['games'])) {
                echo '<br/> - Primera partida URL: ' . ($data['games'][0]['url'] ?? 'NULL');
                echo '<br/> - Claves primera partida: ' . implode(', ', array_keys($data['games'][0]));
            }
        } else {
            echo '<br/> - Estructura JSON: ' . json_encode(array_keys($data));
        }
        
        if (!is_array($data) || !isset($data['games'])) {
            echo '<br/> ERROR: Estructura JSON incorrecta';
            return [];
        }
        
        // USAR createMultipleFromGames en lugar de fromApiJson
        $result = ChesscomPlayerGameApiModel::createMultipleFromGames($data['games'], 0);
        
        // DEBUG: Verificar URLs creadas
        echo '<br/> DEBUG: Partidas creadas (' . count($result) . '):';
        foreach ($result as $i => $gameModel) {
            echo '<br/> - Partida ' . ($i + 1) . ' URL: ' . ($gameModel->url ?? 'NULL');
            echo ' | PGN: ' . (strlen($gameModel->pgn ?? '') > 50 ? substr($gameModel->pgn, 0, 50) . '...' : ($gameModel->pgn ?? 'NULL'));
        }
        
        // DEBUG: Verificar datos completos de las partidas creadas
        echo '<br/> DEBUG: Partidas creadas (' . count($result) . '):';
        foreach ($result as $i => $gameModel) {
            echo '<br/> - Partida ' . ($i + 1) . ':';
            echo '<br/>   URL: ' . ($gameModel->url ?? 'NULL');
            echo '<br/>   White: ' . ($gameModel->white_username ?? 'NULL') . ' (' . ($gameModel->white_rating ?? 'NULL') . ')';
            echo '<br/>   Black: ' . ($gameModel->black_username ?? 'NULL') . ' (' . ($gameModel->black_rating ?? 'NULL') . ')';
            echo '<br/>   Accuracy White: ' . ($gameModel->accuracy_white ?? 'NULL');
            echo '<br/>   Accuracy Black: ' . ($gameModel->accuracy_black ?? 'NULL');
            echo '<br/>   White UUID: ' . ($gameModel->white_uuid ?? 'NULL');
            echo '<br/>   Black UUID: ' . ($gameModel->black_uuid ?? 'NULL');
            echo '<br/>   Time Control: ' . ($gameModel->time_control ?? 'NULL');
            echo '<br/>   Time Class: ' . ($gameModel->time_class ?? 'NULL');
        }
        
        return $result;
    }

    /**
     * Método alternativo para buscar por tipo de tiempo (bullet, blitz, rapid, etc.)
     */
    public function fetchGamesPorTipoTiempo(string $username, string $tipoTiempo): array
    {
        // Validar tipos permitidos
        $tiposValidos = ['bullet', 'blitz', 'rapid', 'daily'];
        if (!in_array(strtolower($tipoTiempo), $tiposValidos)) {
            echo "<br/>Tipo de tiempo no válido: $tipoTiempo. Permitidos: " . implode(', ', $tiposValidos);
            return [];
        }
        
        $url = "https://api.chess.com/pub/player/" . urlencode($username) . "/games/" . strtolower($tipoTiempo);
        
        // Usar cURL (más robusto) - MISMO PATRÓN QUE PROFILE Y STATS
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; PHP Chess App)');
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $json = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        echo '<br/> URL: ' . $url;
        echo '<br/> HTTP Code: ' . $httpCode;
        echo '<br/> cURL Error: ' . $error;
        
        if ($json === false || $httpCode !== 200) {
            return [];
        }
        
        $data = json_decode($json, true);
        if (!is_array($data) || !isset($data['games'])) {
            return [];
        }
        
        return ChesscomPlayerGameApiModel::createMultipleFromGames($data['games'], 0);
    }

    public function fetchPlayerGames(string $username, string $year, string $month, int $participante_id): ?ChesscomPlayerGameApiModel
    {
        $url = "https://api.chess.com/pub/player/" . urlencode($username) . "/games/" . $year . "/" . $month;
        
        // Usar cURL (más robusto) - MISMO PATRÓN QUE PROFILE Y STATS
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; PHP Chess App)');
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $json = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        echo '<br/> URL: ' . $url;
        echo '<br/> HTTP Code: ' . $httpCode;
        echo '<br/> cURL Error: ' . $error;
        
        if ($json === false || $httpCode !== 200) {
            return null;
        }
        
        $data = json_decode($json, true);
        if (!is_array($data) || !isset($data['games'])) {
            return null;
        }
        echo '<br/> Conversion: ';
        return ChesscomPlayerGameApiModel::fromApiJson($data, $participante_id);
    }

    /**
     * Método mejorado para obtener múltiples partidas como objetos separados
     */
    public function fetchPlayerGamesAsArray(string $username, string $year, string $month, int $participante_id): array
    {
        $url = "https://api.chess.com/pub/player/" . urlencode($username) . "/games/" . $year . "/" . $month;
        
        // Usar cURL (más robusto) - MISMO PATRÓN QUE PROFILE Y STATS
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; PHP Chess App)');
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $json = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        echo '<br/> URL: ' . $url;
        echo '<br/> HTTP Code: ' . $httpCode;
        echo '<br/> cURL Error: ' . $error;
        
        if ($json === false || $httpCode !== 200) {
            return [];
        }
        
        $data = json_decode($json, true);
        if (!is_array($data) || !isset($data['games'])) {
            return [];
        }
        
        echo '<br/> Conversion: ' . count($data['games']) . ' partidas encontradas';
        return ChesscomPlayerGameApiModel::createMultipleFromGames($data['games'], $participante_id);
    }
}