<?php
require_once __DIR__ . '/../controllers/SourcesController.php';

Flight::route('GET /sources', ['SourcesController', 'getAll']);
Flight::route('GET /sources/@id', ['SourcesController', 'getById']);
// Flight::route('POST /sources', ['SourcesController', 'create']);
// Flight::route('PUT /sources/@id', ['SourcesController', 'update']);
// Flight::route('DELETE /sources/@id', ['SourcesController', 'delete']);