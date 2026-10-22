<?php

require_once __DIR__ . "/../src/logger.php";
require_once __DIR__ . "/../src/dashboard.php";

const TEMPLATES = __DIR__ . "/../templates";

// Pfade, die Bots typischerweise abklappern. Zugriffe darauf werden protokolliert.
const ANGRIFFSPFADE = [
    "/wp-login.php", "/wp-admin", "/xmlrpc.php", "/wp-config", "/wp-json", "/wp-content",
    "/phpmyadmin", "/pma", "/myadmin", "/mysql", "/adminer",
    "/admin", "/administrator", "/login", "/user/login",
    "/.env", "/.git", "/.aws", "/.ssh", "/config", "/backup", "/vendor", "/cgi-bin",
    "/actuator", "/shell", "/setup", "/install", "/server-status", "/console",
];

$ip = $_SERVER["REMOTE_ADDR"] ?? "0.0.0.0";
$userAgent = $_SERVER["HTTP_USER_AGENT"] ?? null;
$uri = $_SERVER["REQUEST_URI"] ?? "/";
$method = $_SERVER["REQUEST_METHOD"] ?? "GET";
$pfad = strtolower(rawurldecode((string) parse_url($uri, PHP_URL_PATH)));

if ($pfad === "/dashboard" || $pfad === "/dashboard/") {
    dashboardSeite($method);
    exit;
}

// Gefälschte WordPress-Anmeldeseite
if ($pfad === "/wp-login.php") {
    loginSeite($method, $ip, $uri, $userAgent);
    exit;
}

// Gefälschte XML-RPC-Schnittstelle
if ($pfad === "/xmlrpc.php") {
    xmlrpcSeite($method, $ip, $uri, $userAgent);
    exit;
}

if ($pfad === "/") {
    header("Content-Type: text/html; charset=UTF-8");
    echo "<!DOCTYPE html><html lang=\"en-US\"><head><meta charset=\"UTF-8\"><title>My WordPress Website</title></head>"
       . "<body><h1>My WordPress Website</h1><p>Just another WordPress site</p></body></html>";
    exit;
}

if (istAngriffspfad($pfad)) {
    // Wie WordPress: /wp-admin leitet auf die Anmeldung weiter
    $benutzer = postFeld(["log", "username", "user", "login", "email", "pma_username", "user_login"]);
    $passwort = postFeld(["pwd", "password", "pass", "passwd", "pma_password", "user_pass"]);
    protokolliere($ip, $uri, $benutzer, $passwort, $userAgent);

    if ($pfad === "/wp-admin" || str_starts_with($pfad, "/wp-admin/")) {
        header("Location: /wp-login.php?redirect_to=" . rawurlencode("/wp-admin/") . "&reauth=1", true, 302);
        exit;
    }
}

notFound();

function istAngriffspfad(string $pfad): bool
{
    foreach (ANGRIFFSPFADE as $p) {
        if ($pfad === $p || str_starts_with($pfad, $p . "/") || str_starts_with($pfad, $p . ".")) {
            return true;
        }
    }
    return str_contains($pfad, "phpmyadmin");
}

function notFound(): void
{
    http_response_code(404);
    header("Content-Type: text/html; charset=UTF-8");
    echo "<!DOCTYPE html><html><head><title>404 Not Found</title></head><body><h1>Not Found</h1>"
       . "<p>The requested URL was not found on this server.</p></body></html>";
}

function loginSeite(string $method, string $ip, string $uri, ?string $userAgent): void
{
    $benutzer = null;
    $fehler = "";

    if ($method === "POST") {
        $benutzer = postFeld(["log"]);
        $passwort = postFeld(["pwd"]);
        protokolliere($ip, $uri, $benutzer, $passwort, $userAgent);

        // Wie WordPress: bekannter Benutzer "admin" hat ein falsches Passwort, alle anderen sind unbekannt
        $name = h($benutzer);
        if ($benutzer !== null && strtolower($benutzer) === "admin") {
            $meldung = "The password you entered for the username <strong>$name</strong> is incorrect. "
                     . "<a href=\"/wp-login.php?action=lostpassword\">Lost your password?</a>";
        } else {
            $meldung = "The username <strong>$name</strong> is not registered on this site. "
                     . "If you are unsure of your username, try your email address instead.";
        }
        $fehler = "<div id=\"login_error\" class=\"notice notice-error\"><p><strong>Error:</strong> $meldung</p></div>";
    } else {
        protokolliere($ip, $uri, null, null, $userAgent);
    }

    // Header wie bei echtem WordPress (siehe reference/wp-login.response-headers.txt)
    header("Expires: Wed, 11 Jan 1984 05:00:00 GMT");
    header("Cache-Control: no-cache, must-revalidate, max-age=0, no-store, private");
    header("Content-Type: text/html; charset=UTF-8");
    header("X-Frame-Options: SAMEORIGIN");
    header("Content-Security-Policy: frame-ancestors 'self';");
    header("Referrer-Policy: strict-origin-when-cross-origin");
    setcookie("wordpress_test_cookie", "WP Cookie check", ["path" => "/", "httponly" => true]);

    echo strtr(file_get_contents(TEMPLATES . "/wp-login.html"), [
        "{{FEHLER}}"       => $fehler,
        "{{BENUTZERNAME}}" => h($benutzer),
    ]);
}

function xmlrpcSeite(string $method, string $ip, string $uri, ?string $userAgent): void
{
    header("Content-Type: text/xml; charset=UTF-8");

    if ($method !== "POST") {
        protokolliere($ip, $uri, null, null, $userAgent);
        http_response_code(405);
        header("Allow: POST");
        echo "XML-RPC server accepts POST requests only.";
        return;
    }

    // Bots schicken Zugangsdaten als XML-Body, die ersten zwei Strings sind meist Benutzer und Passwort
    $body = file_get_contents("php://input", false, null, 0, 65536) ?: "";
    $benutzer = $passwort = null;
    if (preg_match_all('/<string>(.*?)<\/string>/s', $body, $treffer) && count($treffer[1]) >= 2) {
        $benutzer = html_entity_decode($treffer[1][0], ENT_QUOTES | ENT_XML1, "UTF-8");
        $passwort = html_entity_decode($treffer[1][1], ENT_QUOTES | ENT_XML1, "UTF-8");
    }
    protokolliere($ip, $uri, $benutzer, $passwort, $userAgent);

    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"
       . '<methodResponse><fault><value><struct>'
       . '<member><name>faultCode</name><value><int>403</int></value></member>'
       . '<member><name>faultString</name><value><string>Incorrect username or password.</string></value></member>'
       . '</struct></value></fault></methodResponse>';
}

function dashboardSeite(string $method): void
{
    header("X-Robots-Tag: noindex, nofollow");
    header("Cache-Control: no-store");
    header("X-Frame-Options: DENY");
    sessionStarten();

    $fehler = "";

    if ($method === "POST") {
        if (!csrfPruefen()) {
            http_response_code(400);
            $fehler = "Ungültige Anfrage, bitte erneut versuchen.";
        } elseif (($_POST["aktion"] ?? "") === "abmelden") {
            $_SESSION = [];
            session_destroy();
            header("Location: /dashboard", true, 303);
            return;
        } elseif (!istAngemeldet()) {
            $benutzer = $_POST["benutzername"] ?? "";
            $passwort = $_POST["passwort"] ?? "";
            if (is_string($benutzer) && is_string($passwort) && adminAnmelden($benutzer, $passwort)) {
                header("Location: /dashboard", true, 303);
                return;
            }
            sleep(1);
            http_response_code(401);
            $fehler = "Benutzername oder Passwort falsch.";
        }
    }

    $csrf = csrfToken();

    if (!istAngemeldet()) {
        require TEMPLATES . "/dashboard-login.php";
        return;
    }

    $filter = filterLesen($_GET);
    $daten = auswertung($filter);
    $laender = alleLaender();
    require TEMPLATES . "/dashboard.php";
}
