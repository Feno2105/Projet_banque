<?php

require_once __DIR__.'/../db.php';

require_once __DIR__.'/../models/Status.php';
require_once __DIR__.'/../models/TypePretModel.php';

class Interet
{
    public static function getAll()
    {
        $db = getDB();
        $stmt = $db->query('select * from interet ORDER BY date_debut DESC');

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function getByMoisEtAnne($mois,$annee){
        
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
    $mois_fin = $mois + $TypePret['duree_mois'];
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

    public static function saveInteretParRemboursement($id,$data){
       $db = getDB();
       $stmt = $db->prepare('select interet_total from vue_interet_global WHERE id_pret = :id LIMIT 1');
       $stmt->bindParam(':id', $id, PDO::PARAM_INT);
       $stmt->execute();
       $interet = $stmt->fetch(PDO::FETCH_ASSOC);
       $stmt2 = $db->prepare('INSERT INTO interet_par_mois(id_pret, mois, annee, valeur) VALUES (:id_pret, :mois, :annee, :valeur)');
       $stmt2->bindParam(':id_pret', $id, PDO::PARAM_INT);
       $stmt2->bindParam(':mois', $data->mois, PDO::PARAM_INT);
       $stmt2->bindParam(':annee', $data->annee, PDO::PARAM_INT);
       $stmt2->bindParam(':valeur', $interet['interet_total'], PDO::PARAM_STR);
       $stmt2->execute();
       return $db->lastInsertId();
    }
    public static function getByIdParMois($data)
    {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM interet_par_mois WHERE id_pret = ? AND mois = ? AND annee = ?");
        $stmt->execute([$data->id_pret,$data->mois, $data->annee]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function listInteretParIntervalle($mois_debut, $annee_debut, $mois_fin, $annee_fin)
    {
        $db = getDB();
        $stmt = $db->prepare("select mois,annee,SUM(valeur) AS total from  interet_par_mois WHERE  mois >= :mois_debut AND mois <= :mois_fin AND annee >= :annee_debut AND annee <= :annee_fin GROUP BY mois,annee");
        $stmt->execute(['mois_debut' => $mois_debut, 'mois_fin' => $mois_fin, 'annee_debut' => $annee_debut, 'annee_fin' => $annee_fin]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
