<div class="client-profile-container">
    <div class="profile-header">
        <div class="profile-photo">
            <div class="photo-placeholder">
                <i class="fas fa-user-circle"></i>
            </div>
        </div>
        <div class="profile-info">
            <h1 class="client-name" id="nom"></h1>
            <div class="contact-info">
                <div class="contact-item">
                    <i class="fas fa-phone"></i>
                    <span id="contact"></span>
                </div>
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <span id="email"></span>
                </div>
            </div>
        </div>
    </div>

    <div class="profile-details">
        <div class="detail-card">
            <h2><i class="fas fa-map-marker-alt"></i> Adresse</h2>
            <div class="detail-content">
                <p id="adresse"></p>
            </div>
        </div>

        <div class="detail-card">
            <h2><i class="fas fa-calendar-alt"></i> Inscription</h2>
            <div class="detail-content">
                <p id="date"></p>
            </div>
        </div>
    </div>
</div>
  <script>
    const apiBase = "http://localhost/Projet_banque/project/ws";

        function ajax(method, url, data, callback) {
          const xhr = new XMLHttpRequest();
          xhr.open(method, apiBase + url, true);
          xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
          xhr.onreadystatechange = () => {
            if (xhr.readyState === 4 && xhr.status === 200) {
              callback(JSON.parse(xhr.responseText));
            }
          };
          xhr.send(data ? data : null);
        }
    // fonction pour charger les données du client
    document.addEventListener("DOMContentLoaded", function () {
    const params = new URLSearchParams(window.location.search);
    const id = params.get("id");
    
    if (id) {
        ajax("GET", `/client/${id}`, null, function (data) {
            document.getElementById("nom").innerText = data.nom_client;
            document.getElementById("contact").innerText = data.telephone;
            document.getElementById("email").innerText = data.email;
            document.getElementById("adresse").innerText = data.adresse;
            document.getElementById("date").innerText = formatDate(data.date_inscription);
        });
    } else {
        console.error("Aucun ID fourni dans l'URL !");
    }
});

// Fonction optionnelle pour formater la date
function formatDate(dateString) {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('fr-FR', options);
}
</script>
