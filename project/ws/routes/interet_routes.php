<?php
require_once __DIR__ . '/../controllers/InteretController.php';

Flight::route('GET /interets', ['PretController', 'getAll']);
Flight::route('GET /interets/@mois_debut/@annee_debut/@mois_fin/@annee_fin', ['InteretController', 'listInteretParIntervalle']);
