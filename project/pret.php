<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <title>Gestion des étudiants</title>
  <style>
    body {
      font-family: sans-serif;
      padding: 20px;
    }

    input,
    button {
      margin: 5px;
      padding: 5px;
    }

    table {
      border-collapse: collapse;
      width: 100%;
      margin-top: 20px;
    }

    th,
    td {
      border: 1px solid #ccc;
      padding: 8px;
      text-align: left;
    }

    th {
      background-color: #f2f2f2;
    }
  </style>
</head>

<body>

  <head>
    <?php include 'fragment/navigation.php'; ?>
  </head>

  <h1>Gestion des étudiants</h1>

  <div>
    <input type="hidden" id="id">
    <input type="text" id="nom" placeholder="Nom">
    <input type="text" id="prenom" placeholder="Prénom">
    <input type="email" id="email" placeholder="Email">
    <input type="number" id="age" placeholder="Âge">
    <button onclick="ajouterOuModifier()">Ajouter / Modifier</button>
  </div>

  <table id="table-etudiants">
    <thead>
      <tr>
        <th>Email</th>
        <th>Type</th>
        <th>Montant</th>
        <th>Rest</th>
        <th>Date debut</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>

    <a href="/prets/accept/"><button>Valider</button></a>

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
      xhr.send(data);
    }

    function chargerEtudiants() {
      ajax("GET", "/prets", null, (data) => {
        const tbody = document.querySelector("#table-etudiants tbody");
        tbody.innerHTML = "";
        data.forEach(e => {
          const tr = document.createElement("tr");
          tr.innerHTML = `
  <td>${e.email}</td>
  <td>${e.nom_type_pret}</td>
  <td>${e.montant}</td>
  <td>${e.reste_a_payer}</td>
  <td>${e.date_debut}</td>
  <td>
    ${e.libelle === 'En attente' 
      ? ` 
        <button onclick="valider(${e.id_pret})">Valider</button></a>
        <button onclick="refuser(${e.id_pret})">Refuser</button>
      ` 
      : e.libelle
    }
  </td>
  <td>
    <button>✏️</button>
    <button>🗑️</button>
  </td>
`;
          tbody.appendChild(tr);
        });
      });
    }

    // Empêcher le double-clic
function valider(id) {
  const btn = event.target;
  btn.disabled = true;
  
  ajax("GET", `/prets/accept/${id}`, null,
    (response) => {
      alert(response.message);
      chargerEtudiants();
    },
    (error) => {
      btn.disabled = false;
      alert("Erreur: " + (error.message || "Opération échouée"));
    }
  );
}

function refuser(id) {
  const btn = event.target;
  btn.disabled = true;
  
  ajax("GET", `/prets/refuse/${id}`, null,
    (response) => {
      alert(response.message);
      chargerEtudiants();
    },
    (error) => {
      btn.disabled = false;
      alert("Erreur: " + (error.message || "Opération échouée"));
    }
  );
}

    chargerEtudiants();
  </script>

</body>

</html>