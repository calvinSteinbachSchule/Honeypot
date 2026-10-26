<?php

    include_once(__DIR__ . "/funktionen.php");

    $benutzername = (string) readline("Benutzername: ");
    $passwort = (string) readline("Passwort: ");

    if($benutzername === "" || $passwort === "")
    {
        echo "Benutzername und Passwort dürfen nicht leer sein.\n";
    }
    else
    {
        $hash = password_hash($passwort, PASSWORD_DEFAULT);

        $pdo = verbindung();

        $stmt = $pdo->prepare("DELETE FROM admin WHERE benutzername = :benutzername");
        $stmt->execute([":benutzername" => $benutzername]);

        $stmt = $pdo->prepare("INSERT INTO admin (benutzername, passworthash) VALUES (:benutzername, :hash)");
        $stmt->execute([":benutzername" => $benutzername, ":hash" => $hash]);

        echo "Admin {$benutzername} wurde angelegt.\n";
    }

?>
