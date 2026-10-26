<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Honeypot-Dashboard – Anmeldung</title>
    <link rel="stylesheet" href="/assets/dashboard.css">
</head>
<body class="login">
    <main class="login-box">
        <h1>Honeypot-Dashboard</h1>
        <p class="fehler"><?php echo $fehler; ?></p>
        <form method="post" action="/dashboard">
            <label for="benutzername">Benutzername</label>
            <input type="text" id="benutzername" name="benutzername" required>
            <label for="passwort">Passwort</label>
            <input type="password" id="passwort" name="passwort" required>
            <button type="submit">Anmelden</button>
        </form>
    </main>
</body>
</html>
