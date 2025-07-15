<?php


require 'vendor/autoload.php';
require 'db.php';
require 'routes/pret_routes.php';
require 'routes/client_routes.php';
require 'routes/fond_routes.php';
require 'routes/sources_routes.php';
require 'routes/remboursement_routes.php';
require 'routes/type_prets_routes.php';
require 'routes/redirection_routes.php';
require 'routes/interet_routes.php';



Flight::start();
