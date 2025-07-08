<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Gestion des fonds</title>
  <style>
    body { font-family: sans-serif; padding: 20px; }
    input, select, button { margin: 5px; padding: 5px; }
    table { border-collapse: collapse; width: 100%; margin-top: 20px; }
    th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
    th { background-color: #f2f2f2; }
  </style>
</head>
<body>

  <h1>Gestion des fonds de l'établissement</h1>
  <a href=""></a>
  <div>
    <input type="hidden" id="id_fonds">
    <input type="date" id="date_ajout" placeholder="Date d'ajout">
    <input type="number" id="montant" placeholder="Montant" step="0.01">
    <select id="source">
      <option value="">-- Sélectionnez une source --</option>
    </select>
    <input type="text" id="description" placeholder="Description">
    <button onclick="ajouterOuModifier()">Ajouter / Modifier</button>
  </div>

  <table id="table-fonds">
    <thead>
      <tr>
        <th>ID</th><th>Date</th><th>Montant</th><th>Source</th><th>Description</th><th>Actions</th>
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
      xhr.send(data ? data: null);
    }

    function chargerFonds() {
      ajax("GET", "/fonds", null, (data) => {
        const tbody = document.querySelector("#table-fonds tbody");
        tbody.innerHTML = "";
        data.forEach(f => {
          const tr = document.createElement("tr");
          tr.innerHTML = `
            <td>${f.id_fonds_etablissement}</td>
            <td>${f.date_ajout}</td>
            <td>${f.montant}</td>
            <td>${f.source}</td>
            <td>${f.description}</td>
            <td>
              <button onclick='remplirFormulaire(${JSON.stringify(f)})'>✏️</button>
              <button onclick='supprimerFonds(${f.id_fonds_etablissement})'>🗑️</button>
            </td>
          `;
          tbody.appendChild(tr);
        });
      });
    }

    function chargerSources() {
      ajax("GET", "/sources", null, (data) => {
        const select = document.getElementById("source");
        select.innerHTML = "";
        data.forEach(s => {
          const option = document.createElement("option");
          option.value = s.id_source_fond;
          option.textContent = s.nom_source;
          select.appendChild(option);
        });
      });
    }

    function ajouterOuModifier() {
      const id = document.getElementById("id_fonds").value;
      const date_ajout = document.getElementById("date_ajout").value;
      const montant = document.getElementById("montant").value;
      const source = document.getElementById("source").value;
      const description = document.getElementById("description").value;

      const data = `date_ajout=${encodeURIComponent(date_ajout)}&montant=${montant}&source=${source}&description=${encodeURIComponent(description)}`;

      if (id) {
        ajax("PUT", `/fonds/${id}`, data, () => {
          resetForm();
          chargerFonds();
        });
      } else {
        ajax("POST", "/fonds", data, () => {
          resetForm();
          chargerFonds();
        });
      }
    }

    function remplirFormulaire(f) {
      document.getElementById("id_fonds").value = f.id_fonds_etablissement;
      document.getElementById("date_ajout").value = f.date_ajout;
      document.getElementById("montant").value = f.montant;
      document.getElementById("source").value = f.source;
      document.getElementById("description").value = f.description;
    }

    function supprimerFonds(id) {
      if (confirm("Supprimer ce fonds ?")) {
        ajax("DELETE", `/fonds/${id}`, null, () => {
          chargerFonds();
        });
      }
    }

    function resetForm() {
      document.getElementById("id_fonds").value = "";
      document.getElementById("date_ajout").value = "";
      document.getElementById("montant").value = "";
      document.getElementById("source").value = "";
      document.getElementById("description").value = "";
    }
    function chargerSources() {
      ajax("GET", "/sources", null, (data) => {
          const select = document.getElementById("source");
          select.innerHTML = "<option value=''>-- Sélectionnez une source --</option>"; 
      
          data.forEach(s => {
              const option = document.createElement("option");
              option.value = s.id_source_fond;
              option.textContent = s.nom_source;
              select.appendChild(option);
          });
      });
    }

    chargerSources();
    chargerFonds();
  </script>

</body>
</html>
