// Chart.js wird ausschließlich zur Darstellung genutzt.
// Alle Daten kommen fertig ausgewertet aus PHP/SQL.
const daten = JSON.parse(document.getElementById("diagrammdaten").textContent);

function zeichne(id, typ, zeilen, beschriftung, labelFeld) {
  new Chart(document.getElementById(id), {
    type: typ,
    data: {
      labels: zeilen.map((z) => z[labelFeld]),
      datasets: [{
        label: beschriftung,
        data: zeilen.map((z) => Number(z.anzahl)),
        backgroundColor: typ === "doughnut"
          ? ["#c0392b", "#2980b9", "#27ae60", "#f39c12", "#8e44ad", "#16a085"]
          : "#2980b9",
        borderColor: "#2980b9",
      }],
    },
    options: {
      plugins: { legend: { display: typ === "doughnut" } },
      scales: typ === "doughnut" ? {} : { y: { beginAtZero: true, ticks: { precision: 0 } } },
    },
  });
}

zeichne("chart-tage", "line", daten.tage, "Versuche", "tag");
zeichne("chart-laender", "bar", daten.laender, "Versuche", "name");
zeichne("chart-muster", "doughnut", daten.muster, "Versuche", "name");
