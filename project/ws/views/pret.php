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

  <h1>Gestion des prêts</h1>
  <h2>Faire un pret</h2>
  <label>Client :</label>
  <select name="client_id" id="client-choix" required>
    <!-- Remplir dynamiquement avec les clients existants -->
    <option value="">Sélectionner un client</option>
    <!-- <option value="1">Jean Dupont</option> -->
  </select><br>

  <label>Type de prêt :</label>
  <select name="type_pret_id" id="type-pret-choix" required>
    <!-- Remplir dynamiquement avec les types de prêts existants -->
    <option value="">Sélectionner un type</option>
    <!-- <option value="1">Crédit Immobilier</option> -->
  </select><br>

  <label>Montant :</label>
  <input type="number" step="0.01" name="montant" required><br>

  <label>Date de début :</label>
  <input type="date" name="date_debut" value="<?= date('Y-m-d') ?>"><br>
  <br>

  <button onclick="ajouterPret()">Ajouter le prêt</button>
  <h2>Liste des prêts </h2>
  <div class="search-container">
    <input type="text" id="search-input" placeholder="Rechercher par email, type, statut..." oninput="filterLoans()">
  </div>

  <table id="table-etudiants">
    <thead>
      <tr>
        <th>Email</th>
        <th>Type</th>
        <th>Montant</th>
        <th>Reste a payer</th>
        <th>Mensualité(resultat de calcul)</th>
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
      xhr.send(data ? data : null);
    }

    function chargerPrets() {
      ajax("GET", "/prets", null, (data) => {
        allLoans = data; // Stocke tous les prêts
        displayLoans(allLoans);
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
          <td>
            <a href="profil_?id=${e.client_id}" target="_blank">${e.email}</a>
          </td>
          <td>${e.nom_type_pret}</td>
          <td>${e.montant}</td>
          <td>${e.reste_a_payer}</td>
          <td>${e.mensualite}</td>
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
            <a href="rembourser_?id=${e.id_pret}"><button>Rembourser</button></a>
          </td>
        `;
        tbody.appendChild(tr);
      });
    }

    function chargerClients() {
      ajax("GET", "/client", null, (data) => {
        const select = document.getElementById("client-choix");
        select.innerHTML = "";
        data.forEach(client => {
          const option = document.createElement("option");
          option.value = client.id_client;
          option.textContent = client.nom_client +" id = " +client.id_client;
          select.appendChild(option);
        });
      }, (error) => {
        console.error("Erreur lors du chargement des clients:", error);
        alert("Erreur lors du chargement des clients");
      });
    }
    function rembourser(id){
      if (confirm('Rembourser ?')) {
        ajax("GET" ,`/prets/rembourser/${id}`,null,()=>{
          chargerPrets();
        });
      }
    }
    function chargerTypePrets() {
      ajax("GET", "/type_prets", null, (data) => {
        const select = document.getElementById("type-pret-choix");
        select.innerHTML = "";
        data.forEach(t => {
          const option = document.createElement("option");
          option.value = t.id_type_pret;
          option.textContent = t.nom_type_pret + "(" + t.taux_interet + "% " + "" + t.duree + " mois)";
          select.appendChild(option);
        });
        }, (error) => {
        console.error("Erreur lors du chargement des clients:", error);
        alert("Erreur lors du chargement des typres de prets");
      });
    }

    function ajouterPret() {
      const client_id = document.querySelector('select[name="client_id"]').value;
      const type_pret_id = document.querySelector('select[name="type_pret_id"]').value;
      const montant = document.querySelector('input[name="montant"]').value;
      const date_debut = document.querySelector('input[name="date_debut"]').value;

      // Encodage des données au format x-www-form-urlencoded
      const data = `client_id=${encodeURIComponent(client_id)}&type_pret_id=${encodeURIComponent(type_pret_id)}&montant=${encodeURIComponent(montant)}&date_debut=${encodeURIComponent(date_debut)}`;

      ajax("POST", "/prets", data, () => {
        alert("Prêt ajouté !");
        resetFormPret();
        chargerPrets(); // Recharge la liste des prêts si tu as une fonction pour ça
      });
    }

    function resetFormPret() {
      document.querySelector('select[name="client_id"]').value = "";
      document.querySelector('select[name="type_pret_id"]').value = "";
      document.querySelector('input[name="montant"]').value = "";
      document.querySelector('input[name="date_debut"]').value = "";
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
    chargerPrets();
    chargerClients();
    chargerTypePrets();
  </script>

</body>