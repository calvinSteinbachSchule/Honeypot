<?php

    include_once(__DIR__ . "/../src/funktionen.php");

    $ip = $_SERVER["REMOTE_ADDR"] ?? "0.0.0.0";
    $userAgent = $_SERVER["HTTP_USER_AGENT"] ?? "";
    $uri = $_SERVER["REQUEST_URI"] ?? "/";
    $methode = $_SERVER["REQUEST_METHOD"] ?? "GET";
    $pfad = strtolower(urldecode(parse_url($uri, PHP_URL_PATH)));

    $angriffspfade =
    [
        "/xmlrpc.php", "/wp-admin", "/wp-config", "/wp-json", "/wp-content",
        "/phpmyadmin", "/pma", "/myadmin", "/mysql", "/adminer",
        "/admin", "/administrator", "/login", "/user/login",
        "/.env", "/.git", "/.aws", "/.ssh", "/config", "/backup", "/vendor", "/cgi-bin",
        "/actuator", "/shell", "/setup", "/install", "/server-status", "/console"
    ];

    $istAngriffspfad = false;

    foreach($angriffspfade as $angriffspfad)
    {
        if(str_starts_with($pfad, $angriffspfad))
        {
            $istAngriffspfad = true;
        }
    }

    if($pfad === "/")
    {
        include_once(__DIR__ . "/../templates/startseite.php");
    }
    elseif($pfad === "/dashboard" || $pfad === "/dashboard/")
    {
        include_once(__DIR__ . "/../src/dashboard.php");
    }
    elseif($pfad === "/wp-login.php")
    {
        $benutzer = "";
        $passwort = "";
        $fehler = "";

        if($methode === "POST")
        {
            $benutzer = (string) ($_POST["log"] ?? "");
            $passwort = (string) ($_POST["pwd"] ?? "");
            $fehler = "<div id=\"login_error\"><p><strong>Error:</strong> The username or password you entered is incorrect.</p></div>";
        }

        speichereVersuch($ip, $uri, $benutzer, $passwort, $userAgent);

        $seite = file_get_contents(__DIR__ . "/../templates/wp-login.html");
        $seite = str_replace("{{FEHLER}}", $fehler, $seite);
        $seite = str_replace("{{BENUTZERNAME}}", htmlspecialchars($benutzer), $seite);
        echo $seite;
    }
    elseif($istAngriffspfad)
    {
        $benutzer = (string) ($_POST["username"] ?? "");
        $passwort = (string) ($_POST["password"] ?? "");

        speichereVersuch($ip, $uri, $benutzer, $passwort, $userAgent);

        if(str_starts_with($pfad, "/.env") || str_starts_with($pfad, "/wp-config") || $pfad === "/backup.sql")
        {
            header("Content-Type: text/plain; charset=UTF-8");
            echo "DB_HOST=localhost\n";
            echo "DB_DATABASE=wordpress\n";
            echo "DB_USERNAME=wp_blog\n";
            echo "DB_PASSWORD=Xk9#mPq2vLz\n\n";
            echo "WP_ADMIN_USER=webmaster\n";
            echo "WP_ADMIN_PASSWORD=Blog#Sommer2024\n";
        }
        elseif(str_starts_with($pfad, "/wp-admin"))
        {
            header("Location: /wp-login.php?redirect_to=%2Fwp-admin%2F&reauth=1");
        }
        else
        {
            nichtGefunden();
        }
    }
    else
    {
        nichtGefunden();
    }

?>
