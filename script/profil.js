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

document.querySelectorAll('.vehicle-item').forEach((vehicle) => {
    const type = vehicle.getAttribute('data-type'); // Récupère le type du véhicule

    if (type === 'electrique') {
        // Crée un élément image
        const icon = document.createElement('img');
        icon.src = '../Ressources/voiture-ecolo.png'; // Chemin de l'icône
        icon.alt = 'Électrique';
        icon.className = 'vehicle-icon'; // Classe CSS pour le style

        // Insère l'image avant la croix
        const deleteBtn = vehicle.querySelector('.delete-btn');
        vehicle.insertBefore(icon, deleteBtn);
    }
});

// Gestion des rôles (Passager, Chauffeur, Les deux)
document.querySelectorAll('.role-selection input').forEach((checkbox) => {
    checkbox.addEventListener('change', function () {
        const selectedRoles = Array.from(document.querySelectorAll('.role-selection input:checked'))
            .map((input) => input.value);
        console.log("Rôles sélectionnés:", selectedRoles);
    });
});

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
document.getElementById('addVehicleForm').addEventListener('submit', function (event) {
    event.preventDefault();
    const vehicleName = document.getElementById('vehicle-name').value;
    const vehicleType = document.getElementById('vehicle-type').value;
    const vehiclePlate = document.getElementById('vehicle-plate').value;

    const vehicleList = document.querySelector('.list-group');
    const newVehicle = document.createElement('li');
    newVehicle.classList.add('list-group-item', 'vehicle-item');
    newVehicle.innerHTML = `
        ${vehicleName} <br> ${vehiclePlate} - ${vehicleType}
        <button class="delete-btn" onclick="deleteVehicle(this)">&#10006;</button>
    `;
    vehicleList.appendChild(newVehicle);
    console.log(`Nouveau véhicule ajouté: ${vehicleName}, ${vehicleType}, ${vehiclePlate}`);

    // Réinitialiser le formulaire
    document.getElementById('addVehicleForm').reset();
});

// Mise à jour dynamique du prix
document.getElementById('price').addEventListener('input', function () {
    const prix = parseFloat(this.value);
    const prixNet = prix - 2;
    document.getElementById('net-price-display').innerText = prixNet > 0 ? `${prixNet} crédits nets` : "Prix insuffisant";
});
