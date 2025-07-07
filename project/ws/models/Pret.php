<?php

require_once __DIR__.'/../db.php';

require_once __DIR__.'/../models/Status.php';
class Pret
{
    public static function getAll()
    {
        $db = getDB();
        $stmt = $db->query('SELECT * FROM view_pret');

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
