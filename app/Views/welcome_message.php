<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cartographie des APL</title>
    <meta name="description" content="The small framework with powerful features">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="/favicon.ico">


    <!-- Essai de leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>

     <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
     integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
     crossorigin=""></script>

     <style>
        #map { height:680px; }
        #viewerDiv { width: 100%; height: 100vh; }
        body {margin:0;}
     </style>
</head>
<body>


<h3> Le début du site de carto ! </h3>

<div id="map"></div>
<script>
    var mymap = L.map('map').setView([46.8, 6.5], 10);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(mymap);

    // Ajouter des marqueurs à la carte
    <?php foreach ($marqueurs as $marqueur): ?>
        // Convertir la valeur de l'APL en un nombre décimal
        var aplValue = parseFloat('<?= str_replace(',', '.', $marqueur['APL_aux_medecins_generalistes']) ?>');

        // Définir la couleur en fonction de la valeur de l'APL
        var couleur = 'hsl(120, 100%, ' + (50 - aplValue * 10) + '%)';

        L.circleMarker([<?= $marqueur['latitude'] ?>, <?= $marqueur['longitude'] ?>], {
            radius: 8,
            color: couleur,
            fillOpacity: 0.7
        })
        .bindPopup('City Code: <?= $marqueur['city_code'] ?><br>Department: <?= $marqueur['department_name'] ?>')
        .addTo(mymap);
    <?php endforeach; ?>
</script>


</body>
</html>

