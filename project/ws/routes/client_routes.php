<?php
require_once __DIR__ . '/../controllers/ClientController.php';

Flight::route('GET /client', ['ClientController', 'getAll']);
Flight::route('GET /client/@id', ['ClientController', 'getById']);
Flight::route('POST /client', ['ClientController', 'create']);
Flight::route('PUT /client/@id', ['ClientController', 'update']);
Flight::route('DELETE /client/@id', ['ClientController', 'delete']);

