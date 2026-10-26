<?php

    $beitraege =
    [
        [
            "kategorie" => "Neuigkeiten",
            "titel" => "Hallo Welt!",
            "datum" => "3. Juni 2026",
            "text" => "Willkommen bei WordPress. Dies ist dein erster Beitrag. Bearbeite oder lösche ihn und beginne mit dem Schreiben!"
        ],
        [
            "kategorie" => "Grundlagen",
            "titel" => "Was ist ein Honeypot?",
            "datum" => "12. Juni 2026",
            "text" => "Ein Honeypot ist ein Köder, der absichtlich angreifbar wirkt. Er enthält keine echten Daten, hält aber jeden Zugriff fest. So lässt sich sehen, wie Angriffe aussehen."
        ],
        [
            "kategorie" => "Passwörter",
            "titel" => "Immer wieder 123456",
            "datum" => "9. August 2026",
            "text" => "In den Listen der häufigsten Passwörter stehen seit Jahren Klassiker wie 123456 und password weit oben. Genau diese Kandidaten stehen auch auf den Wortlisten der Bots."
        ]
    ];

    $navigation = ["Startseite", "Über uns", "Blog", "Kontakt"];

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mein WordPress-Blog &#8211; Noch eine WordPress-Seite</title>
    <link rel="stylesheet" href="/assets/startseite.css">
</head>
<body>
    <header class="kopf">
        <div class="kopf-innen">
            <h1 class="site-titel">Mein WordPress-Blog</h1>
            <p class="untertitel">Noch eine WordPress-Seite. Wissenswertes rund um Webserver und Sicherheit.</p>
            <nav>
                <?php
                    foreach($navigation as $punkt)
                    {
                        echo "<a href='/'>" . htmlspecialchars($punkt) . "</a>\n";
                    }
                ?>
            </nav>
        </div>
    </header>

    <div class="layout">
        <main class="beitraege">
            <?php
                foreach($beitraege as $beitrag)
                {
                    echo "<article>\n";
                    echo "<h2><a href='/'>" . htmlspecialchars($beitrag["titel"]) . "</a></h2>\n";
                    echo "<p class='meta'>" . htmlspecialchars($beitrag["datum"]) . " &middot; " . htmlspecialchars($beitrag["kategorie"]) . "</p>\n";
                    echo "<p>" . htmlspecialchars($beitrag["text"]) . "</p>\n";
                    echo "</article>\n";
                }
            ?>
        </main>

        <aside>
            <section>
                <h2>Über diese Seite</h2>
                <p>Ein kleiner Blog über Webserver, Angriffe im Netz und wie man sich dagegen schützt.</p>
            </section>
            <section>
                <h2>Neueste Beiträge</h2>
                <ul>
                    <?php
                        foreach($beitraege as $beitrag)
                        {
                            echo "<li><a href='/'>" . htmlspecialchars($beitrag["titel"]) . "</a></li>\n";
                        }
                    ?>
                </ul>
            </section>
            <section>
                <h2>Meta</h2>
                <ul>
                    <li><a href="/wp-login.php">Anmelden</a></li>
                </ul>
            </section>
        </aside>
    </div>

    <footer class="fuss">
        <div class="fuss-innen">
            &copy; <?php echo date("Y"); ?> Mein WordPress-Blog &middot; Stolz betrieben mit <a href="https://wordpress.org/">WordPress</a>
        </div>
    </footer>
</body>
</html>
