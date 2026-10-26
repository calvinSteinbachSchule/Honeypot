# Benutzerhandbuch – Login-Honeypot mit Angriffsanalyse-Dashboard

*Hinweis zur Abgabe: Für die Abgabe als Dokument in Arial 11, Zeilenabstand 1,5 formatieren (3 bis 4 Seiten).*

## 1. Zweck

Die Anwendung ist eine absichtlich angreifbar wirkende Falle (Honeypot). Sie zeigt
eine gefälschte WordPress-Anmeldeseite. Jeder Anmeldeversuch wird abgelehnt und
gespeichert. Ein geschütztes Dashboard wertet die gesammelten Daten aus: woher die
Angriffe kommen, welche Zugangsdaten probiert werden und welche Angriffstechniken
(SQL-Injection, Cross-Site-Scripting) vorkommen.

## 2. Installation und Start

Voraussetzung: Docker mit Docker Compose.

1. Im Ordner `honeypot/` eine Datei `.env` anlegen:

   ```
   MARIADB_ROOT_PASSWORD=<geheim>
   MARIADB_DATABASE=honeypot
   MARIADB_USER=honeypot
   MARIADB_PASSWORD=<geheim>
   ```

2. Container starten: `docker compose up -d --build`
   Beim ersten Start legt MariaDB die Tabellen aus `schema.sql` an.
3. Dashboard-Benutzer anlegen:
   `docker compose exec php php /var/www/src/create_admin.php`
   Das Skript fragt Benutzername und Passwort ab. Gibt es den Benutzer schon, wird sein Passwort ersetzt.
   (Gespeichert wird nur der Hash des Passworts.)
4. Auf dem Server läuft der Honeypot hinter dem Webserver Caddy, der für HTTPS sorgt.
   Die Datenbank ist von außen nicht erreichbar.

## 3. Der Honeypot

| Pfad | Verhalten |
|------|-----------|
| `/wp-login.php` | Gefälschte Anmeldeseite. Jeder Versuch wird abgelehnt und mit IP, Zeitpunkt, Benutzername, Passwort und User-Agent gespeichert. |
| `/.env` (und Varianten wie `.env.bak`), `/wp-config.php` (und `.bak`), `/backup.sql` | Köderdateien: Zugriff wird protokolliert, die Antwort enthält erfundene Zugangsdaten (siehe unten). |
| `/xmlrpc.php`, `/wp-admin`, `/phpmyadmin`, `/admin` u. a. | Zugriff wird protokolliert, Antwort ist 404 (bzw. Weiterleitung bei `/wp-admin`). |
| alle anderen Pfade | 404, ohne Eintrag |

**Köderdateien:** Bots suchen gezielt nach Dateien wie `.env` oder `wp-config.php`, in denen
Zugangsdaten stehen. Der Honeypot liefert dort erfundene Inhalte aus (`public/index.php`). Die
Zugangsdaten darin gelten nirgends. Probiert ein Bot sie später auf `/wp-login.php` aus, erscheinen
sie im Dashboard unter „Meistprobierte Benutzernamen“ und „Passwörter“. So lässt sich erkennen, dass
ein Bot die Daten aus einer Köderdatei hat. In die Köderdateien gehören nie echte Zugangsdaten.

Bei jeder **neuen** IP-Adresse werden Land und Netzbetreiber einmalig über
ip-api.com abgefragt und gespeichert. Ist die API nicht erreichbar, steht als
Land „Unbekannt“ und der Honeypot arbeitet normal weiter.

Jede Eingabe wird auf typische Angriffsmuster geprüft. Erkannt werden
**SQL-Injection** (z. B. `' OR '1'='1`, `UNION SELECT`) und **XSS**
(z. B. `<script>`, `onerror=`). Das Muster wird beim Eintrag mitgespeichert.

## 4. Das Dashboard

Aufruf: `https://honeypot.calvinsteinbach.de/dashboard`. Das Dashboard ist nicht öffentlich
verlinkt und nur nach Anmeldung nutzbar.

**Anmelden:** Benutzername und Passwort des angelegten Admins eingeben.
**Abmelden:** Schaltfläche „Abmelden“ oben rechts.

**Inhalt:**

- Kennzahlen: Anzahl der Zugriffe/Versuche und der verschiedenen IP-Adressen
- Diagramm „Versuche pro Tag“
- Diagramm „Häufigste Herkunftsländer“ (Top 10)
- Diagramm „Erkannte Angriffsmuster“
- Tabellen „Meistprobierte Benutzernamen“ und „Meistprobierte Passwörter“ (Top 10)
- Tabelle „Letzte 50 Einträge“ mit allen Details

**Filter:** Über dem Dashboard lassen sich Zeitraum (von/bis), Land und
Angriffsart (SQL-Injection, XSS, kein Muster erkannt) auswählen. „Filtern“
wendet die Auswahl auf alle Kennzahlen, Diagramme und Tabellen an,
„Zurücksetzen“ zeigt wieder alles.

## 5. Sicherheit und Datenschutz

- Alle Datenbankzugriffe verwenden Prepared Statements (Schutz vor SQL-Injection).
- Alle gespeicherten Daten werden mit `htmlspecialchars` ausgegeben (Schutz vor XSS).
- Eingaben werden auf 255 Zeichen begrenzt.
- Dashboard-Passwörter werden mit `password_hash` gespeichert, Anmeldung über Sessions.
- IP-Adressen werden nur zur Auswertung im Rahmen des Projekts gespeichert und nach
  Projektende gelöscht, z. B. mit
  `docker compose down -v` (löscht auch das Datenbank-Volume).
- Für die Vorstellung werden nur Testzugangsdaten verwendet.

## 6. Fehlersuche

| Problem | Lösung |
|---------|--------|
| Dashboard-Anmeldung klappt nicht | Admin mit `create_admin.php` neu anlegen, das setzt auch das Passwort neu |
| Tabellen fehlen | `schema.sql` läuft nur beim ersten Start. Mit `docker compose down -v` neu anfangen (löscht alle Daten) |
