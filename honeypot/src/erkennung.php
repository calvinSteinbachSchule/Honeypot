<?php

// Erkennt typische Angriffsmuster in eingegebenen Daten.
// Gibt eine Liste der gefundenen Muster zurück, z. B. ["SQL-Injection", "XSS"].

const MUSTER_SQLI = [
    '/\bunion\b[\s\S]{0,30}\bselect\b/i',
    '/[\'"`]\s*(or|and)\b/i',
    '/\b(or|and)\b\s+[\'"]?\w+[\'"]?\s*(=|like)\s*[\'"]?\w+/i',
    '/[\'"`]\s*(--|#|;|\/\*)/',
    '/(--|#)\s*$/',
    '/;\s*(drop|select|insert|update|delete|alter|create)\b/i',
    '/\b(select|insert|update|delete)\b[\s\S]{0,60}\b(from|into|set)\b/i',
    '/\bdrop\s+(table|database)\b/i',
    '/\b(sleep|benchmark|load_file|extractvalue|updatexml)\s*\(/i',
    '/\binformation_schema\b/i',
];

const MUSTER_XSS = [
    '/<\s*script/i',
    '/<\s*\/\s*script/i',
    '/\bon(error|load|click|dblclick|mouse\w+|focus\w*|blur|key\w+|submit|change|toggle|begin|start|animation\w+|pointer\w+)\s*=/i',
    '/javascript\s*:/i',
    '/<\s*(img|svg|iframe|body|object|embed|link|style)\b/i',
    '/document\s*\.\s*(cookie|location|write)/i',
];

function erkenneAngriffsmuster(?string ...$eingaben): array
{
    $gefunden = [];
    foreach ($eingaben as $eingabe) {
        if ($eingabe === null || $eingabe === "") {
            continue;
        }
        // Auch URL-kodierte Angriffe (%27, %3Cscript%3E) erkennen
        $text = urldecode($eingabe);

        if (!in_array("SQL-Injection", $gefunden, true) && passtEinMuster(MUSTER_SQLI, $text)) {
            $gefunden[] = "SQL-Injection";
        }
        if (!in_array("XSS", $gefunden, true) && passtEinMuster(MUSTER_XSS, $text)) {
            $gefunden[] = "XSS";
        }
    }
    return $gefunden;
}

function passtEinMuster(array $muster, string $text): bool
{
    foreach ($muster as $m) {
        if (preg_match($m, $text) === 1) {
            return true;
        }
    }
    return false;
}
