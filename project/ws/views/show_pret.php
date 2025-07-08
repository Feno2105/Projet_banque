
 
  <h1>Récupération de l'ID du prêt</h1>
  <div id="affichage_response"></div>
<script>
    const apiBase = "http://localhost/Projet_banque/project/ws";

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
    // fonction pour charger les données du client
    document.addEventListener("DOMContentLoaded", function () { // Cette fonction sera appelée dès que la page est chargée grace au DOMContentLoaded
    // recupêration de l Id du client via l url grace à l'API URLSearchParams
    const params = new URLSearchParams(window.location.search);
    const id_pret = params.get("id_pret");
    //const id = 1;
    if (id_pret) {
        ajax("GET", `/prets/${id_pret}`, null, function (data) {
            // chargement des données  via ID 
            console.table(data);
            document.getElementById("affichage_response").innerHTML= `
              <a href="pretPdf.php?id_pret=${data.id_pret}"><button>Format pdf</button> </a>
              <h2>Preteur</h2>
              <p>Nom : ${data.nom_client}</p>
              <p>Email : ${data.email} </p>
              <p>Telephone : ${data.telephone} </p>
              <hr>
              <h2>Object du pret</h2>
              <p>Le prêteur accorde à l’emprunteur un prêt d’un montant de ${data.montant}  Ariary destiné à financer des besoins personnels.</p>
              <h2>Durée du prêt</h2>
              <p>La durée du prêt est de ${data.duree_mois}  mois, à compter du ${data.date_debut}.</p>
              <h2>Taux d’intérêt</h2>
              <p>Le taux d’intérêt annuel fixe est de  ${data.taux_interet}%.</p>
              <h2> Modalités de remboursement</h2>
              <p>L’emprunteur s’engage à rembourser le prêt par mensualités constantes de ${data.mensualite} Ariary, comprenant le capital et les intérêts.</p>
              <br>
              <h2>Assurance</h2>

              <h2>Signatures</h2>

              <p>Fait à Antananarivo, le ${data.date_debut} </p>
              <p>Signature du Prêteur : ____________________</p>
              <p>Signature de l’Emprunteur : ____________________</p>
              
              `;
        });
    } else {
        console.error("Aucun ID fourni dans l'URL !");
    }
});


</script>
