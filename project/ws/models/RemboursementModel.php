<?php
require_once __DIR__ . '/../db.php';

class RemboursementModel {
    public static function getAll() {
        $db = getDB();
        $stmt = $db->query("SELECT * FROM remboursement");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM remboursement WHERE id_remboursement = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($data) {
        $db = getDB();
        $stmt = $db->prepare("
            INSERT INTO remboursement (id_pret, mois, annee)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([
            $data->id_pret,
            $data->mois,
            $data->annee
        ]);
        return $db->lastInsertId();
    }

    public static function update($id, $data) {
        $db = getDB();
        $stmt = $db->prepare("
            UPDATE remboursement
            SET id_pret = ?, mois = ?, annee = ?
            WHERE id_remboursement = ?
        ");
        $stmt->execute([
            $data->id_pret,
            $data->mois,
            $data->annee,
            $id
        ]);
    }

    public static function delete($id) {
        $db = getDB();
        $stmt = $db->prepare("DELETE FROM remboursement WHERE id_remboursement = ?");
        $stmt->execute([$id]);
    }
    public static function verifyDate($id_pret, $mois, $annee) {
        $db = getDB();
    
        $stmt = $db->prepare('
            SELECT 1 FROM remboursement 
            WHERE id_pret = :id_pret AND mois = :mois AND annee = :annee 
            LIMIT 1
        ');
        $stmt->execute([
            ':id_pret' => $id_pret,
            ':mois' => $mois,
            ':annee' => $annee
        ]);
    
        return $stmt->fetch() === false;
    }
    
}
