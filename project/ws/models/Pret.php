<?php

require_once __DIR__.'/../db.php';

require_once __DIR__.'/../models/Status.php';
require_once __DIR__.'/../models/TypePretModel.php';
require_once __DIR__.'/../models/Interet.php';

class Pret
{

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
        $id_pret = $db->lastInsertId();
        $interet = Interet::save($id_pret, $data);
        return $id_pret;
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
    public static function remboursement($id,$assurance)
    {
        $db = getDB();

        $stmt = $db->prepare('SELECT p.reste_a_payer, p.montant,p.mensualite, tp.valeur_assurance, tp.duree_mois,tp.nom_type_pret, tp.taux_interet FROM pret p JOIN type_pret tp ON p.type_pret_id = tp.id_type_pret WHERE p.id_pret = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $pret = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$pret) {
            return ['success' => false, 'message' => 'Prêt non trouvé'];
        }
        if ($pret['reste_a_payer']==0) {
            return ['success' => false, 'message' => 'Mensualité trop élevée, remboursement impossible'];    
        }
        if ($assurance) {
            $assu = ($pret['montant']/$pret['duree_mois'])*$pret['valeur_assurance'];
            if ($pret['reste_a_payer'] < $pret['mensualite'] +$assu) {
                $stmt = $db->prepare('UPDATE pret SET reste_a_payer = 0 WHERE id_pret = :id');            
            }
            else{
                $stmt = $db->prepare('UPDATE pret SET reste_a_payer = (reste_a_payer - mensualite - :assu) WHERE id_pret = :id');
                $stmt->bindParam(':assu', $assu, PDO::PARAM_STR); 
            }
        }
        else{
            if ($pret['reste_a_payer'] < $pret['mensualite']) {
                $stmt = $db->prepare('UPDATE pret SET reste_a_payer = 0 WHERE id_pret = :id');            
            }
            else{
                $stmt = $db->prepare('UPDATE pret SET reste_a_payer = (reste_a_payer-mensualite) WHERE id_pret = :id');
            }
        }
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);    
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
