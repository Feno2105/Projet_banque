<?php
require_once __DIR__ . '/../models/TypePretModel.php';
require_once __DIR__ . '/../helpers/Utils.php';



class TypePretController {
    public static function getAll() {
        $TypePretModels = TypePretModel::getAll();
        Flight::json($TypePretModels);
    }

    public static function getById($id) {
        $TypePretModel = TypePretModel::getById($id);
        Flight::json($TypePretModel);
    }

    public static function create() {
        $data = Flight::request()->data;
        $id = TypePretModel::create($data);
        $dateFormatted = Utils::formatDate('2025-01-01');
        Flight::json(['message' => 'Type Pret ajouté', 'id' => $id]);
    }

    public static function update($id) {
        parse_str(file_get_contents("php://input"), $data);
        $data = (object)$data; // Pour conserver la compatibilité avec votre code existant
        
        TypePretModel::update($id, $data);
        Flight::json(['message' => 'Type Pret modifié']);
    }

    public static function delete($id) {
        TypePretModel::delete($id);
        Flight::json(['message' => 'Type Pret supprimé']);
    }
}
