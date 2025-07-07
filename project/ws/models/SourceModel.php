<?php
require_once __DIR__ . '/../db.php';

class SourceModel {
    public static function getAll() {
        $db = getDB();
        $stmt = $db->query("SELECT * FROM source_fond");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM source_fond WHERE id_source_fond = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($data) {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO source_fond (nom_source) VALUES (?)");
        $stmt->execute([$data->nom_source]);
        return $db->lastInsertId();
    }

    public static function update($id, $data) {
        $db = getDB();
        $stmt = $db->prepare("UPDATE source_fond SET nom_source = ? WHERE id_source_fond = ?");
        $stmt->execute([$data->nom_source, $id]);
    }

    public static function delete($id) {
        $db = getDB();
        $stmt = $db->prepare("DELETE FROM source_fond WHERE id_source_fond = ?");
        $stmt->execute([$id]);
    }
}
