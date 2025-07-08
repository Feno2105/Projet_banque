<?php

require_once __DIR__.'/../db.php';

class Status
{
    public static function getAll()
    {
        $db = getDB();
        $stmt = $db->query('SELECT * FROM statut_pret');

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // find by libelle
    public static function findByLibelle($libelle)
    {
        $db = getDB();
        $stmt = $db->prepare('SELECT * FROM statut_pret WHERE libelle = :libelle');
        $stmt->bindParam(':libelle', $libelle);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
