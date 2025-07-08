<?php
require_once __DIR__ . '/../db.php';

class FondModel {
    public static function getAll() {
        $db = getDB();
        $stmt = $db->query("SELECT * FROM fonds_etablissement AS f JOIN source_fond AS s ON s.id_source_fond =  f.source");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM fonds_etablissement AS f JOIN source_fond AS s ON s.id_source_fond =  f.source WHERE id_fonds_etablissement = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($data) {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO fonds_etablissement (date_ajout, montant, source, description) VALUES (?, ?, ?, ?)");
        $stmt->execute([$data->date_ajout, $data->montant, $data->source, $data->description]);
        return $db->lastInsertId();
    }

    public static function update($id, $data) {
        $db = getDB();
        $stmt = $db->prepare("UPDATE fonds_etablissement SET date_ajout = ?, montant = ?, source = ?, description = ? WHERE id_fonds_etablissement = ?");
        $stmt->execute([$data->date_ajout, $data->montant, $data->source, $data->description, $id]);
    }

    public static function delete($id) {
        $db = getDB();
        $stmt = $db->prepare("DELETE FROM fonds_etablissement WHERE id_fonds_etablissement = ?");
        $stmt->execute([$id]);
    }
}
