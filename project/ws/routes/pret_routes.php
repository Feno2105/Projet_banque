<?php
require_once __DIR__ . '/../controllers/PretController.php';

Flight::route('GET /prets', ['PretController', 'getAll']);
Flight::route('POST /prets', ['PretController', 'save']);
Flight::route('GET /prets/accept/@id', ['PretController', 'accept']);
Flight::route('GET /prets/refuse/@id', ['PretController', 'refuse']);
