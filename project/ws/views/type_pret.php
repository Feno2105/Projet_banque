<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Gestion des types de pret</title>
  <style>
    body { font-family: sans-serif; padding: 20px; }
    input, button { margin: 5px; padding: 5px; width: 200px; }
    table { border-collapse: collapse; width: 100%; margin-top: 20px; }
    th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
    th { background-color: #f2f2f2; }
  </style>
</head>
<body>

  <h1>Gestion des types de pret</h1>

  <div>
    <input type="hidden" id="id_type_pret">
    <input type="text" id="nom_type_pret" placeholder="Nom du type de pret" />
    <input type="number" id="taux_interet" placeholder="Taux d'interet (%)" step="0.01" min="0" />
    <input type="number" id="duree_mois" placeholder="Duree maximum en mois" min="0" />
    <input type="number" id="montant_min" placeholder="Montant minimum" step="0.01" min="0" />
    <input type="number" id="montant_max" placeholder="Montant maximum" step="0.01" min="0" />
    <br/>
    <button onclick="ajouterOuModifier()">Ajouter / Modifier</button>
  </div>

  <table id="table-type-pret">
    <thead>
      <tr>
        <th>ID</th><th>Nom</th><th>Taux (%)</th><th>Duree mois</th><th>Montant min</th><th>Montant max</th><th>Actions</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>

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

  function chargerTypesPret() {
    ajax("GET", "/type_prets", null, (data) => {
      const tbody = document.querySelector("#table-type-pret tbody");
      tbody.innerHTML = "";
      data.forEach(tp => {
        const tr = document.createElement("tr");
        tr.innerHTML = `
          <td>${tp.id_type_pret}</td>
          <td>${tp.nom_type_pret}</td>
          <td>${tp.taux_interet}</td>
          <td>${tp.duree_mois}</td>
          <td>${tp.montant_min ?? ''}</td>
          <td>${tp.montant_max ?? ''}</td>
          <td>
            <button onclick='remplirFormulaire(${JSON.stringify(tp)})'>✏️</button>
            <button onclick='supprimerTypePret(${tp.id_type_pret})'>🗑️</button>
          </td>
        `;
        tbody.appendChild(tr);
      });
    });
  }

  function ajouterOuModifier() {
    const id = document.getElementById("id_type_pret").value;
    const nom = document.getElementById("nom_type_pret").value;
    const taux = document.getElementById("taux_interet").value;
    const duree = document.getElementById("duree_mois").value;
    const min = document.getElementById("montant_min").value;
    const max = document.getElementById("montant_max").value;

    const data = `nom_type_pret=${encodeURIComponent(nom)}&taux_interet=${taux}&duree_mois=${duree}&montant_min=${min}&montant_max=${max}`;

    if (id) {
      ajax("PUT", `/type_prets/${id}`, data, () => {
        resetForm();
        chargerTypesPret();
      });
    } else {
      ajax("POST", "/type_prets", data, () => {
        resetForm();
        chargerTypesPret();
      });
    }
  }

  function remplirFormulaire(tp) {
    document.getElementById("id_type_pret").value = tp.id_type_pret;
    document.getElementById("nom_type_pret").value = tp.nom_type_pret;
    document.getElementById("taux_interet").value = tp.taux_interet;
    document.getElementById("duree_mois").value = tp.duree_mois;
    document.getElementById("montant_min").value = tp.montant_min ?? "";
    document.getElementById("montant_max").value = tp.montant_max ?? "";
  }

  function supprimerTypePret(id) {
    if (confirm("Supprimer ce type de pret ?")) {
      ajax("DELETE", `/type_prets/${id}`, null, () => {
        chargerTypesPret();
      });
    }
  }

  function resetForm() {
    document.getElementById("id_type_pret").value = "";
    document.getElementById("nom_type_pret").value = "";
    document.getElementById("taux_interet").value = "";
    document.getElementById("duree_mois").value = "";
    document.getElementById("montant_min").value = "";
    document.getElementById("montant_max").value = "";
  }

  chargerTypesPret();
</script>

</body>
</html>
