<?php
require_once '../autoload.php'; // Ajusta la ruta

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

$todayLog = date("Ymd") . ".log";
$jugadorAAnalizar = "aitoruriaerandio";
$profileEndpoint = "https://api.chess.com/pub/player/" . $jugadorAAnalizar;

// Crear el fichero Log
$fileDescriptor = fopen($todayLog, "a+");
fwrite($fileDescriptor, "Inicia el proceso de Analisis\n");

// Comienza el analisis
$textoLog = "Jugador a analizar: " . $jugadorAAnalizar . "\n";
fwrite($fileDescriptor, $textoLog);

$textoLog = "ProfileEndpoint: " . $profileEndpoint . "\n";
fwrite($fileDescriptor, $textoLog);

$client = new Client();

try {
    $response = $client->get($profileEndpoint);
    $responseContent = $response->getBody()->getContents();
    $textoLog = "ProfileEndpointResponse: \n" . $responseContent . "\n";
    fwrite($fileDescriptor, $textoLog);
} catch (RequestException $e) {
    $textoLog = "Error al consultar el endpoint: " . $e->getMessage() . "\n";
    fwrite($fileDescriptor, $textoLog);
}

// Termina el analisis y se cierra el fichero
fwrite($fileDescriptor, "Termina el proceso de Analisis\n");
fclose($fileDescriptor);
?>