<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\util\FormValidator.php

class InscripcionFormValidator extends FormValidator
{
    /**
     * Valida que un campo no esté vacío.
     */
    public static function required(string $value): bool
    {
        return trim($value) !== '';
    }

    /**
     * Valida un email.
     */
    public static function email(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Valida un DNI español simple (8 números y una letra).
     */
    public static function dni(string $dni): bool
    {
        return preg_match('/^\d{8}[A-Za-z]$/', $dni) === 1;
    }

    /**
     * Valida un número de teléfono español (9 dígitos o con prefijo internacional).
     */
    public static function telefono(string $telefono): bool
    {
        return preg_match('/^(\+34)?\d{9}$/', $telefono) === 1;
    }

    /**
     * Valida una fecha en formato YYYY-MM-DD.
     */
    public static function fecha(string $fecha): bool
    {
        $d = DateTime::createFromFormat('Y-m-d', $fecha);
        return $d && $d->format('Y-m-d') === $fecha;
    }

    /**
     * Valida que un valor sea un número entero.
     */
    public static function entero($valor): bool
    {
        return filter_var($valor, FILTER_VALIDATE_INT) !== false;
    }

    /**
     * Valida la longitud mínima de un campo.
     */
    public static function minLength(string $value, int $min): bool
    {
        return mb_strlen($value) >= $min;
    }

    /**
     * Valida la longitud máxima de un campo.
     */
    public static function maxLength(string $value, int $max): bool
    {
        return mb_strlen($value) <= $max;
    }
}