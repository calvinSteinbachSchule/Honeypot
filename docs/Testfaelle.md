# Testfälle – Login-Honeypot mit Angriffsanalyse-Dashboard

Getestet am 26.10.2026 im lokalen Docker-Setup (Apache, PHP 8.5, MariaDB) und anschließend
auf dem VPS unter `https://honeypot.calvinsteinbach.de`.

## Fake-Anmeldeseite und Protokollierung

| Nr. | Testfall | Erwartet | Ergebnis |
|-----|----------|----------|----------|
| 1 | `GET /wp-login.php` | Anmeldeseite wird angezeigt, Eintrag in `versuch` | bestanden |
| 2 | Anmeldung mit Benutzer `admin`, Passwort `123456` | Fehlermeldung, Eintrag mit Benutzer, Passwort und User-Agent | bestanden |
| 3 | Formular mit Array-Feldern (`log[]=a`) | kein Absturz, Seite wird angezeigt | bestanden |
| 4 | Zugriff auf `/phpmyadmin`, `/admin`, `/xmlrpc.php` | Eintrag in `versuch`, Antwort 404 | bestanden |
| 5 | Zugriff auf `/nothing` | 404, kein Eintrag | bestanden |
| 6 | Eingabe länger als 255 Zeichen | wird gekürzt gespeichert | bestanden |

## Schutz vor SQL-Injection und XSS

| Nr. | Testfall | Erwartet | Ergebnis |
|-----|----------|----------|----------|
| 7 | Benutzername `admin' OR '1'='1` | als „SQL-Injection“ erkannt, unverändert gespeichert, Tabellen unversehrt | bestanden |
| 8 | Benutzername `<script>alert(1)</script>` | als „XSS“ erkannt, im Dashboard nur als Text angezeigt | bestanden |
| 9 | Harmlose Eingaben (`admin`, `Sleep123`, `online=1`) | kein Angriffsmuster erkannt | bestanden |
| 10 | SQL-Injection im Dashboard-Login | Anmeldung scheitert (HTTP 401) | bestanden |

## Geolocation-API

| Nr. | Testfall | Erwartet | Ergebnis |
|-----|----------|----------|----------|
| 11 | Neue öffentliche IP | Land und Netzbetreiber werden gespeichert | bestanden |
| 12 | Lokale IP (API liefert keinen Treffer) | Land „Unbekannt“, Eintrag wird trotzdem gespeichert | bestanden |
| 13 | API nicht erreichbar | `file_get_contents` liefert `false`, Land bleibt „Unbekannt“, Seite antwortet nach höchstens 3 Sekunden | durch Code geprüft |

## Dashboard

| Nr. | Testfall | Erwartet | Ergebnis |
|-----|----------|----------|----------|
| 14 | `/dashboard` ohne Anmeldung | nur Anmeldeformular | bestanden |
| 15 | Falsches Passwort | HTTP 401, Fehlermeldung | bestanden |
| 16 | Richtige Zugangsdaten, danach Abmelden | Dashboard sichtbar, nach Abmelden wieder Anmeldeformular | bestanden |
| 17 | Filter nach Angriffsart, Land und Zeitraum | Zahlen passen zu den Testdaten | bestanden |

## Köderdateien

| Nr. | Testfall | Erwartet | Ergebnis |
|-----|----------|----------|----------|
| 18 | `/.env`, `/wp-config.php.bak`, `/backup.sql` | Klartext mit erfundenen Zugangsdaten, Eintrag in `versuch` | bestanden |

**Fehler beim Serverlauf gefunden und behoben:** Mit `FallbackResource` lieferte Apache für
nicht existierende `.php`-Dateien (`/wp-login.php`) 404. Lösung: `mod_rewrite` in
`docker/apache-honeypot.conf`.
