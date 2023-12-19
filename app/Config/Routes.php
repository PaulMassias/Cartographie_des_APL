<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/traitement', 'traitement::fusionnerFichiers');
$routes->get('/importerjson','traitement::importerJson');
