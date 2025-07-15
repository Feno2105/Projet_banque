
<div class="bank-loan-types-container">
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-list-alt icon-lg me-2"></i>
            Gestion des types de prêt
        </h1>
    </div>

    <div class="card loan-type-form-card mb-4">
    <div class="card-header">
        <h2 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Ajouter ou modifier un type</h2>
    </div>
    <div class="card-body">
        <form id="loan-type-form">
            <input type="hidden" id="id_type_pret">

            <div class="row mb-3">
                <div class="form-group col-md-6">
                    <label for="nom_type_pret">Nom du type :</label>
                    <input type="text" class="form-control" id="nom_type_pret" required>
                </div>

                <div class="form-group col-md-6">
                    <label for="taux_interet">Taux d'intérêt :</label>
                    <div class="input-group">
                        <input type="number" class="form-control" id="taux_interet" step="0.01" min="0" required>
                        <span class="input-group-text">%</span>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="form-group col-md-6">
                    <label for="duree_mois">Durée (mois) :</label>
                    <input type="number" class="form-control" id="duree_mois" min="0" required>
                </div>

                <div class="form-group col-md-6">
                    <label for="montant_min">Montant min :</label>
                    <div class="input-group">
                        <input type="number" class="form-control" id="montant_min" step="0.01" min="0">
                        <span class="input-group-text">€</span>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="form-group col-md-6">
                    <label for="montant_max">Montant max :</label>
                    <div class="input-group">
                        <input type="number" class="form-control" id="montant_max" step="0.01" min="0">
                        <span class="input-group-text">€</span>
                    </div>
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
            <h2 class="mb-0"><i class="fas fa-list me-2"></i>Liste des types de prêt</h2>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="table-type-pret">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Taux</th>
                            <th>Durée</th>
                            <th>Montant min</th>
                            <th>Montant max</th>
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
                <td><strong>${tp.nom_type_pret}</strong></td>
                <td class="interest-rate">${tp.taux_interet}%</td>
                <td>${tp.duree_mois} mois</td>
                <td>${tp.montant_min ? tp.montant_min + ' €' : '-'}</td>
                <td>${tp.montant_max ? tp.montant_max + ' €' : '-'}</td>
                <td class="loan-type-actions">
                    <button class="btn-edit-loan-type" onclick='remplirFormulaire(${JSON.stringify(tp)})'>
                        <i class="fas fa-edit"></i> Modifier
                    </button>
                    <button class="btn-delete-loan-type" onclick='supprimerTypePret(${tp.id_type_pret})'>
                        <i class="fas fa-trash-alt"></i> Supprimer
                    </button>
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
