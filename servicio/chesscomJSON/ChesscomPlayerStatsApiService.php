<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\servicio\ChesscomPlayerStatsApiService.php

require_once __DIR__ . '/../../modelo/chesscomJSON/ChesscomPlayerStatsApiModel.php';

// Verificar si allow_url_fopen está habilitado
if (!ini_get('allow_url_fopen')) {
    echo 'allow_url_fopen está deshabilitado';
}

// Verificar si cURL está disponible
if (!function_exists('curl_init')) {
    echo 'cURL no está instalado';
}

class ChesscomPlayerStatsApiService
{
    public function fetchPlayerStats(string $username, int $participante_id): ?ChesscomPlayerStatsApiModel
    {
        $url = "https://api.chess.com/pub/player/" . urlencode($username) . "/stats";
        
        // Usar cURL (más robusto)
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
        //echo '<br/> JSON: ' . $json;
        
        if ($json === false || $httpCode !== 200) {
            return null;
        }
        
        $data = json_decode($json, true);
       // echo '<br/> DATA: ' . $json;
        if (!is_array($data)) {
            return null;
        }
        echo '<br/> Conversion: ';
        return ChesscomPlayerStatsApiModel::fromApiJson($data, $participante_id);
    }
}