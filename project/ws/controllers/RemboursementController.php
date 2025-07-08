<?php
require_once __DIR__ . '/../models/RemboursementModel.php';
require_once __DIR__ . '/../helpers/Utils.php';
require_once __DIR__ . '/../models/Pret.php';
require_once __DIR__ . '/../models/Interet.php';



class RemboursementController {
    public static function getAll() {
        $RemboursementModels = RemboursementModel::getAll();
        Flight::json($RemboursementModels);
    }

    public static function getById($id) {
        $RemboursementModel = RemboursementModel::getById($id);
        Flight::json($RemboursementModel);
    }

    public static function create() {
        $data = Flight::request()->data;
    
        $id_pret = $data->id_pret;
        $mois = (int)$data->mois_debut;
        $annee = (int)$data->annee_debut;
        $mois_fin = (int)$data->mois_fin;
        $annee_fin = (int)$data->annee_fin;
        // $assurance = isset($data->assurance) ? 1 : 0;
    
        $inserted = [];

        while ($annee < $annee_fin || ($annee == $annee_fin && $mois <= $mois_fin)) {
            $entry = (object)[
                'id_pret' => $id_pret,
                'mois' => $mois,
                'annee' => $annee,
            ];
            if (Pret::remboursement($id_pret,true)) {
                RemboursementModel::create($entry);
            }
            if(!Interet::getByIdParMois($entry)){
                $inserted[] = Interet::saveInteretParRemboursement($id_pret,$entry);
            }
            $mois++;
            if ($mois > 12) {
                $mois = 1;
                $annee++;
            }
        }
        Flight::json(['message' => 'Remboursements ajoutés avec succès','ids' => $inserted]);
    }
    

    public static function update($id) {
        parse_str(file_get_contents("php://input"), $data);
        $data = (object)$data; // Pour conserver la compatibilité avec votre code existant
        RemboursementModel::update($id, $data);
        Flight::json(['message' => 'RemboursementModel modifié suivants les nouvelles caracteristiques']);
    }

    public static function delete($id) {
        RemboursementModel::delete($id);
        Flight::json(['message' => 'RemboursementModel supprimé']);
    }
    public static function profil() {
        $id = Flight::request()->query['id'];
        $RemboursementModel = RemboursementModel::getById($id);
        if ($RemboursementModel) {
            Flight::json($RemboursementModel);
        } else {
            Flight::json(['message' => 'RemboursementModel non trouvé'], 404);
        }
    }
}
