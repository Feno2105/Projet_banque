<?php
class RedirectionController {
    public static function interet() {
        $title = "Liste des intérêts";
        $content = __DIR__ . '/../views/interet.php';
        include __DIR__ . '/../template.php';
    }

    public static function pretPdf() {
        $title = "Show pret pdf";
        $content = __DIR__ . '/../views/pretPdf.php';
        include __DIR__ . '/../template.php';
    }
    
    public static function show() {
        $title = "Show pret";
        $content = __DIR__ . '/../views/show_pret.php';
        include __DIR__ . '/../template.php';
    }
    public static function simulation() {
        $title = "Show pret";
        $content = __DIR__ . '/../views/simulation.php';
        include __DIR__ . '/../template.php';
    }
    
    public static function home() {
        $title = "Accueil";
        $content = __DIR__ . '/../views/acceuil.php';
        include __DIR__ . '/../template.php';
    }

    public static function profilUser() {
        $title = "Profil";
        $content = __DIR__ . '/../views/profil.php';
        include __DIR__ . '/../template.php';
    }

    public static function ListePret() {
        $title = "Prêts";
        $content = __DIR__ . '/../views/pret.php';
        include __DIR__ . '/../template.php';
    }

    public static function TypePret() {
        $title = "Types de Prêts";
        $content = __DIR__ . '/../views/type_pret.php';
        include __DIR__ . '/../template.php';
    }
    public static function FondsEtablissement() {
        $title = "Fonds d'Établissement";
        $content = __DIR__ . '/../views/fond_etablissement.php';
        include __DIR__ . '/../template.php';
    }
    public static function rembourser() {
        $title = "Fonds d'Établissement";
        $content = __DIR__ . '/../views/rembourser.php';
        include __DIR__ . '/../template.php';
    }
}