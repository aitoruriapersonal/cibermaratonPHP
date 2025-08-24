<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\util\Utils.php

class Utils
{
    // Constantes para prefijos de nick
    public const NICK_UPV = '23UPV';
    public const NICK_UNI = '23UNI';
    public const NICK_FED = '23FED';

    /**
     * Elimina tildes y reemplaza ñ/Ñ por n/N en una cadena.
     */
    public static function quitarTildesYN(string $cadena): string
    {
        $originales =    ['á','é','í','ó','ú','Á','É','Í','Ó','Ú','ñ','Ñ', 'ü', 'Ü'];
        $reemplazos =    ['a','e','i','o','u','A','E','I','O','U','n','N', 'u', 'U'];
        return str_replace($originales, $reemplazos, $cadena);
    }

    /**
     * Convierte un nombre compuesto a CamelCase, sin espacios, tildes ni ñ/Ñ.
     */
    public static function nombreToCamelCase(string $nombre): string
    {
        $nombre = self::quitarTildesYN($nombre);
        $palabras = preg_split('/\s+/', strtolower($nombre));
        $camel = '';
        foreach ($palabras as $palabra) {
            $camel .= ucfirst($palabra);
        }
        return $camel;
    }

    /**
     * Devuelve el prefijo de nick según el tipo.
     */
    public static function getNickPrefijo(string $tipo): string
    {
        return match (strtoupper($tipo)) {
            'UPV' => self::NICK_UPV,
            'UNI' => self::NICK_UNI,
            'FED' => self::NICK_FED,
            default => '23XXX'
        };
    }
}