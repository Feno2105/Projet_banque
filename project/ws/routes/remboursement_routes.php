<?php
require_once __DIR__ . '/../controllers/RemboursementController.php';

Flight::route('GET /remboursement', ['RemboursementController', 'getAll']);
Flight::route('GET /remboursement/@id', ['RemboursementController', 'getById']);
Flight::route('POST /remboursement', ['RemboursementController', 'create']);
Flight::route('PUT /remboursement/@id', ['RemboursementController', 'update']);
// Flight::route('DELETE /remboursement/@id', ['RemboursementController', 'delete']);