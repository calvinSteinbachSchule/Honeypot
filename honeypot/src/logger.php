<?php

require_once __DIR__ . "/db.php";
require_once __DIR__ . "/helpers.php";
require_once __DIR__ . "/erkennung.php";
require_once __DIR__ . "/geo.php";

// Speichert einen Zugriff oder Anmeldeversuch.
// Neue IP-Adressen werden einmalig per Geolocation-API angereichert.
// Fehler beim Speichern werden nur geloggt, die Antwort an den Angreifer
// bleibt davon unberührt.
function protokolliere(string $ip, string $pfad, ?string $benutzername, ?string $passwort, ?string $useragent): void
{
    try {
        $pdo = Database::connection();

        angreiferAnlegen($pdo, $ip);

        $muster = erkenneAngriffsmuster($benutzername, $passwort, $pfad);

        $stmt = $pdo->prepare(
            "INSERT INTO versuch (angreifer, pfad, benutzername, passwort, useragent, angriffsmuster)
             VALUES (:ip, :pfad, :benutzername, :passwort, :useragent, :muster)"
        );
        $stmt->execute([
            ":ip"           => $ip,
            ":pfad"         => kuerzen($pfad),
            ":benutzername" => kuerzen($benutzername),
            ":passwort"     => kuerzen($passwort),
            ":useragent"    => kuerzen($useragent),
            ":muster"       => $muster ? implode(", ", $muster) : null,
        ]);
    } catch (Throwable $e) {
        error_log("Protokollierung fehlgeschlagen: " . $e->getMessage());
    }
}

// Legt die IP an, falls sie neu ist, und fragt dann einmalig Land/Netzbetreiber ab
function angreiferAnlegen(PDO $pdo, string $ip): void
{
    $stmt = $pdo->prepare("SELECT 1 FROM angreifer WHERE ip = :ip");
    $stmt->execute([":ip" => $ip]);
    if ($stmt->fetchColumn() !== false) {
        return;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO angreifer (ip) VALUES (:ip)");
        $stmt->execute([":ip" => $ip]);
    } catch (PDOException $e) {
        // Parallele Anfrage hat die IP gerade angelegt, dann ist nichts zu tun
        return;
    }

    $geo = geoAbfrage($ip);
    if ($geo === null) {
        return;
    }
    $stmt = $pdo->prepare("UPDATE angreifer SET land = :land, netzbetreiber = :netz WHERE ip = :ip");
    $stmt->execute([":land" => $geo["land"], ":netz" => $geo["netzbetreiber"], ":ip" => $ip]);
}
