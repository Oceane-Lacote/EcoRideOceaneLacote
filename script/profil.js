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

function deleteVehicle(button) {
    const listItem = button.closest('li'); // Récupérer le parent <li>
    listItem.remove();
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

// Supprimer un véhicule
function deleteVehicle(button) {
    const listItem = button.closest('li');
    listItem.remove();
    console.log("Véhicule supprimé.");
}

// Mettre à jour la liste des véhicules
document.getElementById('addVehicleModal').addEventListener('submit', function (event) {
    event.preventDefault();
    const vehicleName = document.getElementById('vehicle-name').value;
    const vehicleModel = document.getElementById('vehicle-model').value;
    const vehicleColor = document.getElementById('vehicle-color').value;
    const vehicleType = document.getElementById('vehicle-type').value;
    const vehiclePlate = document.getElementById('vehicle-plate').value;

    const vehicleList = document.querySelector('.list-group');
    const newVehicle = document.createElement('li');
    newVehicle.classList.add('list-group-item', 'vehicle-item');
    newVehicle.innerHTML = `
        ${vehicleName}, ${vehicleModel} <br> ${vehicleColor} <br> ${vehiclePlate} - ${vehicleType}
        <button class="delete-btn" onclick="deleteVehicle(this)">&#10006;</button>
    `;
    vehicleList.appendChild(newVehicle);
    console.log(`Nouveau véhicule ajouté: ${vehicleName}, ${vehicleModel}, ${vehicleColor}, ${vehicleType}, ${vehiclePlate}`);

    // Réinitialiser le formulaire
    document.getElementById('addVehicleForm').reset();
});

// Mise à jour dynamique du prix
document.getElementById('price').addEventListener('input', function () {
    const prix = parseFloat(this.value);
    const prixNet = prix - 2;
    document.getElementById('net-price-display').innerText = prixNet > 0 ? `${prixNet} crédits nets` : "Prix insuffisant";
});

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

// Bouton pour enregistrer les rôles sélectionnés
document.getElementById('saveRolesBtn').addEventListener('click', () => {
    // Récupérer les checkboxes dans la modale
    const checkboxes = document.querySelectorAll('.role-selection .form-check-input');
    const selectedRoles = Array.from(checkboxes)
        .filter(box => box.checked) // Récupère uniquement les cases cochées
        .map(box => box.value);    // Récupère les valeurs des cases cochées

    // Mettre à jour l'affichage dans le profil
    const rolesDisplay = document.getElementById('selectedRoles');
    if (selectedRoles.length > 0) {
        rolesDisplay.textContent = selectedRoles.join(', ');
    } else {
        rolesDisplay.textContent = 'Aucun';
    }

    // Fermer la modale (optionnel, si désiré)
    const modal = bootstrap.Modal.getInstance(document.getElementById('editProfileModal'));
    modal.hide();
});

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