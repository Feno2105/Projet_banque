<?php
// Pas d'espace ou ligne vide avant le tag PHP
require("fpdf186/fpdf.php");

function convert($str){
    return mb_convert_encoding($str, 'windows-1252', 'UTF-8');
}

// Vérifie les paramètres
if (isset($_GET['id_pret']) && isset($_GET['action']) && $_GET['action'] === "pdf") {
    $id_pret = $_GET['id_pret'];
    $apiUrl = "/Projet_banque/project/ws/prets/$id_pret";

    $json = @file_get_contents($apiUrl);
    if ($json === false) {
        http_response_code(500);
        exit("Erreur lors de l'appel à l'API.");
    }

    $data = json_decode($json, true);
    if (!$data) {
        http_response_code(500);
        exit("Erreur de décodage JSON.");
    }

    // Aucune sortie HTML avant les headers
    header("Content-Type: application/pdf");
    header("Content-Disposition: inline; filename=contrat_pret_{$data['id_pret']}.pdf");

    // Génération du PDF
    $pdf = new FPDF();
    $pdf->AddPage();

    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, convert('CONTRAT DE PRÊT BANCAIRE'), 0, 1, 'C');

    $pdf->Ln(10);
    $pdf->SetFont('Arial', '', 12);

    // Prêteur
    $pdf->Cell(0, 10, convert("Prêteur"), 0, 1, 'L');
    $pdf->MultiCell(0, 8, convert("Nom : {$data['nom_client']}"));
    $pdf->MultiCell(0, 8, convert("Email : {$data['email']}"));
    $pdf->MultiCell(0, 8, convert("Téléphone : {$data['telephone']}"));
    $pdf->Ln(5);

    // Objet du prêt
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, convert('Objet du prêt'), 0, 1, 'L');
    $pdf->SetFont('Arial', '', 12);
    $pdf->MultiCell(0, 8, convert("Le prêteur accorde à l’emprunteur un prêt d’un montant de {$data['montant']} Ariary destiné à financer des besoins personnels."));
    $pdf->Ln(5);

    // Durée du prêt
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, convert('Durée du prêt'), 0, 1, 'L');
    $pdf->SetFont('Arial', '', 12);
    $pdf->MultiCell(0, 8, convert("La durée du prêt est de {$data['duree_mois']} mois, à compter du {$data['date_debut']}."));
    $pdf->Ln(5);

    // Taux d'intérêt
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, convert("Taux d'intérêt"), 0, 1, 'L');
    $pdf->SetFont('Arial', '', 12);
    $pdf->MultiCell(0, 8, convert("Le taux d’intérêt annuel fixe est de {$data['taux_interet']} %."));
    $pdf->Ln(5);

    // Modalités de remboursement
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, convert('Modalités de remboursement'), 0, 1, 'L');
    $pdf->SetFont('Arial', '', 12);
    $pdf->MultiCell(0, 8, convert("L’emprunteur s’engage à rembourser le prêt par mensualités constantes de {$data['mensualite']} Ariary, comprenant le capital et les intérêts."));
    $pdf->Ln(10);

    // Assurance
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, convert('Assurance'), 0, 1, 'L');
    $pdf->SetFont('Arial', '', 12);
    $pdf->MultiCell(0, 8, convert("Le prêt est soumis aux conditions d’assurance définies par la banque (à compléter selon le cas)."));
    $pdf->Ln(10);

    // Signatures
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, convert('Signatures'), 0, 1, 'L');
    $pdf->SetFont('Arial', '', 12);
    $pdf->MultiCell(0, 8, convert("Fait à Antananarivo, le {$data['date_debut']}.\n\nSignature du Prêteur : ____________________\n\nSignature de l’Emprunteur : ____________________"));

    $pdf->Output("I");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<h1>Génération automatique du contrat PDF...</h1>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const params = new URLSearchParams(window.location.search);
    const id_pret = params.get("id_pret");
    if (id_pret) {
        window.location.href = `?id_pret=${id_pret}&action=pdf`;
    } else {
        document.body.innerHTML = "<p style='color:red;'>Aucun ID de prêt fourni.</p>";
    }
});
</script>
</body>
</html>
