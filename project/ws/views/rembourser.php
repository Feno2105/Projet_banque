<form onsubmit="ajouterOuModifier(event)" method="post">
    <fieldset>
        <legend>Période de remboursement</legend>

        <label for="mois_debut">Mois de début :</label>
        <select name="mois_debut" id="mois_debut" required>
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

        <label for="mois_fin">Mois de fin :</label>
        <select name="mois_fin" id="mois_fin" required>
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

        <label for="annee_debut">Année de début :</label>
        <input type="number" name="annee_debut" id="annee_debut" placeholder="ex : 2023" min="1900" max="2100" required>

        <label for="annee_fin">Année de fin :</label>
        <input type="number" name="annee_fin" id="annee_fin" placeholder="ex : 2025" min="1900" max="2100" required>
    </fieldset>

    <button type="submit">Valider</button>
</form>

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
            errorCallback(new Error(xhr.statusText));
          }
        }
      };
      xhr.send(data ? data : null);
    }
    function ajouterOuModifier(event) {
    event.preventDefault(); // empêche le rechargement de la page
    const params = new URLSearchParams(window.location.search);
    const id = params.get("id");

    const mois_debut = document.getElementById("mois_debut").value;
    const mois_fin = document.getElementById("mois_fin").value;
    const annee_debut = document.getElementById("annee_debut").value;
    const annee_fin = document.getElementById("annee_fin").value;

    const data = `id_pret=${encodeURIComponent(id)}&mois_debut=${encodeURIComponent(mois_debut)}&mois_fin=${encodeURIComponent(mois_fin)}&annee_debut=${encodeURIComponent(annee_debut)}&annee_fin=${encodeURIComponent(annee_fin)}`;

    if (id) {
        ajax("POST", `/remboursement/`, data, () => {
            // success callback
        });
    }
}
</script>
