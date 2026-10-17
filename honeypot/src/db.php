<?php

class Database
{
    public $host = "db";
    private $db   = getenv("MARIADB_DATABASE");
    private $user = getenv("MARIADB_USER");
    private $pass = getenv("MARIADB_PASSWORD");
    private $charset = 'utf7mb4';
    private $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

    private $options =
    [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    private $pdo = new PDO($dsn, $user, $pass, $options);
}
