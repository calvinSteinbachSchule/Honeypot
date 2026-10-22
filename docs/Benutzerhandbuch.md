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
   `docker compose exec php php /var/www/src/create_admin.php <benutzername> <passwort>`
   (Passwort mindestens 8 Zeichen, gespeichert wird nur der Hash.)
4. Betrieb auf dem VPS (Hetzner, `/opt/projects/honeypot`): Der Webserver Caddy
   (Container `caddy-monitoring`, im Netzwerk `honeypot-web`) bedient die Domain
   `honeypot.calvinsteinbach.de` mit Let's-Encrypt-Zertifikat und leitet per
   `reverse_proxy php:80` an den Container `php` weiter. Er setzt den Header
   `X-Forwarded-For`, damit die echte Angreifer-IP gespeichert wird.
   Die Datenbank liegt in einem internen Netzwerk und ist von außen nicht erreichbar.

## 3. Der Honeypot

| Pfad | Verhalten |
|------|-----------|
| `/wp-login.php` | Gefälschte Anmeldeseite. Jeder Versuch wird abgelehnt und mit IP, Zeitpunkt, Benutzername, Passwort und User-Agent gespeichert. |
| `/xmlrpc.php` | Gefälschte XML-RPC-Schnittstelle, antwortet mit Fehler 403. Zugangsdaten im XML werden gespeichert. |
| `/wp-admin`, `/phpmyadmin`, `/.env`, `/admin` u. a. | Zugriff wird protokolliert, Antwort ist 404 (bzw. Weiterleitung bei `/wp-admin`). |
| alle anderen Pfade | 404, ohne Eintrag |

Bei jeder **neuen** IP-Adresse werden Land und Netzbetreiber einmalig über
ip-api.com abgefragt und gespeichert. Ist die API nicht erreichbar, bleibt das
Land leer (Anzeige „Unbekannt“) und der Honeypot arbeitet normal weiter.

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
- Dashboard-Passwörter werden mit `password_hash` gespeichert, Anmeldung über Sessions
  mit CSRF-Schutz und Cookie-Flags `HttpOnly`, `SameSite=Strict` (und `Secure` unter HTTPS).
- IP-Adressen werden nur zur Auswertung im Rahmen des Projekts gespeichert und nach
  Projektende gelöscht, z. B. mit
  `docker compose down -v` (löscht auch das Datenbank-Volume).
- Für die Vorstellung werden nur Testzugangsdaten verwendet.

## 6. Fehlersuche

| Problem | Ursache / Lösung |
|---------|------------------|
| Dashboard-Anmeldung klappt nicht | Admin mit `create_admin.php` anlegen oder Passwort damit neu setzen |
| Alle Einträge haben dieselbe interne IP | Caddy setzt `X-Forwarded-For` nicht, oder das Docker-Netzwerk liegt außerhalb von `172.16.0.0/12` (`RemoteIPInternalProxy` in `docker/apache-honeypot.conf`) |
| Land ist immer „Unbekannt“ | API nicht erreichbar oder Rate-Limit (45 Anfragen/Minute), Details im Container-Log: `docker compose logs php` |
| Tabellen fehlen | `schema.sql` läuft nur beim ersten Start; mit `docker compose down -v` Volume löschen und neu starten |
