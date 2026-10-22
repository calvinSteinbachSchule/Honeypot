<?php

require_once __DIR__ . "/db.php";
require_once __DIR__ . "/helpers.php";

const ANGRIFFSARTEN = ["SQL-Injection", "XSS"];

function sessionStarten(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }
    $https = ($_SERVER["HTTP_X_FORWARDED_PROTO"] ?? "") === "https" || !empty($_SERVER["HTTPS"]);
    session_name("honeypot_dashboard");
    session_set_cookie_params([
        "lifetime" => 0,
        "path"     => "/dashboard",
        "secure"   => $https,
        "httponly" => true,
        "samesite" => "Strict",
    ]);
    session_start();
}

function csrfToken(): string
{
    if (empty($_SESSION["csrf"])) {
        $_SESSION["csrf"] = bin2hex(random_bytes(32));
    }
    return $_SESSION["csrf"];
}

function csrfPruefen(): bool
{
    $token = $_POST["csrf"] ?? "";
    return is_string($token) && isset($_SESSION["csrf"]) && hash_equals($_SESSION["csrf"], $token);
}

// Prüft Zugangsdaten gegen die Tabelle admin (password_hash / password_verify)
function adminAnmelden(string $benutzername, string $passwort): bool
{
    $stmt = Database::connection()->prepare("SELECT id, passworthash FROM admin WHERE benutzername = :b");
    $stmt->execute([":b" => kuerzen($benutzername)]);
    $admin = $stmt->fetch();

    // Bei unbekanntem Benutzer trotzdem einen Hash prüfen, damit die Antwortzeit gleich bleibt
    $hash = $admin["passworthash"] ?? '$2y$12$MJrl8QNUzBFHS7IR76e3NulndczxMFHwiNPdxTxS33wwPL2jBCsc2';
    $ok = password_verify($passwort, $hash);

    if ($admin && $ok) {
        session_regenerate_id(true);
        $_SESSION["admin_id"] = (int) $admin["id"];
        return true;
    }
    return false;
}

function istAngemeldet(): bool
{
    return isset($_SESSION["admin_id"]);
}

// Liest und validiert die Filter aus der URL
function filterLesen(array $get): array
{
    $datum = fn($v) => (is_string($v) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $v) && strtotime($v) !== false) ? $v : "";
    $angriff = $get["angriff"] ?? "";

    return [
        "von"     => $datum($get["von"] ?? ""),
        "bis"     => $datum($get["bis"] ?? ""),
        "land"    => is_string($get["land"] ?? null) ? kuerzen($get["land"]) : "",
        "angriff" => (is_string($angriff) && (in_array($angriff, ANGRIFFSARTEN, true) || $angriff === "keine")) ? $angriff : "",
    ];
}

// Baut WHERE-Klausel und Parameter (immer als Prepared Statement)
function filterSql(array $f): array
{
    $where = [];
    $params = [];

    if ($f["von"] !== "") {
        $where[] = "v.zeitpunkt >= :von";
        $params[":von"] = $f["von"] . " 00:00:00";
    }
    if ($f["bis"] !== "") {
        $where[] = "v.zeitpunkt <= :bis";
        $params[":bis"] = $f["bis"] . " 23:59:59";
    }
    if ($f["land"] === "Unbekannt") {
        $where[] = "a.land IS NULL";
    } elseif ($f["land"] !== "") {
        $where[] = "a.land = :land";
        $params[":land"] = $f["land"];
    }
    if ($f["angriff"] === "keine") {
        $where[] = "v.angriffsmuster IS NULL";
    } elseif ($f["angriff"] !== "") {
        $where[] = "v.angriffsmuster LIKE :angriff";
        $params[":angriff"] = "%" . $f["angriff"] . "%";
    }

    return [$where ? "WHERE " . implode(" AND ", $where) : "", $params];
}

function abfrage(string $sql, array $params): array
{
    $stmt = Database::connection()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

// Alle Auswertungen für das Dashboard unter Berücksichtigung der Filter
function auswertung(array $f): array
{
    [$where, $p] = filterSql($f);
    $von = "FROM versuch v JOIN angreifer a ON a.ip = v.angreifer";

    // Zusatzbedingung für Listen, die leere Werte ausschließen
    $und = fn(string $bed) => $where === "" ? "WHERE $bed" : "$where AND $bed";

    return [
        "gesamt" => abfrage("SELECT COUNT(*) AS versuche, COUNT(DISTINCT v.angreifer) AS ips $von $where", $p)[0],
        "proTag" => abfrage("SELECT DATE(v.zeitpunkt) AS tag, COUNT(*) AS anzahl $von $where GROUP BY DATE(v.zeitpunkt) ORDER BY tag", $p),
        "laender" => abfrage("SELECT COALESCE(a.land, 'Unbekannt') AS name, COUNT(*) AS anzahl $von $where GROUP BY name ORDER BY anzahl DESC LIMIT 10", $p),
        "benutzer" => abfrage("SELECT v.benutzername AS name, COUNT(*) AS anzahl $von " . $und("v.benutzername IS NOT NULL AND v.benutzername <> ''") . " GROUP BY v.benutzername ORDER BY anzahl DESC LIMIT 10", $p),
        "passwoerter" => abfrage("SELECT v.passwort AS name, COUNT(*) AS anzahl $von " . $und("v.passwort IS NOT NULL AND v.passwort <> ''") . " GROUP BY v.passwort ORDER BY anzahl DESC LIMIT 10", $p),
        "muster" => abfrage("SELECT v.angriffsmuster AS name, COUNT(*) AS anzahl $von " . $und("v.angriffsmuster IS NOT NULL") . " GROUP BY v.angriffsmuster ORDER BY anzahl DESC", $p),
        "letzte" => abfrage("SELECT v.zeitpunkt, v.angreifer, a.land, a.netzbetreiber, v.pfad, v.benutzername, v.passwort, v.useragent, v.angriffsmuster $von $where ORDER BY v.id DESC LIMIT 50", $p),
    ];
}

// Länder für das Filter-Dropdown
function alleLaender(): array
{
    $zeilen = abfrage("SELECT DISTINCT COALESCE(land, 'Unbekannt') AS name FROM angreifer ORDER BY name", []);
    return array_column($zeilen, "name");
}
