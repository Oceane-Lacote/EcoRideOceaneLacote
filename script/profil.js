// Gestion du changement de photo de profil
document.getElementById('change-photo').addEventListener('click', function() {
    document.getElementById('photo-input').click();
});

document.getElementById('photo-input').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('profile-img').src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
});

// Formulaire de mise à jour du profil
document.getElementById('edit-profile-form').addEventListener('submit', function(event) {
    event.preventDefault(); // Empêche la soumission du formulaire
    console.log("Formulaire soumis");

    // Récupérer les valeurs des champs
    const nom = document.getElementById('nom').value;
    const pseudo = document.getElementById('pseudo').value;
    const email = document.getElementById('email').value;
    const motdepasse = document.getElementById('motdepasse').value;

    console.log(`Nom: ${nom}, Pseudo: ${pseudo}, Email: ${email}, Mot de passe: ${motdepasse}`);

    // Fermer la modale après l'enregistrement (facultatif)
    const modal = bootstrap.Modal.getInstance(document.getElementById('editProfileModal'));
    modal.hide();
});

// Fonction pour supprimer un véhicule
function deleteVehicle(button) {
    button.closest('li').remove();
}

// Fonction pour supprimer une préférence
function deletePreference(button) {
    button.closest('li').remove();
}

// Fonction pour afficher ou cacher une section
function toggleSection(sectionId) {
    const section = document.getElementById(sectionId);
    section.classList.toggle('open');
}

// Formulaire pour démarrer un covoiturage
document.getElementById('startTripForm').addEventListener('submit', function (event) {
    event.preventDefault(); // Empêche la soumission classique
    console.log("Formulaire de covoiturage soumis");

    // Récupérer les informations saisies
    const depart = document.getElementById('depart-address').value;
    const arrivee = document.getElementById('arrival-address').value;
    const prix = parseFloat(document.getElementById('price').value); // Prix brut
    const vehicule = document.getElementById('vehicle-select').value;

    // Calculer le prix avec les crédits de la plateforme
    const prixFinal = prix - 2; // Soustrait les 2 crédits

    if (prixFinal <= 0) {
        alert("Le prix doit être supérieur à 2 crédits pour couvrir les frais de la plateforme.");
        return;
    }

    console.log(`Départ: ${depart}, Arrivée: ${arrivee}, Prix Final: ${prixFinal}, Véhicule: ${vehicule}`);

    // Afficher une modal de confirmation
    const confirmationMessage = `
        <p><strong>Départ:</strong> ${depart}</p>
        <p><strong>Arrivée:</strong> ${arrivee}</p>
        <p><strong>Prix Final:</strong> ${prixFinal} crédits</p>
        <p><strong>Véhicule:</strong> ${vehicule}</p>
    `;
    document.getElementById('tripConfirmationBody').innerHTML = confirmationMessage;
    const confirmationModal = new bootstrap.Modal(document.getElementById('tripConfirmationModal'));
    confirmationModal.show();
});

// Mettre à jour la liste des véhicules
document.getElementById('addVehicleModal').addEventListener('submit', function (event) {
    event.preventDefault(); // Empêche la soumission classique du formulaire

    // Récupérer les informations du véhicule
    const vehicleName = document.getElementById('vehicle-name').value;
    const vehicleModel = document.getElementById('vehicle-model').value;
    const vehicleColor = document.getElementById('vehicle-color').value;
    const vehiclePlace = document.getElementById('vehicle-place').value;
    const vehicleType = document.getElementById('vehicle-type').value;
    const vehiclePlate = document.getElementById('vehicle-plate').value;
    const vehicleDate = document.getElementById('vehicle-date').value;

    // Ajouter le véhicule à la liste
    const vehicleList = document.querySelector('.list-group');
    const newVehicle = document.createElement('li');
    newVehicle.classList.add('list-group-item', 'vehicle-item');
    newVehicle.innerHTML = `
        ${vehicleName}, ${vehicleModel} <br> ${vehicleColor}, ${vehiclePlace} <br> ${vehiclePlate} - ${vehicleDate} <br> ${vehicleType}
        <button class="btn-danger">&#10006;</button>
    `;
    vehicleList.appendChild(newVehicle);

    console.log(`Nouveau véhicule ajouté: ${vehicleName}, ${vehicleModel}, ${vehicleColor}, ${vehicleType}, ${vehiclePlate}, ${vehicleDate}, ${vehiclePlace}`);

    // Fermer la modale après l'ajout du véhicule
    const modal = bootstrap.Modal.getInstance(document.getElementById('addVehicleModal'));
    modal.hide();
});

// Mise à jour dynamique du prix
document.getElementById('price').addEventListener('input', function () {
    const prix = parseFloat(this.value);
    const prixNet = prix - 2;
    document.getElementById('net-price-display').innerText = prixNet > 0 ? `${prixNet} crédits nets` : "Prix insuffisant";
});

// Délégation d'événements pour la suppression de véhicules
document.querySelector('.list-group').addEventListener('click', function(event) {
    // Vérifier si l'élément cliqué est un bouton de suppression (croix)
    if (event.target && event.target.classList.contains('btn-danger')) {
        // Trouver l'élément parent (le <li>) de la croix
        const listItem = event.target.closest('li');
        
        // Supprimer cet élément de la liste
        listItem.remove();
    }
});

// Fonction pour afficher ou cacher une section
function toggleSection(sectionId) {
    // Ferme toutes les autres sections
    const allSections = document.querySelectorAll('.section-content');
    allSections.forEach(section => {
        if (section.id !== sectionId) {
            section.classList.remove('open');
        }
    });

    // Ouvre ou ferme la section ciblée
    const targetSection = document.getElementById(sectionId);
    if (targetSection.classList.contains('open')) {
        targetSection.classList.remove('open');
    } else {
        targetSection.classList.add('open');
    }
}

// Fonction pour afficher la note en étoiles
function displayStarRating(rating) {
    const stars = document.querySelectorAll('.star-rating .star');
    
    // Boucle à travers chaque étoile et ajoute ou retire la classe "filled"
    stars.forEach((star, index) => {
        if (index < rating) {
            star.classList.add('filled'); // Ajouter la classe "filled" pour l'or
        } else {
            star.classList.remove('filled'); // Retirer la classe "filled" pour l'enlever
        }
    });
}

const tripRatings = [4, 5, 3, 4, 5]; // Notes des anciens trajets
const averageRating = tripRatings.reduce((a, b) => a + b, 0) / tripRatings.length;
displayStarRating(Math.round(averageRating)); // Calcul et affichage de la note
