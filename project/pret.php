<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <title>Liste des prêts</title>
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

    .search-container {
      margin: 20px 0;
    }

    #search-input {
      width: 300px;
      padding: 8px;
    }
  </style>
</head>

<body>

  <head>
    <?php include 'fragment/navigation.php'; ?>
  </head>

  <h1>Gestion des prêts</h1>

  <div class="search-container">
    <input type="text" id="search-input" placeholder="Rechercher par email, type, statut..." oninput="filterLoans()">
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

  <script>
    const apiBase = "http://localhost/Projet_banque/project/ws";
    let allLoans = []; // Variable pour stocker tous les prêts

    function ajax(method, url, data, callback, errorCallback) {
      const xhr = new XMLHttpRequest();
      xhr.open(method, apiBase + url, true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.onreadystatechange = () => {
        if (xhr.readyState === 4) {
          if (xhr.status === 200) {
            callback(JSON.parse(xhr.responseText));
          } else if (errorCallback) {
            errorCallback(new Error(xhr.statusText));
          }
        }
      };
      xhr.send(data);
    }

    function chargerEtudiants() {
      ajax("GET", "/prets", null, (data) => {
        allLoans = data; // Stocke tous les prêts
        displayLoans(data);
      }, (error) => {
        console.error("Erreur lors du chargement:", error);
        alert("Erreur lors du chargement des données");
      });
    }

    function displayLoans(loans) {
      const tbody = document.querySelector("#table-etudiants tbody");
      tbody.innerHTML = "";
      loans.forEach(e => {
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
                <button onclick="valider(${e.id_pret})">Valider</button>
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
    }

    function filterLoans() {
      const searchTerm = document.getElementById("search-input").value.toLowerCase();
      if (!searchTerm) {
        displayLoans(allLoans); // Si vide, affiche tout
        return;
      }

      const filteredLoans = allLoans.filter(loan => {
        return (
          (loan.email && loan.email.toLowerCase().includes(searchTerm)) ||
          (loan.nom_type_pret && loan.nom_type_pret.toLowerCase().includes(searchTerm)) ||
          (loan.libelle && loan.libelle.toLowerCase().includes(searchTerm)) ||
          (loan.montant && loan.montant.toString().includes(searchTerm)) ||
          (loan.reste_a_payer && loan.reste_a_payer.toString().includes(searchTerm))
        );
      });

      displayLoans(filteredLoans);
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

    // Chargement initial
    chargerEtudiants();
  </script>

</body>
</html>