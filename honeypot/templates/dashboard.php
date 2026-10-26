<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Honeypot-Dashboard</title>
    <link rel="stylesheet" href="/assets/dashboard.css">
</head>
<body>
    <header>
        <h1>Honeypot-Dashboard</h1>
        <form method="post" action="/dashboard">
            <button type="submit" name="abmelden" value="1">Abmelden</button>
        </form>
    </header>

    <main>
        <form method="get" action="/dashboard" class="filter">
            <label>Von <input type="date" name="von"></label>
            <label>Bis <input type="date" name="bis"></label>
            <label>Land
                <select name="land">
                    <option value="">Alle</option>
                    <?php echo $laenderOptionen; ?>
                </select>
            </label>
            <label>Angriffsart
                <select name="angriff">
                    <option value="">Alle</option>
                    <option value="SQL-Injection">SQL-Injection</option>
                    <option value="XSS">XSS</option>
                    <option value="keine">Kein Muster erkannt</option>
                </select>
            </label>
            <button type="submit">Filtern</button>
            <a href="/dashboard">Zurücksetzen</a>
        </form>

        <section class="kacheln">
            <div><span><?php echo $gesamt["versuche"]; ?></span> Zugriffe / Versuche</div>
            <div><span><?php echo $gesamt["ips"]; ?></span> verschiedene IP-Adressen</div>
        </section>

        <section class="diagramme">
            <div class="karte"><h2>Versuche pro Tag</h2><canvas id="chart-tage"></canvas></div>
            <div class="karte"><h2>Häufigste Herkunftsländer</h2><canvas id="chart-laender"></canvas></div>
            <div class="karte"><h2>Erkannte Angriffsmuster</h2><canvas id="chart-muster"></canvas></div>
        </section>

        <section class="listen">
            <div class="karte">
                <h2>Meistprobierte Benutzernamen</h2>
                <table>
                    <tr><th>Benutzername</th><th>Anzahl</th></tr>
                    <?php echo $benutzerZeilen; ?>
                </table>
            </div>
            <div class="karte">
                <h2>Meistprobierte Passwörter</h2>
                <table>
                    <tr><th>Passwort</th><th>Anzahl</th></tr>
                    <?php echo $passwortZeilen; ?>
                </table>
            </div>
        </section>

        <section class="karte">
            <h2>Letzte 50 Einträge</h2>
            <div class="scroll">
                <table>
                    <tr><th>Zeitpunkt</th><th>IP</th><th>Land</th><th>Netzbetreiber</th><th>Pfad</th><th>Benutzer</th><th>Passwort</th><th>User-Agent</th><th>Muster</th></tr>
                    <?php echo $letzteZeilen; ?>
                </table>
            </div>
        </section>
    </main>

    <script type="application/json" id="diagrammdaten"><?php echo $json; ?></script>
    <script src="/assets/chart.umd.min.js"></script>
    <script src="/assets/dashboard.js"></script>
</body>
</html>
