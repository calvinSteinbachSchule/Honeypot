# Testfälle – Login-Honeypot mit Angriffsanalyse-Dashboard

Getestet am 26.10.2026 in zwei Stufen: Fall 1 bis 26 lokal mit dem
PHP-Entwicklungsserver (PHP 8.5) und einer SQLite-Testdatenbank mit identischem Schema,
Fall 27 bis 33 auf dem VPS im Docker-Setup (Apache, MariaDB, Caddy mit HTTPS).
Die Geolocation-Abfrage lief gegen die echte API ip-api.com.

## Fake-Anmeldeseite und Protokollierung

| Nr. | Testfall | Erwartet | Ergebnis |
|-----|----------|----------|----------|
| 1 | `GET /wp-login.php` | Anmeldeseite, WordPress-Header, Eintrag in `versuch` | bestanden |
| 2 | POST mit Benutzer `admin`, Passwort `123456` | Meldung „password … incorrect“, Eintrag mit Benutzer, Passwort, User-Agent | bestanden |
| 3 | POST mit unbekanntem Benutzer | Meldung „username … not registered“ | bestanden |
| 4 | POST mit Array-Feldern (`log[]=a&pwd[]=b`) | kein Absturz, HTTP 200 | bestanden |
| 5 | `POST /xmlrpc.php` mit XML-Anmeldedaten | XML-RPC-Fehler 403, Benutzer und Passwort gespeichert | bestanden |
| 6 | `GET /xmlrpc.php` | HTTP 405 | bestanden |
| 7 | Zugriff auf `/phpmyadmin/index.php`, `/.env`, `/admin` | Eintrag in `versuch`, Antwort 404 | bestanden |
| 8 | `/wp-admin/` | Eintrag und Weiterleitung auf `/wp-login.php` | bestanden |
| 9 | Zugriff auf nicht aufgelisteten Pfad `/nothing` | 404, kein Eintrag | bestanden |

## Eingabevalidierung und Schutz vor Injection / XSS

| Nr. | Testfall | Erwartet | Ergebnis |
|-----|----------|----------|----------|
| 10 | Benutzername `admin' OR '1'='1` | als „SQL-Injection“ erkannt, unverändert gespeichert, Tabellen unversehrt (Prepared Statements) | bestanden |
| 11 | Benutzername `<script>alert(1)</script>` | als „XSS“ erkannt; auf Fake-Seite und im Dashboard als `&lt;script&gt;` ausgegeben (htmlspecialchars) | bestanden |
| 12 | URL `/admin?id=1 UNION SELECT 1,2` | Muster „SQL-Injection“ im Pfad erkannt | bestanden |
| 13 | Erkennung: 14 Eingaben, darunter harmlose (`admin`, `Sleep123`, `online=1`, `person=1`) | keine Fehlalarme, echte Angriffe erkannt (auch URL-kodiert) | bestanden (nach Korrektur des XSS-Musters, `online=1` löste zuerst fälschlich aus) |
| 14 | Eingabe länger als 255 Zeichen | wird auf Spaltenbreite gekürzt (`kuerzen()`) | durch Code geprüft |

## Geolocation-API

| Nr. | Testfall | Erwartet | Ergebnis |
|-----|----------|----------|----------|
| 15 | Neue IP `8.8.8.8` | Land und Netzbetreiber werden gespeichert | bestanden (United States / Google LLC) |
| 16 | Dieselbe IP erneut | keine weitere Abfrage (IP existiert schon) | bestanden |
| 17 | Private/lokale IP `127.0.0.1` | API meldet Fehler, Land bleibt leer, Anzeige „Unbekannt“ | bestanden |
| 18 | API nicht erreichbar (Proxy auf toten Port) | `geoAbfrage()` liefert `null`, Eintrag wird trotzdem gespeichert, Fehler im Log | bestanden |

## Dashboard

| Nr. | Testfall | Erwartet | Ergebnis |
|-----|----------|----------|----------|
| 19 | `/dashboard` ohne Anmeldung | nur Anmeldeformular, keine Daten | bestanden |
| 20 | Anmeldung ohne CSRF-Token | HTTP 400 | bestanden |
| 21 | Falsches Passwort | HTTP 401, Fehlermeldung | bestanden |
| 22 | SQL-Injection im Dashboard-Login (`' OR '1'='1' --`) | Anmeldung scheitert (401) | bestanden |
| 23 | Richtige Zugangsdaten | Weiterleitung, Dashboard sichtbar | bestanden |
| 24 | Abmelden | Sitzung beendet, Dashboard wieder gesperrt | bestanden |
| 25 | Filter Land, Angriffsart, Zeitraum, Kombination | Zahlen passen zu den Testdaten | bestanden |
| 26 | Ungültige Filterwerte (`von=kaputt`, `angriff=x' OR 1=1`) | werden ignoriert, kein Fehler | bestanden |

## Betrieb auf dem VPS (Docker, Caddy, HTTPS)

| Nr. | Testfall | Erwartet | Ergebnis |
|-----|----------|----------|----------|
| 27 | `http://honeypot.calvinsteinbach.de/wp-login.php` | Weiterleitung auf HTTPS (308) | bestanden |
| 28 | HTTPS-Aufruf | gültiges Let's-Encrypt-Zertifikat (`ssl_verify_result=0`) | bestanden |
| 29 | `/wp-login.php`, `/dashboard`, `/xmlrpc.php`, `/wp-admin/`, `/nothing` | 200, 200, 405, 302, 404 | bestanden |
| 30 | POST mit SQL-Injection über HTTPS | Eintrag mit echter Besucher-IP (nicht Docker-IP), Land und Netzbetreiber ergänzt, Muster „SQL-Injection“ | bestanden |
| 31 | Port 3306 von außen | Verbindung abgelehnt (Datenbank nur im internen Netzwerk) | bestanden |
| 32 | Tabellen `angreifer`, `versuch`, `admin` aus `schema.sql` | beim ersten Start angelegt | bestanden |
| 33 | Bestehende Seite `uptime.calvinsteinbach.de` nach Caddy-Änderung | weiter erreichbar | bestanden |

**Fehler beim Serverlauf gefunden und behoben:** Mit `FallbackResource` lieferte Apache für
nicht existierende `.php`-Dateien (`/wp-login.php`) 404, weil der PHP-Handler vorher zugreift.
Der lokale Test mit dem PHP-Entwicklungsserver hatte das nicht gezeigt. Lösung: `mod_rewrite`
im `<Directory>`-Block (`docker/apache-honeypot.conf`).
