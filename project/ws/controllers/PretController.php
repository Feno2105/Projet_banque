<?php
require_once __DIR__ . '/../models/Pret.php';
require_once __DIR__ . '/../helpers/Utils.php';



class PretController {

    public static function getById($id) {
        $pret = Pret::getById($id);
        Flight::json($pret);
    }

    public static function save(){
        $data = Flight::request()->data;
        $id = Pret::save($data);
        $dateFormatted = Utils::formatDate('2025-01-01'); // Optionnel selon ton besoin
        Flight::json(['message' => 'Prêt ajouté']);
    }
    public static function getAll() {
        $prets = Pret::getAll();
        Flight::json($prets);
    }

    public static function accept($id) {
        try {
            if(Pret::accept($id)) {
                Flight::json(['message' => 'Pret accepté']);
            }else{
                Flight::json(['message' => 'Erreur lors de l\'acceptation du pret'], 500);
            }
        } catch (\Throwable $th) {
            Flight::json(['message' => 'Erreur lors de l\'acceptation du pret: ' . $th->getMessage()], 500);
        }
    }

    public static function refuse($id) {
        try {
            if(Pret::refuse($id)) {
                Flight::json(['message' => 'Pret refusé']);
            }else{
                Flight::json(['message' => 'Erreur lors du refus du pret'], 500);
            }
        } catch (\Throwable $th) {
            Flight::json(['message' => 'Erreur lors du refus du pret: ' . $th->getMessage()], 500);
        }
    }
}
