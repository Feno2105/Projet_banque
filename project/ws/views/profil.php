<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Profil du client</title>
</head>
<body>
    <div>
        <div>[Photo du client]</div>
        <div>
            <h1><p id="nom"></p></h1>
            <div>Contact : <p id="contact"></p></div>
            <div>Email : <p id="email"></p></div>
        </div>
    </div>
    <br>
    <div>
        <div>
            <h2>Adresse</h2>
            <div><p id="adresse"></div>
        </div>

        <div>
            <h2>Inscrit depuis :</h2>
            <div>le <p id="date"></p></div>
        </div>
    </div>
</body>
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
          xhr.send(data ? JSON.stringify(data) : null);
        }
    // fonction pour charger les données du client
    document.addEventListener("DOMContentLoaded", function () { // Cette fonction sera appelée dès que la page est chargée grace au DOMContentLoaded
    // recupêration de l Id du client via l url grace à l'API URLSearchParams
    const params = new URLSearchParams(window.location.search);
    const id = params.get("id");
    //const id = 1;
    if (id) {
        ajax("GET", `/client/${id}`, null, function (data) {
            // chargement des données  via ID 
            document.getElementById("nom").innerText = data.nom_client;
            document.getElementById("contact").innerText = "Contact : " + data.telephone;
            document.getElementById("email").innerText = "Email : " + data.email;
            document.getElementById("adresse").innerText = data.adresse;
            document.getElementById("date").innerText = data.date_inscription;
        });
    } else {
        console.error("Aucun ID fourni dans l'URL !");
    }
});


</script>
</html>
