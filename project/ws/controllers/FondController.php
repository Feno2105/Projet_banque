<?php
require_once __DIR__ . '/../models/FondModel.php';
require_once __DIR__ . '/../helpers/Utils.php';


class FondController {
    public static function getAll() {
        $fonds = FondModel::getAll();
        Flight::json($fonds);
    }

    public static function getById($id) {
        $fond = FondModel::getById($id);
        Flight::json($fond);
    }

    public static function create() {
        $data = Flight::request()->data;
        $id = FondModel::create($data);
        $dateFormatted = Utils::formatDate('2025-01-01'); // Optionnel selon ton besoin
        Flight::json(['message' => 'Fonds ajouté', 'id' => $id]);
    }

    public static function update($id) {
        parse_str(file_get_contents("php://input"), $data);
        $data = (object)$data;
        
        FondModel::update($id, $data);
        Flight::json(['message' => 'Fonds modifié']);
    }

    public static function delete($id) {
        FondModel::delete($id);
        Flight::json(['message' => 'Fonds supprimé']);
    }
    public static function getFonds(){
        $data = Flight::request()->data;
        $result = FondModel::viewMontant($data->mois,$data->annee);
        Flight::json($result);
    }
}
