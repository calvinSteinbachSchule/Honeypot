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
    <input type="hidden" name="csrf" value="<?= h($csrf) ?>">
    <input type="hidden" name="aktion" value="abmelden">
    <button type="submit">Abmelden</button>
  </form>
</header>

<main>
  <form method="get" action="/dashboard" class="filter">
    <label>Von <input type="date" name="von" value="<?= h($filter["von"]) ?>"></label>
    <label>Bis <input type="date" name="bis" value="<?= h($filter["bis"]) ?>"></label>
    <label>Land
      <select name="land">
        <option value="">Alle</option>
        <?php foreach ($laender as $land): ?>
          <option value="<?= h($land) ?>" <?= $filter["land"] === $land ? "selected" : "" ?>><?= h($land) ?></option>
        <?php endforeach; ?>
      </select>
    </label>
    <label>Angriffsart
      <select name="angriff">
        <option value="">Alle</option>
        <?php foreach (ANGRIFFSARTEN as $art): ?>
          <option value="<?= h($art) ?>" <?= $filter["angriff"] === $art ? "selected" : "" ?>><?= h($art) ?></option>
        <?php endforeach; ?>
        <option value="keine" <?= $filter["angriff"] === "keine" ? "selected" : "" ?>>Kein Muster erkannt</option>
      </select>
    </label>
    <button type="submit">Filtern</button>
    <a href="/dashboard">Zurücksetzen</a>
  </form>

  <section class="kacheln">
    <div><span><?= (int) $daten["gesamt"]["versuche"] ?></span> Zugriffe / Versuche</div>
    <div><span><?= (int) $daten["gesamt"]["ips"] ?></span> verschiedene IP-Adressen</div>
  </section>

  <section class="diagramme">
    <div class="karte"><h2>Versuche pro Tag</h2><canvas id="chart-tage"></canvas></div>
    <div class="karte"><h2>Häufigste Herkunftsländer</h2><canvas id="chart-laender"></canvas></div>
    <div class="karte"><h2>Erkannte Angriffsmuster</h2><canvas id="chart-muster"></canvas></div>
  </section>

  <section class="listen">
    <?php
    $listen = ["benutzer" => "Meistprobierte Benutzernamen", "passwoerter" => "Meistprobierte Passwörter"];
    foreach ($listen as $schluessel => $titel): ?>
      <div class="karte">
        <h2><?= h($titel) ?></h2>
        <table>
          <tr><th>Wert</th><th>Anzahl</th></tr>
          <?php foreach ($daten[$schluessel] as $z): ?>
            <tr><td><?= h($z["name"]) ?></td><td><?= (int) $z["anzahl"] ?></td></tr>
          <?php endforeach; ?>
          <?php if (!$daten[$schluessel]): ?><tr><td colspan="2">Keine Daten</td></tr><?php endif; ?>
        </table>
      </div>
    <?php endforeach; ?>
  </section>

  <section class="karte">
    <h2>Letzte 50 Einträge</h2>
    <div class="scroll">
      <table>
        <tr><th>Zeitpunkt</th><th>IP</th><th>Land</th><th>Netzbetreiber</th><th>Pfad</th><th>Benutzer</th><th>Passwort</th><th>User-Agent</th><th>Muster</th></tr>
        <?php foreach ($daten["letzte"] as $z): ?>
          <tr>
            <td><?= h($z["zeitpunkt"]) ?></td>
            <td><?= h($z["angreifer"]) ?></td>
            <td><?= h($z["land"] ?? "Unbekannt") ?></td>
            <td><?= h($z["netzbetreiber"]) ?></td>
            <td><?= h($z["pfad"]) ?></td>
            <td><?= h($z["benutzername"]) ?></td>
            <td><?= h($z["passwort"]) ?></td>
            <td><?= h($z["useragent"]) ?></td>
            <td><?= h($z["angriffsmuster"]) ?></td>
          </tr>
        <?php endforeach; ?>
        <?php if (!$daten["letzte"]): ?><tr><td colspan="9">Keine Daten</td></tr><?php endif; ?>
      </table>
    </div>
  </section>
</main>

<script type="application/json" id="diagrammdaten"><?= json_encode([
    "tage"    => $daten["proTag"],
    "laender" => $daten["laender"],
    "muster"  => $daten["muster"],
], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_INVALID_UTF8_SUBSTITUTE) ?></script>
<script src="/assets/chart.umd.min.js"></script>
<script src="/assets/dashboard.js"></script>
</body>
</html>
