<?php

require_once __DIR__.'/../db.php';

require_once __DIR__.'/../models/Status.php';
require_once __DIR__.'/../models/TypePretModel.php';

class Interet
{
    public static function getAll()
    {
        $db = getDB();
        $stmt = $db->query('select * from view_pret ORDER BY date_debut DESC');

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function save($id,$data){
    $db = getDB();
    $TypePret = TypePretModel::getById($data->type_pret_id);
    $mensualite = $data->montant / $TypePret['duree_mois'];
    $interet = ($mensualite * ($TypePret['taux_interet'] / 1200));

    // Extraction de la date
    $date = new DateTime($data->date_debut);
    $annee = (int)$date->format('Y');
    $mois = (int)$date->format('m');

    $mois_debut = $mois;
    $annee_debut = $annee;
    $mois_fin = $mois + $TypePret['duree_mois'] - 1;
    $annee_fin = $annee_debut;
    if ($mois_fin > 12) {
        $annee_fin += 1;
        $mois_fin = ($mois_fin - 1) % 12 + 1;
    }
    $statut = 1;
    // Insertion de l'intérêt
    $stmt2 = $db->prepare("INSERT INTO interet (id_pret, mois_debut, annee_debut, mois_fin, annee_fin, valeur) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt2->execute([$id, $mois_debut, $annee_debut, $mois_fin, $annee_fin, $interet]);

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
