<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\JsonToChesscomPlayerProfile.php

require_once 'ChesscomPlayerProfile.php';

class JsonToChesscomPlayerProfile
{
    /**
     * Transforma un string JSON en una instancia de ChesscomPlayerProfile.
     *
     * @param string $jsonString
     * @return ChesscomPlayerProfile|null
     */
    public static function fromJson(string $jsonString): ?ChesscomPlayerProfile
    {
        $data = json_decode($jsonString, true);
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
            return null;
        }

        // Ajusta los nombres de las claves si es necesario según la definición de ChesscomPlayerProfile
        return ChesscomPlayerProfile::fromArray($data);
    }
}