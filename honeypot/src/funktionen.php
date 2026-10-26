<?php

    function verbindung()
    {
        $host = "db";
        $db = getenv("MARIADB_DATABASE");
        $user = getenv("MARIADB_USER");
        $pass = getenv("MARIADB_PASSWORD");

        $pdo = new PDO("mysql:host={$host};dbname={$db};charset=utf8mb4", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        return $pdo;
    }

    function nichtGefunden()
    {
        http_response_code(404);
        echo "<!DOCTYPE html><html><head><title>404 Not Found</title></head><body><h1>Not Found</h1>";
        echo "<p>The requested URL was not found on this server.</p></body></html>";
    }

    function speichereVersuch($ip, $pfad, $benutzer, $passwort, $userAgent)
    {
        $pdo = verbindung();

        $text = strtolower(urldecode($benutzer . " " . $passwort . " " . $pfad));

        $sqlStichwoerter = ["' or ", "\" or ", " or 1=1", "union select", "--", "drop table", "sleep(", "information_schema"];
        $xssStichwoerter = ["<script", "onerror=", "onload=", "javascript:", "<img", "<svg", "<iframe"];

        $istSql = false;
        $istXss = false;

        foreach($sqlStichwoerter as $stichwort)
        {
            if(str_contains($text, $stichwort))
            {
                $istSql = true;
            }
        }

        foreach($xssStichwoerter as $stichwort)
        {
            if(str_contains($text, $stichwort))
            {
                $istXss = true;
            }
        }

        $muster = "keine";

        if($istSql && $istXss)
        {
            $muster = "SQL-Injection, XSS";
        }
        elseif($istSql)
        {
            $muster = "SQL-Injection";
        }
        elseif($istXss)
        {
            $muster = "XSS";
        }

        $stmt = $pdo->prepare("SELECT ip FROM angreifer WHERE ip = :ip");
        $stmt->execute([":ip" => $ip]);

        if($stmt->fetch() === false)
        {
            $stmt = $pdo->prepare("INSERT INTO angreifer (ip, land) VALUES (:ip, 'Unbekannt')");
            $stmt->execute([":ip" => $ip]);

            ini_set("default_socket_timeout", "3");
            $antwort = file_get_contents("http://ip-api.com/json/{$ip}?fields=status,country,isp");

            if($antwort !== false)
            {
                $daten = json_decode($antwort, true);

                if($daten["status"] === "success")
                {
                    $stmt = $pdo->prepare("UPDATE angreifer SET land = :land, netzbetreiber = :netz WHERE ip = :ip");
                    $stmt->execute([":land" => $daten["country"], ":netz" => $daten["isp"], ":ip" => $ip]);
                }
            }
        }

        $stmt = $pdo->prepare("INSERT INTO versuch (angreifer, pfad, benutzername, passwort, useragent, angriffsmuster) VALUES (:ip, :pfad, :benutzer, :passwort, :useragent, :muster)");
        $stmt->execute([
            ":ip" => $ip,
            ":pfad" => mb_substr($pfad, 0, 255),
            ":benutzer" => mb_substr($benutzer, 0, 255),
            ":passwort" => mb_substr($passwort, 0, 255),
            ":useragent" => mb_substr($userAgent, 0, 255),
            ":muster" => $muster
        ]);
    }

?>
