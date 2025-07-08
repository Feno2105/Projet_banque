<?php
require_once __DIR__ . '/../controllers/RedirectionController.php';

Flight::route('GET /home_', ['RedirectionController', 'home']);
Flight::route('GET /fonds_', ['RedirectionController', 'FondsEtablissement']);
Flight::route('GET /profil_', ['RedirectionController', 'profilUser']);
Flight::route('GET /pret_', ['RedirectionController', 'ListePret']);
Flight::route('GET /typePret_', ['RedirectionController', 'TypePret']);
Flight::route('GET /rembourser_',['RedirectionController','rembourser']);
Flight::route('GET /show_pret_', ['RedirectionController', 'show']);
Flight::route('GET /pret_pdf_', ['RedirectionController', 'pretPdf']);
Flight::route('GET /simulation_', ['RedirectionController', 'simulation']);
Flight::route('GET /interet_', ['RedirectionController', 'interet']);


// Ajoute d'autres routes si besoin