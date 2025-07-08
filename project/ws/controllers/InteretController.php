<?php
require_once __DIR__ . '/../models/Interet.php';
require_once __DIR__ . '/../helpers/Utils.php';



class PretController {
    public static function getAll() {
        $prets = Interet::getAll();
        Flight::json($prets);
    }
}