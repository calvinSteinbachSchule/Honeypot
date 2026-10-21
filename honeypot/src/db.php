<?php

class Database
{
    private static ?PDO $pdo = null;

    public static function connection(): PDO
    {
        if (self::$pdo === null) {
            $host = "db";
            $db = getenv("MARIADB_DATABASE");
            $user = getenv("MARIADB_USER");
            $pass = getenv("MARIADB_PASSWORD");
            $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

            self::$pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        }
        return self::$pdo;
    }

    // Für Tests: eine andere Verbindung einsetzen
    public static function setConnection(PDO $pdo): void
    {
        self::$pdo = $pdo;
    }
}
