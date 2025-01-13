// Récupérer les paramètres de l'URL (données de recherche)
const params = new URLSearchParams(window.location.search);

const depart = params.get('depart');
const arrivee = params.get('arrivee');
const date = params.get('date');

// Afficher les résultats
const resultatsDiv = document.getElementById('resultats');
resultatsDiv.innerHTML = `
  <p><strong>Départ:</strong> ${depart}</p>
  <p><strong>Arrivée:</strong> ${arrivee}</p>
  <p><strong>Date:</strong> ${date}</p>
`;

// Ajouter ici la logique pour afficher des résultats dynamiques en fonction des paramètres de recherche
// Par exemple, afficher des trajets, des conducteurs, etc.
// Exemple :
resultatsDiv.innerHTML += `
  <div class="trip-card">
    <p><strong>Conducteur:</strong> Jane Doe</p>
    <p><strong>Prix:</strong> 100€</p>
    <p><strong>Horaire:</strong> 08:00 - 12:00</p>
    <button class="btn-learn-more">En savoir plus</button>
  </div>
`;

     // Simuler des résultats de recherche
     const results = []; // Si la recherche ne retourne rien, laisse ce tableau vide pour simuler aucune correspondance.

     const resultsContainer = document.getElementById('results-container');
     const errorMessage = document.getElementById('error-message');

     if (results.length === 0) {
         // Si aucun résultat, afficher le message d'erreur
         errorMessage.style.display = 'block';
     } else {
         // Si des résultats sont trouvés, les afficher
         results.forEach(result => {
             const resultDiv = document.createElement('div');
             resultDiv.textContent = `Covoiturage trouvé de ${result.depart} à ${result.arrivee} le ${result.date}.`;
             resultsContainer.appendChild(resultDiv);
         });
     }