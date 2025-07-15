<div class="bank-funds-container">
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-coins icon-lg me-2"></i>
            Gestion des fonds de l'établissement
        </h1>
    </div>

    <div class="card fund-form-card mb-4">
    <div class="card-header">
        <h2 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Ajouter ou modifier des fonds</h2>
    </div>
    <div class="card-body">
        <form id="fund-form">
            <input type="hidden" id="id_fonds">

            <div class="row mb-3">
                <div class="form-group col-md-6">
                    <label for="date_ajout">Date :</label>
                    <input type="date" class="form-control" id="date_ajout" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="montant">Montant :</label>
                    <div class="input-group">
                        <input type="number" class="form-control" id="montant" step="0.01" required>
                        <span class="input-group-text">€</span>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="form-group col-md-6">
                    <label for="source">Source :</label>
                    <select class="form-control" id="source" required>
                        <option value="">-- Sélectionnez une source --</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="description">Description :</label>
                    <input type="text" class="form-control" id="description" placeholder="Description">
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 d-flex justify-content-start">
                    <button type="button" class="btn btn-primary" onclick="ajouterOuModifier()">
                        <i class="fas fa-save"></i> Valider
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>


    <div class="card">
        <div class="card-header">
            <h2 class="mb-0"><i class="fas fa-list me-2"></i>Historique des fonds</h2>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="table-fonds">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Date</th>
                            <th>Montant</th>
                            <th>Source</th>
                            <th>Description</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

  <script>
    const apiBase = "/Projet_banque/project/ws";

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
                <td class="${f.montant >= 0 ? 'positive-amount' : 'negative-amount'}">
                    ${f.montant} €
                </td>
                <td>${f.source}</td>
                <td>${f.description}</td>
                <td class="fund-actions">
                    <button class="btn-edit" onclick='remplirFormulaire(${JSON.stringify(f)})'>
                        <i class="fas fa-edit"></i> Modifier
                    </button>
                    <button class="btn-delete" onclick='supprimerFonds(${f.id_fonds_etablissement})'>
                        <i class="fas fa-trash-alt"></i> Supprimer
                    </button>
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