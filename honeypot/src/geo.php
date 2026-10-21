<?php

// Fragt Land und Netzbetreiber einer IP bei ip-api.com ab.
// Gibt ["land" => ..., "netzbetreiber" => ...] zurück oder null, wenn die
// API nicht erreichbar ist oder keine brauchbare Antwort liefert.
// Der Honeypot darf dadurch nie ausfallen.
function geoAbfrage(string $ip): ?array
{
    if (filter_var($ip, FILTER_VALIDATE_IP) === false) {
        return null;
    }

    $ch = curl_init("http://ip-api.com/json/" . rawurlencode($ip) . "?fields=status,country,isp");
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => 2,
        CURLOPT_TIMEOUT        => 3,
    ]);
    $antwort = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    curl_close($ch);

    if ($antwort === false || $status !== 200) {
        error_log("Geolocation-API nicht erreichbar oder Fehler (HTTP $status) für $ip");
        return null;
    }

    $daten = json_decode($antwort, true);
    if (!is_array($daten) || ($daten["status"] ?? "") !== "success") {
        return null;
    }

    return [
        "land"          => kuerzen((string) ($daten["country"] ?? ""), 255) ?: null,
        "netzbetreiber" => kuerzen((string) ($daten["isp"] ?? ""), 255) ?: null,
    ];
}
