<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\util\Database.php

class Database
{
    private static ?PDO $connection = null;

    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            $host = 'localhost';
            $dbname = 'tu_bd';
            $user = 'usuario';
            $pass = 'password';
            $charset = 'utf8mb4';

            $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_PERSISTENT         => true,
            ];

            self::$connection = new PDO($dsn, $user, $pass, $options);
        }
        return self::$connection;
    }

    public static function closeConnection(): void
    {
        self::$connection = null;
    }
}