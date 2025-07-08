<?php

require_once __DIR__.'/../db.php';

require_once __DIR__.'/../models/Status.php';
require_once __DIR__.'/../models/TypePretModel.php';

class Pret
{
    public static function getById($id) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM view_pret WHERE id_pret = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getAll()
    {
        $db = getDB();
        $stmt = $db->query('select * from view_pret ORDER BY date_debut DESC');

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function save($data){
        $db = getDB();
        $TypePret = TypePretModel::getById($data->type_pret_id);
        $mesualite = $data->montant / $TypePret['duree_mois'];
        $mensualite = $mesualite + ($mesualite * ($TypePret['taux_interet'] / 1200));
        
        $statut = 1;
        $stmt = $db->prepare("INSERT INTO pret (client_id, type_pret_id,mensualite,montant, reste_a_payer,date_debut,statut) VALUES (?, ?, ?, ?,?,?,?)");
        $stmt->execute([$data->client_id, $data->type_pret_id, $mensualite, $data->montant, $data->montant, $data->date_debut,$statut]);
        return $db->lastInsertId();
    }
    public static function accept($id)
    {
        $db = getDB();

        $status = Status::findByLibelle('Accepté');

        if (!$status) {
            throw new Exception('Status "accepté" not found');
        }

        $stmt = $db->prepare('UPDATE pret SET statut = :id_status WHERE id_pret = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':id_status', $status['id_statut_pret'], PDO::PARAM_INT);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public static function refuse($id)
    {
        $db = getDB();

        $status = Status::findByLibelle('Refusé');

        if (!$status) {
            throw new Exception('Status "Refusé" not found');
        }

        $stmt = $db->prepare('UPDATE pret SET statut = :id_status WHERE id_pret = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':id_status', $status['id_statut_pret'], PDO::PARAM_INT);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
