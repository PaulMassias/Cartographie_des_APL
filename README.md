# Cartographie des Accessibilités Potentielles Localisées

## Installation
Pour pouvoir installer l'application et la faire tourner localement en serveur de développement il vous faut télécharger CodeIgniter4 à [cette adresse](https://codeigniter.com/user_guide/installation/installing_manual.html). Une fois la version la plus récente téléchargé, vous devrez cloner ce porjet dans un dossier vierge de votre choix. Ensuite glissez le dossier vendor de l'archive CodeIgniter 4 dans le projet cloné. 
Ensuite depuis un terminal et à la racine du projet, lancez la commande 

    npm install

puis la commande

    php spark serve

Rendez vous ensuite à cette adresse : http://localhost:8081/traitement et vous verrez apparaître la page principale de l'application après un certain temps de chargement ( environ 2-3 minutes ).

## Fonctionnement global
L'application se découpe en 3 parties:
1. Données
Les données dans l'application sont pour l'instant représenté commedes fichiers que vous truverez dans le répertoire "public" de votre projet. Le premier est le fichier cities.json qui est tiré de [data.gouv.fr](https://www.data.gouv.fr/fr/datasets/villes-de-france/) et qui décrit les différentes villes de france en format json. Le second est le fichier nettoye.csv, ce dernier reprend lui les informations concernant les APL extraite depuis l'applicatif [Shiny](https://drees.shinyapps.io/carto-apl/). 
   
2. Traitement
Vous trouverez le contrôleur principal dans App/Controllers il s'appelle traitement.php et vous trouverez dans ce dernier le "back-office". Vous y trouverez des fonctions pour la récupération de données, pour l'import de fichiers CSV ou encore pour l'export de données depuis un CSV vers une BDD.

3. Vue
Enfin pour voir le code du front-end allez ouvrir le fichier suivant App/Views/Carto.php. Ce fichier se compose d'une première partie de html qui crée l'ossature du site et en dessous vous trouverez une partie conséquente de traitements pour rendre les données utilisables pour le plugin Leaflet.

## Recherches documentaires

Vous trouverez aussi dans ce projet les différents rapports et recherches documentaires que nous avons pu mener, vous les retrouverez dans le dossier :"Recherche" à la racine du projet.
