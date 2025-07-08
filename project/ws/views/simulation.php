<style>
  body {
    font-family: sans-serif;
    padding: 20px;
  }
  input, button {
    margin: 5px;
    padding: 5px;
  }
  table {
    border-collapse: collapse;
    width: 100%;
    margin-top: 20px;
  }
  th, td {
    border: 1px solid #ccc;
    padding: 8px;
    text-align: left;
  }
  th {
    background-color: #f2f2f2;
  }
</style>

<body>

<form id="form-pret">
  <h2>Faire une simulation prêt</h2>
  <label>Client :</label>
  <select name="client_id" id="client-choix" required>
    <option value="">Sélectionner un client</option>
  </select><br>
  <label>Type de prêt :</label>
  <select name="type_pret_id" id="type-pret-choix" required>
    <option value="">Sélectionner un type</option>
  </select><br>

  <label>Montant :</label>
  <input type="number" name="montant" id="montant" required><br>

  <label>Date de début :</label>
  <input type="date" name="date_debut" id="date_debut" value="<?= date('Y-m-d') ?>"><br><br>

  <button id="submit-btn">Ajouter le prêt</button>
  <button type="button" id="cancel-btn" style="display:none;">Annuler</button>
</form>

<table id="table-prets">
  <thead>
    <tr>
      <th>ID</th>
      <th>ID Types Prêt</th>
      <th>Clients</th>
      <th>Montant</th>
      <th>Reste à payer</th>
      <th>Mensualité</th>
      <th>Date début</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody id="table-body-prets"></tbody>
</table>

<script>
  const apiBase = "/Projet_banque/project/ws";
  let typesPret = [];
  let prets = [];
  let pretIdCounter = 1;
  let clients = [];

  let editingPretId = null; 
  class TypePret {
    constructor(id, valeur_assurance, interet, duree_mois) {
      this.id = id;
      this.valeur_assurance = parseFloat(valeur_assurance);
      this.interet = parseFloat(interet);
      this.duree_mois = parseInt(duree_mois);
    }
  }

  class Pret {
    constructor(id, id_type_pret, id_client, montant, reste_paye, date_debut, mensualite) {
      this.id = id;
      this.id_type_pret = id_type_pret;
      this.id_client = id_client;      
      this.montant = montant;
      this.reste_paye = reste_paye;
      this.date_debut = date_debut;
      this.mensualite = mensualite;
    }
  }

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

  function chargerClients() {
    ajax("GET", "/client", null, (data) => {
      clients = data; 
      const select = document.getElementById("client-choix");
      select.innerHTML = '<option value="">Sélectionner un client</option>';
      data.forEach(client => {
        const option = document.createElement("option");
        option.value = client.id_client;
        option.textContent = client.nom_client + " (id=" + client.id_client + ")";
        select.appendChild(option);
      });
    }, (error) => {
      console.error("Erreur lors du chargement des clients:", error);
      alert("Erreur lors du chargement des clients");
    });
  }

  function chargerTypesPret() {
    ajax("GET", "/type_prets", null, (data) => {
      typesPret = data.map(t => new TypePret(
        t.id_type_pret,
        t.valeur_assurance,
        t.taux_interet,
        t.duree_mois
      ));

      const select = document.getElementById("type-pret-choix");
      select.innerHTML = '<option value="">Sélectionner un type</option>';

      typesPret.forEach(type => {
        const option = document.createElement("option");
        option.value = type.id;
        option.textContent = `Type ${type.id} - ${type.interet}% sur ${type.duree_mois} mois`;
        select.appendChild(option);
      });
    }, (error) => {
      console.error("Erreur lors du chargement des types de prêt :", error);
      alert("Erreur lors du chargement des types de prêt");
    });
  }

  function calculerMensualiteEtReste(montant, typePret) {
    let mensualiteBase = montant / typePret.duree_mois;
    let mensualite = mensualiteBase
      + (mensualiteBase * (typePret.interet / 1200))
      + (mensualiteBase * (typePret.valeur_assurance / 1200));
    mensualite = parseFloat(mensualite.toFixed(2));

    let reste_paye = mensualite * typePret.duree_mois;
    reste_paye = parseFloat(reste_paye.toFixed(2));

    return { mensualite, reste_paye };
  }

  function afficherPrets() {
    const tbody = document.getElementById("table-body-prets");
    tbody.innerHTML = "";
    prets.forEach(pret => {
      const client = clients.find(c => c.id_client === pret.id_client);
      const nomClient = client ? client.nom_client : "Inconnu";

      const tr = document.createElement("tr");
      tr.innerHTML = `
        <td>${pret.id}</td>
        <td>${pret.id_type_pret}</td>
        <td>${nomClient}</td>
        <td>${pret.montant.toFixed(2)}</td>
        <td>${pret.reste_paye.toFixed(2)}</td>
        <td>${pret.mensualite.toFixed(2)}</td>
        <td>${pret.date_debut}</td>
        <td>
          <button data-id="${pret.id}" class="btn-modifier">Modifier</button>
          <button data-id="${pret.id}" class="btn-ajouter">Ajouter le prêt</button>
        </td>
      `;
      tbody.appendChild(tr);
    });

    document.querySelectorAll(".btn-modifier").forEach(button => {
      button.onclick = () => {
        const id = parseInt(button.getAttribute("data-id"));
        commencerModification(id);
      };
    });

    document.querySelectorAll(".btn-ajouter").forEach(button => {
      button.onclick = () => {
        const id = parseInt(button.getAttribute("data-id"));
        envoyerPretParAjax(id);
      };
    });
  }

  function commencerModification(id) {
    const pret = prets.find(p => p.id === id);
    if (!pret) return;

    editingPretId = id;

    document.getElementById("client-choix").value = pret.id_client;  
    document.getElementById("type-pret-choix").value = pret.id_type_pret;
    document.getElementById("montant").value = pret.montant;
    document.getElementById("date_debut").value = pret.date_debut;

    document.getElementById("submit-btn").textContent = "Mettre à jour";
    document.getElementById("cancel-btn").style.display = "inline-block";
  }

  function annulerModification() {
    editingPretId = null;
    document.getElementById("form-pret").reset();
    document.getElementById("submit-btn").textContent = "Ajouter le prêt";
    document.getElementById("cancel-btn").style.display = "none";
  }

  document.getElementById("cancel-btn").addEventListener("click", annulerModification);

  document.getElementById("form-pret").addEventListener("submit", function(event) {
    event.preventDefault();

    const client_id = parseInt(document.getElementById("client-choix").value); 
    const montant = parseFloat(document.getElementById("montant").value);
    const date_debut = document.getElementById("date_debut").value;
    const typeId = parseInt(document.getElementById("type-pret-choix").value);

    if (!client_id) {
      alert("Veuillez sélectionner un client.");
      return;
    }

    const typePret = typesPret.find(t => t.id === typeId);
    if (!typePret) {
      alert("Veuillez sélectionner un type de prêt valide");
      return;
    }

    if (!montant || montant <= 0) {
      alert("Veuillez entrer un montant valide.");
      return;
    }

    if (!date_debut) {
      alert("Veuillez entrer une date de début.");
      return;
    }

    if (!typePret.duree_mois || typePret.duree_mois <= 0) {
      alert("Durée de prêt invalide.");
      return;
    }

    const { mensualite, reste_paye } = calculerMensualiteEtReste(montant, typePret);

    if (editingPretId === null) {
      const nouveauPret = new Pret(pretIdCounter, typeId, client_id, montant, reste_paye, date_debut, mensualite);
      prets.push(nouveauPret);
      pretIdCounter++;
    } else {
      const pret = prets.find(p => p.id === editingPretId);
      pret.id_type_pret = typeId;
      pret.id_client = client_id; 
      pret.montant = montant;
      pret.reste_paye = reste_paye;
      pret.date_debut = date_debut;
      pret.mensualite = mensualite;
      editingPretId = null;

      document.getElementById("submit-btn").textContent = "Ajouter le prêt";
      document.getElementById("cancel-btn").style.display = "none";
    }

    afficherPrets();
    this.reset();
  });

  function envoyerPretParAjax(id) {
    const pret = prets.find(p => p.id === id);
    if (!pret) {
      alert("Prêt introuvable");
      return;
    }

    const data = 
      `client_id=${encodeURIComponent(pret.id_client)}` +
      `&type_pret_id=${encodeURIComponent(pret.id_type_pret)}` +
      `&montant=${encodeURIComponent(pret.montant)}` +
      `&date_debut=${encodeURIComponent(pret.date_debut)}`;

    ajax("POST", "/prets", data, (response) => {
      alert(`Prêt ${id} envoyé avec succès !`);
    }, (error) => {
      alert("Erreur lors de l'envoi du prêt : " + error.message);
    });
  }

  function resetFormPret() {
    document.querySelector('select[name="client_id"]').value = "";
    document.querySelector('select[name="type_pret_id"]').value = "";
    document.querySelector('input[name="montant"]').value = "";
    document.querySelector('input[name="date_debut"]').value = "";
  }

  chargerClients();
  chargerTypesPret();
  afficherPrets();

</script>

</body>
