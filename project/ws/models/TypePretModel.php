<?php
require_once __DIR__ . '/../db.php';

class TypePretModel {
    public static function getAll() {
        $db = getDB();
        $stmt = $db->query("SELECT * FROM type_pret ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM type_pret WHERE id_type_pret = ? ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($data) {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO type_pret (nom_type_pret,taux_interet,duree_mois,montant_min,montant_max) VALUES (?,?,?,?,?)");
        $stmt->execute([$data->nom_type_pret, $data->taux_interet, $data->duree_mois, $data->montant_min, $data->montant_max]);        
        return $db->lastInsertId();
    }

    public static function update($id, $data) {
        $db = getDB();
        $stmt = $db->prepare("UPDATE type_pret  SET nom_type_pret = ?, taux_interet = ?, duree_mois = ?, montant_min = ?, montant_max = ?  WHERE id_type_pret = ?");
        $stmt->execute([$data->nom_type_pret, $data->taux_interet, $data->duree_mois, $data->montant_min, $data->montant_max, $id]);
    }
    

    public static function delete($id) {
        $db = getDB();
        $stmt = $db->prepare("DELETE FROM type_pret WHERE id_type_pret = ?");
        $stmt->execute([$id]);
    }
}
