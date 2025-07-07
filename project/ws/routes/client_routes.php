<?php
require_once __DIR__ . '/../controllers/ClientController.php';

Flight::route('GET /client', ['ClientController', 'getAll']);
Flight::route('GET /client/@id', ['ClientController', 'getById']);
Flight::route('POST /client', ['ClientController', 'create']);
Flight::route('PUT /client/@id', ['ClientController', 'update']);
Flight::route('DELETE /client/@id', ['ClientController', 'delete']);
Flight::route('GET /client/profil/@id', function($id) {
    $client = ClientController::getById($id); // Exemple avec l'ID 1
    Flight::json($client);
});
