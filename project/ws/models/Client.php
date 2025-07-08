<?php
require_once __DIR__ . '/../db.php';

class Client {
    public static function getAll() {
        $db = getDB();
        $stmt = $db->query("SELECT * FROM client");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM client WHERE id_client = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($data) {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO client (nom_client, email, telephone, adresse,date_inscription) VALUES (?, ?, ?, ?)");
        $stmt->execute([$data->nom_client, $data->email, $data->telephone, $data->adresse, $data->date_inscription]);
        return $db->lastInsertId();
    }

    public static function update($id, $data) {
        $db = getDB();
        $stmt = $db->prepare("UPDATE client SET nom_client = ?, email = ?, telephone = ?, adresse = ?, date_inscription = ? WHERE id_client = ?");
        $stmt->execute([$data->nom, $data->email, $data->telephone, $data->adresse,$data->date_inscription, $id]);
    }

    public static function delete($id) {
        $db = getDB();
        $stmt = $db->prepare("DELETE FROM client WHERE id_client = ?");
        $stmt->execute([$id]);
    }
}
