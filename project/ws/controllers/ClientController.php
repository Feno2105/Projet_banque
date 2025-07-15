<?php
require_once __DIR__ . '/../models/Etudiant.php';
require_once __DIR__ . '/../helpers/Utils.php';
require_once __DIR__ . '/../models/Client.php';



class ClientController {
    public static function getAll() {
        $clients = Client::getAll();
        Flight::json($clients);
    }

    public static function getById($id) {
        $client = Client::getById($id);
        Flight::json($client);
    }

    public static function create() {
        $data = Flight::request()->data;
        $id = Client::create($data);
        $dateFormatted = Utils::formatDate('2025-01-01');
        Flight::json(['message' => 'Client ajouté avec succès', 'id' => $id]);
    }

    public static function update($id) {
        parse_str(file_get_contents("php://input"), $data);
        $data = (object)$data; // Pour conserver la compatibilité avec votre code existant
        Client::update($id, $data);
        Flight::json(['message' => 'Client modifié suivants les nouvelles caracteristiques']);
    }

    public static function delete($id) {
        Client::delete($id);
        Flight::json(['message' => 'Client supprimé']);
    }
    public static function profil() {
        $id = Flight::request()->query['id'];
        $client = Client::getById($id);
        if ($client) {
            Flight::json($client);
        } else {
            Flight::json(['message' => 'Client non trouvé'], 404);
        }
    }
}
