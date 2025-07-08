<div class="bank-document-container">
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-file-invoice-dollar icon-lg me-2"></i>
            Détails du prêt
        </h1>
    </div>

    <div class="card document-card">
        <div class="card-body">
            <div class="document-actions mb-4">
                <a href="pretPdf.php?id_pret=<?= $_GET['id_pret'] ?? '' ?>" class="btn btn-primary">
                    <i class="fas fa-file-pdf me-2"></i>Exporter en PDF
                </a>
            </div>

            <div id="affichage_response" class="loan-details">
                <!-- Le contenu sera chargé dynamiquement ici -->
                <div class="text-center py-5">
                    <i class="fas fa-spinner fa-spin fa-2x text-muted"></i>
                    <p>Chargement des détails du prêt...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const apiBase = "/Projet_banque/project/ws";

    function ajax(method, url, data, callback) {
        const xhr = new XMLHttpRequest();
        xhr.open(method, apiBase + url, true);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.onreadystatechange = () => {
            if (xhr.readyState === 4 && xhr.status === 200) {
                callback(JSON.parse(xhr.responseText));
            }
        };
        xhr.send(data ? data : null);
    }

    document.addEventListener("DOMContentLoaded", function() {
        const params = new URLSearchParams(window.location.search);
        const id_pret = params.get("id_pret");
        
        if (id_pret) {
            ajax("GET", `/prets/${id_pret}`, null, function(data) {
                document.getElementById("affichage_response").innerHTML = `
                    <div class="loan-section">
                        <h2 class="section-title"><i class="fas fa-user-tie me-2"></i>Emprunteur</h2>
                        <div class="loan-info-grid">
                            <div class="info-item">
                                <span class="info-label">Nom :</span>
                                <span class="info-value">${data.nom_client}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Email :</span>
                                <span class="info-value">${data.email}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Téléphone :</span>
                                <span class="info-value">${data.telephone}</span>
                            </div>
                        </div>
                    </div>

                    <hr class="section-divider">

                    <div class="loan-section">
                        <h2 class="section-title"><i class="fas fa-bullseye me-2"></i>Objet du prêt</h2>
                        <p class="loan-text">Le prêteur accorde à l'emprunteur un prêt d'un montant de <strong>${data.montant} Ariary</strong> destiné à financer des besoins personnels.</p>
                    </div>

                    <div class="loan-section">
                        <h2 class="section-title"><i class="fas fa-calendar-alt me-2"></i>Durée du prêt</h2>
                        <p class="loan-text">La durée du prêt est de <strong>${data.duree_mois} mois</strong>, à compter du <strong>${data.date_debut}</strong>.</p>
                    </div>

                    <div class="loan-section">
                        <h2 class="section-title"><i class="fas fa-percentage me-2"></i>Taux d'intérêt</h2>
                        <p class="loan-text">Le taux d'intérêt annuel fixe est de <strong>${data.taux_interet}%</strong>.</p>
                    </div>

                    <div class="loan-section">
                        <h2 class="section-title"><i class="fas fa-calculator me-2"></i>Modalités de remboursement</h2>
                        <p class="loan-text">L'emprunteur s'engage à rembourser le prêt par mensualités constantes de <strong>${data.mensualite} Ariary</strong>, comprenant le capital et les intérêts.</p>
                    </div>

                    <div class="loan-section">
                        <h2 class="section-title"><i class="fas fa-shield-alt me-2"></i>Assurance</h2>
                        <p class="loan-text">[Détails de l'assurance à compléter]</p>
                    </div>

                    <div class="loan-section">
                        <h2 class="section-title"><i class="fas fa-signature me-2"></i>Signatures</h2>
                        <div class="signature-container">
                            <div class="signature-item">
                                <p>Fait à Antananarivo, le ${data.date_debut}</p>
                                <div class="signature-line"></div>
                                <p>Signature du Prêteur</p>
                            </div>
                            <div class="signature-item">
                                <div class="signature-line"></div>
                                <p>Signature de l'Emprunteur</p>
                            </div>
                        </div>
                    </div>
                `;
            });
        } else {
            document.getElementById("affichage_response").innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Aucun ID de prêt fourni dans l'URL !
                </div>
            `;
        }
    });
</script>