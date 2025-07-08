<div class="bank-loan-container">
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-percentage icon-lg me-2"></i>
            Calcul des intérêts
        </h1>
    </div>

    <div class="card loan-form-card mb-5">
        <div class="card-header">
            <h2 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Période de calcul</h2>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <h3 class="section-title">Date de début</h3>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="mois_debut" class="form-label">Mois</label>
                            <input type="number" class="form-control" id="mois_debut" min="1" max="12" placeholder="MM">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="annee_debut" class="form-label">Année</label>
                            <input type="number" class="form-control" id="annee_debut" min="2000" placeholder="AAAA">
                        </div>
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <h3 class="section-title">Date de fin</h3>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="mois_fin" class="form-label">Mois</label>
                            <input type="number" class="form-control" id="mois_fin" min="1" max="12" placeholder="MM">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="annee_fin" class="form-label">Année</label>
                            <input type="number" class="form-control" id="annee_fin" min="2000" placeholder="AAAA">
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-actions text-center">
                <button type="button" id="validerBtn" class="btn btn-primary">
                    <i class="fas fa-calculator me-2"></i>Calculer les intérêts
                </button>
            </div>
        </div>
    </div>

    <div id="error_message" class="alert alert-danger" style="display: none;"></div>

    <!-- Section Graphique -->
    <div class="card mb-5">
        <div class="card-header">
            <h2 class="mb-0"><i class="fas fa-chart-line me-2"></i>Visualisation des intérêts</h2>
        </div>
        <div class="card-body">
            <canvas id="interestChart" height="400"></canvas>
        </div>
    </div>

    <!-- Section Tableau -->
    <div class="card">
        <div class="card-header">
            <h2 class="mb-0"><i class="fas fa-table me-2"></i>Détails des intérêts</h2>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="table-prets">
                    <thead class="thead-light">
                        <tr>
                            <th>Mois - Année</th>
                            <th>Total d'intérêts</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Ajout de Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  // Variable pour stocker le graphique
  let interestChart = null;

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
        input.classList.add('is-invalid');
      } else {
        input.classList.remove('is-invalid');
      }
    });
    
    const errorMessage = document.getElementById('error_message');
    if (!isValid) {
      errorMessage.textContent = 'Veuillez remplir tous les champs';
      errorMessage.style.display = 'block';
      return;
    }
    
    // Si tout est valide
    errorMessage.style.display = 'none';
    
    const formData = {
      moisDebut: inputs[0].value,
      anneeDebut: inputs[1].value,
      moisFin: inputs[2].value,
      anneeFin: inputs[3].value
    };
    
    const apiBase = "http://localhost/Projet_banque/project/ws";

    function ajax(method, url, data, callback) {
      const xhr = new XMLHttpRequest();
      xhr.open(method, apiBase + url, true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.onreadystatechange = () => {
        if (xhr.readyState === 4 && xhr.status === 200) {
          callback(JSON.parse(xhr.responseText));
        } else if (xhr.readyState === 4) {
          errorMessage.textContent = 'Erreur lors de la requête';
          errorMessage.style.display = 'block';
        }
      };
      xhr.send(data);
    }

    function chargerInterets() {
      ajax("GET", `/interets/${formData.moisDebut}/${formData.anneeDebut}/${formData.moisFin}/${formData.anneeFin}`, null, (data) => {
        // Mise à jour du tableau
        const tbody = document.querySelector("#table-prets tbody");
        tbody.innerHTML = '';
        
        // Préparation des données pour le graphique
        const labels = [];
        const values = [];
        
        // Ajouter les données au tableau et préparer les données pour le graphique
        data.forEach(item => {
          const row = document.createElement('tr');
          row.innerHTML = `
            <td>${item.mois} - ${item.annee}</td>
            <td class="amount positive-amount">${item.total} €</td>
          `;
          tbody.appendChild(row);
          
          labels.push(`${item.mois}/${item.annee}`);
          values.push(item.total);
        });
        
        // Créer ou mettre à jour le graphique
        updateChart(labels, values);
      });
    }

    function updateChart(labels, data) {
      const ctx = document.getElementById('interestChart').getContext('2d');
      
      // Détruire le graphique existant s'il y en a un
      if (interestChart) {
        interestChart.destroy();
      }
      
      // Créer un nouveau graphique
      interestChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: labels,
          datasets: [{
            label: 'Montant des intérêts (€)',
            data: data,
            backgroundColor: 'rgba(0, 95, 135, 0.7)',
            borderColor: 'rgba(0, 95, 135, 1)',
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            y: {
              beginAtZero: true,
              title: {
                display: true,
                text: 'Montant (€)'
              }
            },
            x: {
              title: {
                display: true,
                text: 'Période (Mois/Année)'
              }
            }
          },
          plugins: {
            tooltip: {
              callbacks: {
                label: function(context) {
                  return context.parsed.y.toFixed(2) + ' €';
                }
              }
            },
            legend: {
              position: 'top',
            }
          }
        }
      });
    }

    chargerInterets();
  });
</script>