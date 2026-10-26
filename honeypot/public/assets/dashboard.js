var daten = JSON.parse(document.getElementById("diagrammdaten").textContent);

function zeichne(id, typ, zeilen, labelFeld)
{
    var labels = [];
    var werte = [];

    for(var i = 0; i < zeilen.length; i++)
    {
        labels.push(zeilen[i][labelFeld]);
        werte.push(Number(zeilen[i].anzahl));
    }

    var farbe = "#2980b9";
    var legendeAnzeigen = false;
    var skalen = { y: { beginAtZero: true, ticks: { precision: 0 } } };

    if(typ === "doughnut")
    {
        farbe = ["#c0392b", "#2980b9", "#27ae60", "#f39c12", "#8e44ad", "#16a085"];
        legendeAnzeigen = true;
        skalen = {};
    }

    new Chart(document.getElementById(id),
    {
        type: typ,
        data:
        {
            labels: labels,
            datasets: [{ label: "Versuche", data: werte, backgroundColor: farbe, borderColor: "#2980b9" }]
        },
        options:
        {
            plugins: { legend: { display: legendeAnzeigen } },
            scales: skalen
        }
    });
}

zeichne("chart-tage", "line", daten.tage, "tag");
zeichne("chart-laender", "bar", daten.laender, "name");
zeichne("chart-muster", "doughnut", daten.muster, "name");
