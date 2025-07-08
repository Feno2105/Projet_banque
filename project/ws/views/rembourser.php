<div class="remboursement-container">
  <div class="page-header">
    <h1 class="page-title">
      <i class="fas fa-money-bill-wave icon-lg me-2"></i>
      Planification de remboursement
    </h1>
  </div>

  <div class="card remboursement-card">
    <div class="card-header">
      <h2 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Période de remboursement</h2>
    </div>
    <div class="card-body">
      <form id="remboursement-form" onsubmit="ajouterOuModifier(event)" method="post">
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="mois_debut">Mois de début</label>
            <select class="form-control" name="mois_debut" id="mois_debut" required>
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

          <div class="form-group col-md-6">
            <label for="annee_debut">Année de début</label>
            <input type="number" class="form-control" name="annee_debut" id="annee_debut"
              placeholder="ex : 2023" min="1900" max="2100" required>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="mois_fin">Mois de fin</label>
            <select class="form-control" name="mois_fin" id="mois_fin" required>
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

          <div class="form-group col-md-6">
            <label for="annee_fin">Année de fin</label>
            <input type="number" class="form-control" name="annee_fin" id="annee_fin"
              placeholder="ex : 2025" min="1900" max="2100" required>
          </div>
        </div>

        <div class="form-actions text-center mt-4">
          <button type="submit" class="btn btn-primary btn-lg">
            <i class="fas fa-check-circle me-2"></i>Valider le plan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  const apiBase = "/Projet_banque/project/ws";

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

  function ajouterOuModifier(event) {
    event.preventDefault();
    const params = new URLSearchParams(window.location.search);
    const id = params.get("id");

    const btnSubmit = event.target.querySelector('button[type="submit"]');
    btnSubmit.disabled = true;
    btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Traitement...';

    const data = new FormData(event.target);
    data.append('id_pret', id);

    ajax("POST", `/remboursement/`, new URLSearchParams(data).toString(), 
        (response) => {
            btnSubmit.innerHTML = '<i class="fas fa-check-circle me-2"></i> Plan validé !';
            setTimeout(() => {
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = '<i class="fas fa-check-circle me-2"></i> Valider le plan';
                // Redirection ou message de succès
                alert('Plan de remboursement enregistré avec succès !');
            }, 1500);
        },
        (error) => {
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = '<i class="fas fa-check-circle me-2"></i> Valider le plan';
            alert('Erreur: ' + (error.message || "Échec de l'enregistrement"));
        }
    );
}
</script>