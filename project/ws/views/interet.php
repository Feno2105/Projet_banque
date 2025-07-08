<h2>Interer</h2>

<div>
  <p>Date de debut</p>
  <label for="mois_debut">Mois de debut</label>
  <input type="number" id="mois_debut" min="1" max="12">
  <label for="annee_debut">Annee de debut</label>
  <input type="number" id="annee_debut" min="2000">
</div>

<div>
  <p>Date de fin</p>
  <label for="mois_fin">Mois de fin</label>
  <input type="number" id="mois_fin" min="1" max="12">
  <label for="annee_fin">Annee de fin</label>
  <input type="number" id="annee_fin" min="2000">
</div>

<button id="validerBtn">Valider</button>

<div id="recherche_result"></div>

<table id="table-prets">
  <thead>
    <tr>
      <th>Mois - Annee</th>
      <th>Total d'interet</th>
    </tr>
  </thead>
  <tbody></tbody>
</table>

<div id="error_message" style="color: red;"></div>

<script>
  document.getElementById('validerBtn').addEventListener('click', function() {
    // Récupère tous les inputs
    const inputs = [
      document.getElementById('mois_debut'),
      document.getElementById('annee_debut'), 
      document.getElementById('mois_fin'),
      document.getElementById('annee_fin')
    ];
    
    // Vérifie que tous les champs sont remplis
    let isValid = true;
    inputs.forEach(input => {
      if (!input.value.trim()) {
        isValid = false;
        input.style.border = '1px solid red';
      } else {
        input.style.border = '';
      }
    });
    
    if (!isValid) {
      document.getElementById('error_message').textContent = 'Veuillez remplir tous les champs';
      return;
    }
    
    // Si tout est valide
    document.getElementById('error_message').textContent = '';
    
    const formData = {
      moisDebut: inputs[0].value,
      anneeDebut: inputs[1].value,
      moisFin: inputs[2].value,
      anneeFin: inputs[3].value
    };
    
    document.getElementById('recherche_result').innerHTML = ``;

    const apiBase = "http://localhost/Projet_banque/project/ws";

    function ajax(method, url, data, callback) {
      const xhr = new XMLHttpRequest();
      xhr.open(method, apiBase + url, true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.onreadystatechange = () => {
        if (xhr.readyState === 4 && xhr.status === 200) {
          callback(JSON.parse(xhr.responseText));
        } else if (xhr.readyState === 4) {
          document.getElementById('error_message').textContent = 'Acun interet trouver';
        }
      };
      xhr.send(data);
    }

    function chargerInterets() {
      ajax("GET", `/interets/${formData.moisDebut}/${formData.anneeDebut}/${formData.moisFin}/${formData.anneeFin}`, null, (data) => {
        const tbody = document.querySelector("#table-prets tbody");
        tbody.innerHTML = ''; // Vider le tableau
        
        // Ajouter les données au tableau
        data.forEach(item => {
          const row = document.createElement('tr');
          row.innerHTML = `
            <td>${item.mois} - ${item.annee}</td>
            <td>${item.total}</td>
          `;
          tbody.appendChild(row);
        });
      });
    }

    chargerInterets();
  });
</script>