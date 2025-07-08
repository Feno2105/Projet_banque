<?php
require_once __DIR__ . '/../models/SourceModel.php';
require_once __DIR__ . '/../helpers/Utils.php';


class SourcesController {
    public static function getAll() {
        $fonds = SourceModel::getAll();
        Flight::json($fonds);
    }

    public static function getById($id) {
        $fond = SourceModel::getById($id);
        Flight::json($fond);
    }

    // public static function create() {
    //     $data = Flight::request()->data;
    //     $id = SourceModel::create($data);
    //     $dateFormatted = Utils::formatDate('2025-01-01'); // Optionnel selon ton besoin
    //     Flight::json(['message' => 'Fonds ajouté', 'id' => $id]);
    // }

    // public static function update($id) {
    //     parse_str(file_get_contents("php://input"), $data);
    //     $data = (object)$data;
        
    //     SourceModel::update($id, $data);
    //     Flight::json(['message' => 'Fonds modifié']);
    // }

    // public static function delete($id) {
    //     SourceModel::delete($id);
    //     Flight::json(['message' => 'Fonds supprimé']);
    // }
}
