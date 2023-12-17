<?php

namespace App\Controllers;

use CodeIgniter\Controller;

use League\Csv\Reader;

ini_set('max_execution_time', 1000); // 0 = Unlimited

class traitement extends BaseController
{
    public function fusionnerFichiers()
{
    // Chemin vers les fichiers
    $cheminCSV = FCPATH . 'nettoye.csv';
    $cheminJSON = FCPATH . 'cities.json';

    // Utiliser league/csv pour lire le fichier CSV
    $csv = Reader::createFromPath($cheminCSV, 'r');
    $csv->setHeaderOffset(0);

    // Lire le fichier JSON
    $jsonContent = file_get_contents($cheminJSON);
    $jsonData = json_decode($jsonContent, true);

    // Initialiser le résultat
    $resultat = [];

    // Limiter le nombre de lignes lues
    //$limit = 20;

    // Ouvrir le fichier CSV en mode lecture
    $handle = fopen($cheminCSV, 'r');

    // Ignorer l'en-tête
    $header = fgetcsv($handle);

    // Initialiser le compteur
    $count = 0;

    // Lire le fichier CSV ligne par ligne
    while (($row = fgetcsv($handle)) !== false){ //&& $count < $limit) {

        $element = array_combine($header, $row);

        // Clé de correspondance
        $cleJSON = 'insee_code';

        // Vérifier si la clé existe dans le fichier JSON (
        $libelleCommuneCSV = $element['Code commune'];

        // Parcourir les villes
        foreach ($jsonData['cities'] as $data) {
            $libelleCommuneJSON = $data[$cleJSON];

            if ($libelleCommuneCSV === $libelleCommuneJSON) {
                // Correspondance trouvée, ajouter les propriétés spécifiques au résultat final
                $resultat[] = [
                    'insee_code' => $data[$cleJSON],
                    'city_code' => $data['city_code'],
                    'latitude' => $data['latitude'],
                    'longitude' => $data['longitude'],
                    'department_name' => $data['department_name'],
                    'APL_aux_medecins_generalistes' => $element['APL aux medecins generalistes'],
                ];

                break; // Sortir de la boucle une fois qu'une correspondance a été trouvée
            }
        }

        // Incrémenter le compteur
        //$count++;
    }

    // Fermer le fichier CSV
    fclose($handle);

    // Vous avez maintenant les données fusionnées dans $resultat
    //return view('welcome_message', ['marqueurs' => $resultat]);
    return view('carto', ['marqueurs' => $resultat]);
    //print_r($resultat);
}
}
