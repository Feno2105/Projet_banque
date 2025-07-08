<?php
require_once __DIR__ . '/../controllers/RedirectionController.php';

Flight::route('GET /home_', ['RedirectionController', 'home']);
Flight::route('GET /fonds_', ['RedirectionController', 'FondsEtablissement']);
Flight::route('GET /profil_', ['RedirectionController', 'profilUser']);
Flight::route('GET /pret_', ['RedirectionController', 'ListePret']);
Flight::route('GET /typePret_', ['RedirectionController', 'TypePret']);

// Ajoute d'autres routes si besoin