<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Situation des fonds par client</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="p-4">

  <div class="container">
    <h1 class="mb-4">Situation mensuelle des fonds par client</h1>

    <form id="form-filtre" class="row g-3 mb-4">
      <div class="col-md-3">
        <label for="mois" class="form-label">Mois :</label>
        <select id="mois" class="form-select" required>
            <option value="">-- Choisissez --</option>
            <option value="1">1 - Janvier</option>
            <option value="2">2 - Février</option>
            <option value="3">3 - Mars</option>
            <option value="4">4 - Avril</option>
            <option value="5">5 - Mai</option>
            <option value="6">6 - Juin</option>
            <option value="7">7 - Juillet</option>
            <option value="8">8 - Août</option>
            <option value="9">9 - Septembre</option>
            <option value="10">10 - Octobre</option>
            <option value="11">11 - Novembre</option>
            <option value="12">12 - Décembre</option>
        </select>
      </div>
      <div class="col-md-3">
        <label for="annee" class="form-label">Année :</label>
        <input type="number" class="form-control" id="annee" value="${new Date().getFullYear()}" required>
      </div>
      <div class="col-md-3 align-self-end">
        <button type="submit" class="btn btn-primary">Rechercher</button>
      </div>
    </form>

    <div id="resultats">
      <table class="table table-bordered table-striped">
        <thead class="table-light">
          <tr>
            <th>Client ID</th>
            <th>Montant établissement fonds (MEF)</th>
            <th>Montant prêt (MP)</th>
            <th>Montant remboursé (P)</th>
            <th>Solde calculé</th>
          </tr>
        </thead>
        <tbody id="tbody-resultats"></tbody>
      </table>
    </div>
  </div>

  <script>
    const apiBase = "http://localhost/Projet_banque/project/ws";

    function ajax(method, url, data, callback, errorCallback) {
      const xhr = new XMLHttpRequest();
      xhr.open(method, apiBase + url, true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.onreadystatechange = () => {
        if (xhr.readyState === 4) {
          if (xhr.status === 200) {
            callback(JSON.parse(xhr.responseText));
          } else if (errorCallback) {
            errorCallback(xhr.responseText);
          }
        }
      };
      xhr.send(data);
    }

    document.getElementById("form-filtre").addEventListener("submit", function(e) {
      e.preventDefault();
      const mois = document.getElementById("mois").value;
      const annee = document.getElementById("annee").value;

      if (!mois || !annee) {
        alert("Veuillez remplir tous les champs.");
        return;
      }

      const data = `mois=${mois}&annee=${annee}`;
      ajax("POST", "/fondtab", data, afficherResultats, (err) => {
        alert("Erreur : " + err);
      });
    });

    function afficherResultats(resultats) {
      const tbody = document.getElementById("tbody-resultats");
      tbody.innerHTML = "";

      resultats.forEach(r => {
        const tr = document.createElement("tr");
        tr.innerHTML = `
          <td>${r.id_client}</td>
          <td>${parseFloat(r.mef || 0).toFixed(2)} </td>
          <td>${parseFloat(r.mp || 0).toFixed(2)} </td>
          <td>${parseFloat(r.p || 0).toFixed(2)} </td>
          <td><strong>${parseFloat(r.solde || 0).toFixed(2)} </strong></td>
        `;
        tbody.appendChild(tr);
      });
    }
  </script>
</body>
</html>
