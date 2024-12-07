document.querySelector('.btn-search').addEventListener('click', function() {
    // Récupérer les valeurs de la recherche
    const depart = document.getElementById('depart').value;
    const arrivee = document.getElementById('arrivee').value;
    const date = document.getElementById('date').value;
    
    // Simuler une recherche de covoiturages
    const results = searchCarpools(depart, arrivee, date);
    
    // Afficher ou masquer les résultats
    const resultsContainer = document.getElementById('results-container');
    const noResultsMessage = document.getElementById('no-results');
    
    // Vider le conteneur des résultats avant de le remplir
    resultsContainer.innerHTML = '';
    
    if (results.length > 0) {
      // Afficher les résultats
      results.forEach(result => {
        const resultCard = createResultCard(result);
        resultsContainer.appendChild(resultCard);
      });
      noResultsMessage.style.display = 'none'; // Cacher le message d'absence de résultats
    } else {
      // Afficher le message "Aucun résultat"
      noResultsMessage.style.display = 'block';
    }
  });
  
  // Fonction pour simuler une recherche de covoiturages (remplacer avec une vraie logique de recherche)
  function searchCarpools(depart, arrivee, date) {
    // Exemple de données de covoiturage (à remplacer par une recherche dynamique)
    const carpoolData = [
      {
        driver: 'JaneD',
        departure: 'Chamonix, France',
        arrival: 'Paris, France',
        date: '2025-03-25',
        price: 120,
        seatsAvailable: 2
      },
      // Autres trajets peuvent être ajoutés ici
    ];
  
    // Recherche fictive : filtrer les trajets selon les critères (à ajuster selon tes besoins)
    return carpoolData.filter(carpool => 
      carpool.departure.includes(depart) && 
      carpool.arrival.includes(arrivee) &&
      carpool.date === date
    );
  }
  
  // Fonction pour créer une carte de résultat
  function createResultCard(result) {
    const card = document.createElement('div');
    card.classList.add('trip-card');
    
    card.innerHTML = `
      <div class="trip-header">
        <div class="profile-container">
          <img src="../Ressources/jane doe.jpg" alt="Photo de Profil" class="profile-img">
          <div class="profile-info">
            <h2 class="driver-name">${result.driver}</h2>
            <div class="rating">
              <span class="star filled"></span>
              <span class="star filled"></span>
              <span class="star filled"></span>
              <span class="star filled"></span>
              <span class="star"></span>
            </div>
          </div>
        </div>
        <div class="price-info">
          <p class="price">${result.price}€</p>
          <p class="seats-available">${result.seatsAvailable} sièges disponibles</p>
        </div>
      </div>
  
      <div class="trip-details">
        <p class="trip-date">${result.date}</p>
        <div class="trip-timing">
          <div class="departure">
            <h1>08:00</h1>
            <p>${result.departure}</p>
          </div>
          <div class="arrival">
            <h1>12:00</h1>
            <p>${result.arrival}</p>
          </div>
        </div>
      </div>
  
      <button class="learn-more-btn">En savoir plus</button>
    `;
    
    return card;
  }