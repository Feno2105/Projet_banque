<?php
require_once __DIR__ . '/../models/Pret.php';
require_once __DIR__ . '/../helpers/Utils.php';



class PretController {
    public static function getAll() {
        $prets = Pret::getAll();
        Flight::json($prets);
    }
}
