# Honeypot

PHP-Login-Honeypot mit Angriffsanalyse-Dashboard (Projektarbeit).

Eine gefälschte WordPress-Anmeldeseite protokolliert Anmeldeversuche und Zugriffe auf
typische Angriffspfade in MariaDB. Ein geschütztes Dashboard (`/dashboard`) wertet
Herkunftsländer, probierte Zugangsdaten und erkannte Angriffsmuster aus.

- Start: `cd honeypot && docker compose up -d --build`
- Dashboard-Benutzer: `docker compose exec php php /var/www/src/create_admin.php` (fragt Name und Passwort ab)
- Doku: `docs/Benutzerhandbuch.docx`, `docs/Testfaelle.md`, Antrag in `docs/`
