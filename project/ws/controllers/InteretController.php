<?php
require_once __DIR__ . '/../models/Interet.php';
require_once __DIR__ . '/../helpers/Utils.php';



class InteretController {
    public static function getAll() {
        $prets = Interet::getAll();
        Flight::json($prets);
    }
    public static function listInteretParIntervalle($mois_debut, $annee_debut, $mois_fin, $annee_fin){
         // Validation des paramètres
         if (!is_numeric($mois_debut) || !is_numeric($annee_debut) || !is_numeric($mois_fin) || !is_numeric($annee_fin)) {
            Flight::json(['error' => 'Invalid parameters'], 400);
            return;
        }
        if ($mois_debut < 1 || $mois_debut > 12 || $mois_fin < 1 || $mois_fin > 12) {
            Flight::json(['error' => 'Mois invalide'], 400);
            return;
        }
        $interets = Interet::listInteretParIntervalle($mois_debut, $annee_debut, $mois_fin, $annee_fin);
        if ($interets) {
            Flight::json($interets);
        } else {
            Flight::json(['message' => 'Aucun intérêt trouvé pour cette période'], 404);
        }
    }
}