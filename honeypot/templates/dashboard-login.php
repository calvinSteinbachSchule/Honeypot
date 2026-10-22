<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>Dashboard – Anmeldung</title>
<link rel="stylesheet" href="/assets/dashboard.css">
</head>
<body class="login">
<main class="login-box">
  <h1>Honeypot-Dashboard</h1>
  <?php if ($fehler): ?>
    <p class="fehler"><?= h($fehler) ?></p>
  <?php endif; ?>
  <form method="post" action="/dashboard">
    <input type="hidden" name="csrf" value="<?= h($csrf) ?>">
    <label for="benutzername">Benutzername</label>
    <input type="text" id="benutzername" name="benutzername" maxlength="255" required autofocus>
    <label for="passwort">Passwort</label>
    <input type="password" id="passwort" name="passwort" maxlength="255" required>
    <button type="submit">Anmelden</button>
  </form>
</main>
</body>
</html>
