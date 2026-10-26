<?php

    session_start();

    $pdo = verbindung();
    $fehler = "";

    $benutzername = $_POST["benutzername"] ?? "";
    $passwort = $_POST["passwort"] ?? "";
    $abmelden = $_POST["abmelden"] ?? "";

    if($abmelden !== "")
    {
        $_SESSION["angemeldet"] = false;
    }

    if($benutzername !== "")
    {
        $stmt = $pdo->prepare("SELECT passworthash FROM admin WHERE benutzername = :benutzername");
        $stmt->execute([":benutzername" => $benutzername]);
        $admin = $stmt->fetch();

        if($admin !== false && password_verify($passwort, $admin["passworthash"]))
        {
            $_SESSION["angemeldet"] = true;
        }
        else
        {
            http_response_code(401);
            $fehler = "Benutzername oder Passwort falsch.";
        }
    }

    $angemeldet = $_SESSION["angemeldet"] ?? false;

    if($angemeldet === true)
    {
        $von = $_GET["von"] ?? "";
        $bis = $_GET["bis"] ?? "";
        $land = $_GET["land"] ?? "";
        $angriff = $_GET["angriff"] ?? "";

        if($von === "")
        {
            $von = "2000-01-01";
        }
        if($bis === "")
        {
            $bis = "2999-12-31";
        }

        $tabellen = "FROM versuch v JOIN angreifer a ON a.ip = v.angreifer";
        $where = "WHERE v.zeitpunkt BETWEEN :von AND :bis AND a.land LIKE :land AND v.angriffsmuster LIKE :angriff";
        $parameter =
        [
            ":von" => $von . " 00:00:00",
            ":bis" => $bis . " 23:59:59",
            ":land" => "%" . $land . "%",
            ":angriff" => "%" . $angriff . "%"
        ];

        $stmt = $pdo->prepare("SELECT COUNT(*) AS versuche, COUNT(DISTINCT v.angreifer) AS ips $tabellen $where");
        $stmt->execute($parameter);
        $gesamt = $stmt->fetch();

        $stmt = $pdo->prepare("SELECT DATE(v.zeitpunkt) AS tag, COUNT(*) AS anzahl $tabellen $where GROUP BY tag ORDER BY tag");
        $stmt->execute($parameter);
        $proTag = $stmt->fetchAll();

        $stmt = $pdo->prepare("SELECT a.land AS name, COUNT(*) AS anzahl $tabellen $where GROUP BY name ORDER BY anzahl DESC LIMIT 10");
        $stmt->execute($parameter);
        $laenderStatistik = $stmt->fetchAll();

        $stmt = $pdo->prepare("SELECT v.angriffsmuster AS name, COUNT(*) AS anzahl $tabellen $where GROUP BY name ORDER BY anzahl DESC");
        $stmt->execute($parameter);
        $musterStatistik = $stmt->fetchAll();

        $stmt = $pdo->prepare("SELECT v.benutzername AS name, COUNT(*) AS anzahl $tabellen $where AND v.benutzername <> '' GROUP BY name ORDER BY anzahl DESC LIMIT 10");
        $stmt->execute($parameter);
        $benutzerListe = $stmt->fetchAll();

        $stmt = $pdo->prepare("SELECT v.passwort AS name, COUNT(*) AS anzahl $tabellen $where AND v.passwort <> '' GROUP BY name ORDER BY anzahl DESC LIMIT 10");
        $stmt->execute($parameter);
        $passwortListe = $stmt->fetchAll();

        $stmt = $pdo->prepare("SELECT v.zeitpunkt, v.angreifer, a.land, a.netzbetreiber, v.pfad, v.benutzername, v.passwort, v.useragent, v.angriffsmuster $tabellen $where ORDER BY v.id DESC LIMIT 50");
        $stmt->execute($parameter);
        $letzte = $stmt->fetchAll();

        $stmt = $pdo->prepare("SELECT DISTINCT land FROM angreifer ORDER BY land");
        $stmt->execute();
        $alleLaender = $stmt->fetchAll();

        $laenderOptionen = "";

        foreach($alleLaender as $zeile)
        {
            $name = htmlspecialchars($zeile["land"]);
            $laenderOptionen .= "<option value='{$name}'>{$name}</option>";
        }

        $benutzerZeilen = "";

        foreach($benutzerListe as $zeile)
        {
            $benutzerZeilen .= "<tr><td>" . htmlspecialchars($zeile["name"]) . "</td><td>{$zeile["anzahl"]}</td></tr>";
        }

        $passwortZeilen = "";

        foreach($passwortListe as $zeile)
        {
            $passwortZeilen .= "<tr><td>" . htmlspecialchars($zeile["name"]) . "</td><td>{$zeile["anzahl"]}</td></tr>";
        }

        $letzteZeilen = "";

        foreach($letzte as $zeile)
        {
            $letzteZeilen .= "<tr>";
            $letzteZeilen .= "<td>" . htmlspecialchars($zeile["zeitpunkt"]) . "</td>";
            $letzteZeilen .= "<td>" . htmlspecialchars($zeile["angreifer"]) . "</td>";
            $letzteZeilen .= "<td>" . htmlspecialchars($zeile["land"]) . "</td>";
            $letzteZeilen .= "<td>" . htmlspecialchars($zeile["netzbetreiber"] ?? "") . "</td>";
            $letzteZeilen .= "<td>" . htmlspecialchars($zeile["pfad"]) . "</td>";
            $letzteZeilen .= "<td>" . htmlspecialchars($zeile["benutzername"]) . "</td>";
            $letzteZeilen .= "<td>" . htmlspecialchars($zeile["passwort"]) . "</td>";
            $letzteZeilen .= "<td>" . htmlspecialchars($zeile["useragent"]) . "</td>";
            $letzteZeilen .= "<td>" . htmlspecialchars($zeile["angriffsmuster"]) . "</td>";
            $letzteZeilen .= "</tr>";
        }

        $diagrammDaten = ["tage" => $proTag, "laender" => $laenderStatistik, "muster" => $musterStatistik];
        $json = json_encode($diagrammDaten, JSON_HEX_TAG);
    }

    if($angemeldet === false)
    {
        include_once(__DIR__ . "/../templates/dashboard-login.php");
    }
    else
    {
        include_once(__DIR__ . "/../templates/dashboard.php");
    }

?>
