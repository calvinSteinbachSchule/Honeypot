<?php

// Ausgabe immer escapen (Schutz vor gespeichertem XSS im Dashboard)
function h(?string $text): string
{
    return htmlspecialchars($text ?? "", ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
}

// Eingabelänge begrenzen, damit nichts über die Spaltenbreite hinausgeht
function kuerzen(?string $text, int $max = 255): ?string
{
    if ($text === null) {
        return null;
    }
    return mb_substr($text, 0, $max, "UTF-8");
}

// Nur Strings aus $_POST übernehmen (Arrays wie log[]=x werden ignoriert)
function postFeld(array $namen): ?string
{
    foreach ($namen as $name) {
        if (isset($_POST[$name]) && is_string($_POST[$name])) {
            return $_POST[$name];
        }
    }
    return null;
}
