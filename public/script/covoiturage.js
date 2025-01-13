// Ouvrir le calendrier lorsque l'utilisateur clique sur l'input de type "date"
document.getElementById("date").addEventListener("click", function () {
  this.showPicker(); // Méthode native pour ouvrir le sélecteur dans les navigateurs modernes
});

// Liste de villes (mockup pour démonstration)
const villes = [
    "Paris", "Pau", "Pamiers", "Parthenay", "Pantin", "Perpignan",
    "Lyon", "Lille", "Marseille", "Bordeaux", "Nice", "Nantes",
    "Montpellier", "Strasbourg", "Toulouse", "Rennes", "Dijon"
  ];
  
  // Fonction pour afficher les suggestions
  function afficherSuggestions(inputElement, suggestionsElement, villes) {
    const query = inputElement.value.toLowerCase(); // Texte entré
    suggestionsElement.innerHTML = ""; // Effacer les anciennes suggestions
    
    if (query.length > 1) { // Afficher les suggestions après 2 lettres
      const filtres = villes.filter(ville => ville.toLowerCase().startsWith(query));
      
      filtres.forEach(ville => {
        const suggestion = document.createElement("div");
        suggestion.textContent = ville; // Nom de la ville
        suggestion.addEventListener("click", () => {
          inputElement.value = ville; // Remplir le champ avec la ville
          suggestionsElement.innerHTML = ""; // Effacer les suggestions
        });
        suggestionsElement.appendChild(suggestion);
      });
    }
  }
  
  // Écouteurs d'événements pour les champs de recherche
  const inputDepart = document.getElementById("depart");
  const suggestionsDepart = document.getElementById("suggestions-depart");
  
  inputDepart.addEventListener("input", () => {
    afficherSuggestions(inputDepart, suggestionsDepart, villes);
  });
  
  const inputArrivee = document.getElementById("arrivee");
  const suggestionsArrivee = document.getElementById("suggestions-arrivee");
  
  inputArrivee.addEventListener("input", () => {
    afficherSuggestions(inputArrivee, suggestionsArrivee, villes);
  });

  function toggleFilters() {
    const filters = document.getElementById('filters');
    filters.classList.toggle('show'); // Si les filtres sont visibles, on les cache, et inversement
  }

  // Gérer la durée maximale (slider)
const durationSlider = document.getElementById('duration');
const durationValue = document.getElementById('duration-value');
durationSlider.addEventListener('input', function () {
  durationValue.textContent = `${durationSlider.value} heures`;
});

// Sélectionner le curseur et l'élément d'affichage du prix
const priceSlider = document.getElementById('price');
const priceDisplay = document.getElementById('price-value');

// Initialiser l'affichage du prix sans la devise
priceDisplay.textContent = priceSlider.value;

// Ajouter un gestionnaire d'événements pour le mouvement du curseur
priceSlider.addEventListener('input', function() {
  // Mettre à jour l'affichage avec la valeur actuelle sans la devise
  priceDisplay.textContent = priceSlider.value;
});
// Gestion des filtres (checkbox et select)
const electricCheckbox = document.getElementById('electric');
const earlyDepartureCheckbox = document.getElementById('early-departure');
const driverRatingSelect = document.getElementById('driver-rating');

// Exemple pour afficher les valeurs filtrées (peut être ajusté selon les résultats)
electricCheckbox.addEventListener('change', function () {
  console.log('Voiture électrique:', electricCheckbox.checked);
});

earlyDepartureCheckbox.addEventListener('change', function () {
  console.log('Départ tôt:', earlyDepartureCheckbox.checked);
});

driverRatingSelect.addEventListener('change', function () {
  console.log('Note du conducteur:', driverRatingSelect.value);
});

// Sélectionner les étoiles
const stars = document.querySelectorAll('.star');

// Fonction pour mettre à jour les étoiles en fonction de la note
function updateRating(rating) {
  stars.forEach(star => {
    if (parseInt(star.dataset.value) <= rating) {
      star.classList.add('filled');  // Remplir l'étoile
    } else {
      star.classList.remove('filled');  // Vider l'étoile
    }
  });
}

// Ajouter un gestionnaire d'événements de clic sur chaque étoile
stars.forEach(star => {
  star.addEventListener('click', function () {
    const rating = parseInt(star.dataset.value);  // Obtenir la valeur de la note à partir de l'étoile cliquée
    updateRating(rating);  // Mettre à jour les étoiles en fonction de la note
    console.log('Note du conducteur:', rating);  // Afficher la note (tu peux l'utiliser pour filtrer les résultats)
  });
});

function showTripModal() {
  document.getElementById('tripModal').style.display = 'block';
}

function closeTripModal() {
  document.getElementById('tripModal').style.display = 'none';
}