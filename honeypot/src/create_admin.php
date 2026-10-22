<?php

// Legt einen Dashboard-Benutzer an (Passwort wird als Hash gespeichert).
// Aufruf im Container:
//   docker compose exec php php /var/www/src/create_admin.php <benutzername> <passwort>

if (PHP_SAPI !== "cli") {
    exit(1);
}

require_once __DIR__ . "/db.php";

if ($argc !== 3 || strlen($argv[2]) < 8) {
    fwrite(STDERR, "Aufruf: php create_admin.php <benutzername> <passwort (mind. 8 Zeichen)>\n");
    exit(1);
}

$stmt = Database::connection()->prepare(
    "INSERT INTO admin (benutzername, passworthash) VALUES (:b, :h)
     ON DUPLICATE KEY UPDATE passworthash = VALUES(passworthash)"
);
$stmt->execute([":b" => $argv[1], ":h" => password_hash($argv[2], PASSWORD_DEFAULT)]);

echo "Admin '{$argv[1]}' angelegt bzw. Passwort aktualisiert.\n";
