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

     <script src="<?php echo base_url('js/leaflet-heat.js')?>"></script>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

     <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.Default.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.css" />
    <script src="https://unpkg.com/leaflet.markercluster/dist/leaflet.markercluster.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/chroma-js/2.1.0/chroma.min.js"></script>

     <style>
        #map { height:580px;}
        body {margin:0; background: ##e6e6e6;}
     </style>
</head>
<body>

<div style="padding:20px;">
    <center><h3> Visualisation des Accessibilités Potentielles Localisées </h3></center>
</div>
<div class="accordion ccordion-flush" id="accordionExample" style ="padding-bottom:50px;">
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
        Choisir les données à afficher
      </button>
    </h2>
    <div id="collapseOne" class="accordion-collapse collapse hide" data-bs-parent="#accordionExample">
      <div class="accordion-body">
        <div class="container text-center">
            <div class="row align-items-start">
                <div class="col">
                <select class="form-select" aria-label="Default select example">
                    <option disabled selected>Séléctionnez les professionnels de santé</option>
                    <option value="1">Médecins généralistes</option>
                    <option value="2" disabled>Infirmières</option>
                    <option value="3" disabled>Masseurs-kinésithérapeutes</option>
                    <option value="4" disabled>Sages-femmes</option>
                    <option value="5" disabled>Chirurgiens dentistes</option>
                    </select>
                </div>
                <div class="col">
                <select class="form-select" id="select2" aria-label="Default select example">
                    <option disabled selected>Séléctionnez une tranche d'âge des professionnels de santé</option>
                    <option value="1">Moins de 65 ans</option>
                    <option value="2">Moins de 62 ans</option>
                    <option value="3">Sans borne d'âge</option>
                    </select>
                </div>
                <div class="col">
                    <select class="form-select" aria-label="Default select example">
                    <option disabled selected>Séléctionnez un plage temporelle</option>
                    <option value="1" disabled>2023</option>
                    <option value="2" disabled>2022</option>
                    <option value="3">2021</option>
                    </select>
                </div>
                <div class="col">
                    <select class="form-select" id="affMarq" aria-label="Default select example">
                    <option disabled selected value="1">Afficher les marqueurs</option>
                    <option value="1">oui</option>
                    <option value="0">non</option>
                    </select>
                </div>
                <div class="col">
                <form method="post" action="/traitement/import" enctype="multipart/form-data">
                    <div class="form-group" >
                    <input style="position: relative; left: 30px;padding:10px" type="file" name="csv_file" accept=".csv">
                    <br>
                    <button style="position: relative; left: 25px;" class="btn btn-primary" type="submit">Importer</button>
                    </div> 
                </form>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>



 <div id="map"></div>

 <script>
    var select2 = document.getElementById('select2');
    select2.addEventListener('change', updateResult);
    var affichageMarqueurs = document.getElementById('affMarq');
    affichageMarqueurs.addEventListener('change', updateResult);



    var mymap = L.map('map').setView([47.08, 2.39], 6);

    var base = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(mymap);

    // Créer un tableau pour stocker les coordonnées des points
    var points = [];

    // Créer un tableau pour stocker les intensités des points
    var intensites = [];

    // Ajouter des marqueurs à la couche correspondant à la heatmap
    <?php foreach ($marqueurs as $marqueur): ?>
        var latLng = [<?= floatval($marqueur['latitude']) ?>, <?= floatval($marqueur['longitude']) ?>];
        var intensite = <?= floatval($marqueur['APL_aux_medecins_generalistes']) ?>; // Remplacez 'intensite' par le nom de votre champ d'intensité
        points.push(latLng);
        intensites.push(intensite);
    <?php endforeach; ?>

    // Définir une échelle de couleurs personnalisée
    var gradient = {
    0.0: 'black',
    0.2: 'red',
    0.4: 'yellow',
    0.6: 'green',
    0.8: 'blue',
    1.0: 'purple'
    };


    

    //Permet de contraster les valeurs de la heatmap
    var maxIntensite = 0.1;


    var HeatMap = L.layerGroup();

    // Créer une heatmap à partir du tableau de points et d'intensités
    L.heatLayer(points, {
        radius: calculateRadius(mymap.getZoom()),
        blur: 20,
        gradient: gradient,
        max: maxIntensite,
    }).addTo(HeatMap);
    
    
    /*---------------------------------------------------------------------------------------------------------------------*/

    var marqueursAPL = L.markerClusterGroup();

    <?php foreach ($marqueurs as $marqueur): ?>
        // Convertir la valeur de l'APL en un nombre décimal
        var aplValue = <?= floatval(str_replace(',', '.', $marqueur['APL_aux_medecins_generalistes'])) ?>;


        // Définir la couleur en fonction de la valeur de l'APL
        var couleur = 'hsl(160, 100%, ' + (50 - aplValue * 10) + '%)';

        L.circleMarker([<?= $marqueur['latitude'] ?>, <?= $marqueur['longitude'] ?>], {
            radius: 8,
            color: couleur,
            fillOpacity: 0.7
        })
        .bindPopup('Nom de la ville : <?= $marqueur['city_code'] ?><br>APL: <?= $marqueur['APL_aux_medecins_generalistes'] ?>')
        .addTo(marqueursAPL);
    <?php endforeach; ?>

    var marqueursAPL65 = L.markerClusterGroup();

    <?php foreach ($marqueurs as $marqueur): ?>
        // Convertir la valeur de l'APL en un nombre décimal
        var aplValue = <?= floatval(str_replace(',', '.', $marqueur['APL_aux_medecins_generalistes65'])) ?>;


        // Définir la couleur en fonction de la valeur de l'APL
        var couleur = 'hsl(160, 100%, ' + (50 - aplValue * 10) + '%)';

        L.circleMarker([<?= $marqueur['latitude'] ?>, <?= $marqueur['longitude'] ?>], {
            radius: 8,
            color: couleur,
            fillOpacity: 0.7
        })
        .bindPopup('Nom de la ville : <?= $marqueur['city_code'] ?><br>APL: <?= $marqueur['APL_aux_medecins_generalistes65'] ?>')
        .addTo(marqueursAPL65);
    <?php endforeach; ?>

    var marqueursAPL62 = L.markerClusterGroup();

    <?php foreach ($marqueurs as $marqueur): ?>
        // Convertir la valeur de l'APL en un nombre décimal
        var aplValue = <?= floatval(str_replace(',', '.', $marqueur['APL_aux_medecins_generalistes62'])) ?>;


        // Définir la couleur en fonction de la valeur de l'APL
        var couleur = 'hsl(160, 100%, ' + (50 - aplValue * 10) + '%)';

        L.circleMarker([<?= $marqueur['latitude'] ?>, <?= $marqueur['longitude'] ?>], {
            radius: 8,
            color: couleur,
            fillOpacity: 0.7
        })
        .bindPopup('Nom de la ville : <?= $marqueur['city_code'] ?><br>APL: <?= $marqueur['APL_aux_medecins_generalistes62'] ?>')
        .addTo(marqueursAPL62);
    <?php endforeach; ?>

    
    var baseMaps = {
        mymap,
        HeatMap
    };

    /*var overlayMaps = {
        "Marqueurs": marqueursAPL,
        "Marqueurs62": marqueursAPL62,
        "Marqueurs65": marqueursAPL65,
    };*/

    var layerControl = L.control.layers(baseMaps).addTo(mymap);



    function updateResult(){

        mymap.removeLayer(marqueursAPL65);
        mymap.removeLayer(marqueursAPL62);
        mymap.removeLayer(marqueursAPL);
        var value2 = select2.value;
        var affichage = affichageMarqueurs.value;
        

        if(affichage=="1"){
            switch(select2.value){
                case '1':
                    marqueursAPL65.addTo(mymap);
                    break;
                case '2':
                    marqueursAPL62.addTo(mymap);
                    break;
                case '3':
                    marqueursAPL.addTo(mymap);
                    break;
            }
        }
    } 

    // fonction ajustant le radius en fonction du zoom
    function calculateRadius(zoom) {
    var baseRadius = 20;
    var minZoom = 10;  // Le niveau de zoom à partir duquel le radius sera constant
    return zoom >= minZoom ? baseRadius * Math.pow(2, zoom - minZoom) : baseRadius; 
    }

</script>


<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>

</body>
</html>

