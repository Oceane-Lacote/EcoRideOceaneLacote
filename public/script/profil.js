// Fonction pour ouvrir ou fermer une section
function toggleSection(sectionId) {
    // Fermer toutes les autres sections
    const allSections = document.querySelectorAll('.section-content');
    allSections.forEach(section => {
        if (section.id !== sectionId) {
            section.classList.remove('open');
        }
    });

    // Ouvrir ou fermer la section ciblée
    const targetSection = document.getElementById(sectionId);
    if (targetSection.classList.contains('open')) {
        targetSection.classList.remove('open');
    } else {
        targetSection.classList.add('open');
    }
}

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

            // Envoi de la photo au backend
            const formData = new FormData();
            formData.append('photo', file);

            fetch('/utilisateur.php?action=uploadPhoto', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    console.log('Photo mise à jour avec succès');
                } else {
                    console.error('Erreur lors de la mise à jour de la photo:', result.message);
                }
            })
            .catch(error => console.error('Erreur réseau:', error));
        };
        reader.readAsDataURL(file);
    }
});

// Formulaire de mise à jour du profil
document.getElementById('edit-profile-form').addEventListener('submit', function(event) {
    event.preventDefault(); // Empêche la soumission classique du formulaire

    // Récupérer les données du formulaire
    const nom = document.getElementById('nom').value;
    const prénom = document.getElementById('prénom').value;
    const pseudo = document.getElementById('pseudo').value;
    const email = document.getElementById('email').value;
    const motdepasse = document.getElementById('motdepasse').value;

    // Récupérer les rôles sélectionnés
    const isPassager = document.getElementById('rolePassager').checked;
    const isChauffeur = document.getElementById('roleChauffeur').checked;
    const roles = [];
    if (isPassager) roles.push('Passager');
    if (isChauffeur) roles.push('Chauffeur');

    // Envoyer les données au backend
    fetch('/utilisateur.php?action=updateProfile', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ nom, pseudo, email, motdepasse, roles }),
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            console.log('Profil mis à jour avec succès');

            // Mettre à jour le frontend
            document.getElementById('display-lastname').innerText = nom;
            document.getElementById('display-name').innerText = prénom;
            document.getElementById('display-pseudo').innerText = pseudo;
            document.getElementById('display-email').innerText = email;

            // Fermer la modale après succès
            const modal = bootstrap.Modal.getInstance(document.getElementById('editProfileModal'));
            modal.hide();
        } else {
            console.error('Erreur lors de la mise à jour du profil:', result.message);
        }
    })
    .catch(error => console.error('Erreur réseau:', error));
});

// Ajouter un véhicule
document.getElementById('addVehicleForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Empêche la soumission classique du formulaire

    // Récupérer les informations du véhicule
    const vehicleData = {
        name: document.getElementById('vehicle-name').value,
        model: document.getElementById('vehicle-model').value,
        color: document.getElementById('vehicle-color').value,
        places: document.getElementById('vehicle-place').value,
        type: document.getElementById('vehicle-type').value,
        plate: document.getElementById('vehicle-plate').value,
        date: document.getElementById('vehicle-date').value,
    };

    // Envoyer les données au backend
    fetch('/vehicule.php?action=addVehicle', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(vehicleData),
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            console.log('Véhicule ajouté avec succès');
            // Ajouter visuellement le véhicule à la liste
            const vehicleList = document.querySelector('.list-group');
            const newVehicle = document.createElement('li');
            newVehicle.classList.add('list-group-item', 'vehicle-item');
            newVehicle.dataset.id = result.vehicleId; // Assure que chaque véhicule a un ID
            newVehicle.innerHTML = `
                ${vehicleData.name}, ${vehicleData.model} <br>
                ${vehicleData.color}, ${vehicleData.places} <br>
                ${vehicleData.plate} - ${vehicleData.date} <br>
                ${vehicleData.type}
                <button class="btn-danger" onclick="deleteVehicle(this)">&#10006;</button>
            `;
            vehicleList.appendChild(newVehicle);

            // Fermer la modale après succès
            const modal = bootstrap.Modal.getInstance(document.getElementById('addVehicleModal'));
            modal.hide();
        } else {
            console.error('Erreur lors de l’ajout du véhicule:', result.message);
        }
    })
    .catch(error => console.error('Erreur réseau:', error));
});

// Supprimer un véhicule
function deleteVehicle(button) {
    const vehicleItem = button.closest('li');
    const vehicleId = vehicleItem.dataset.id; // Assurez-vous que chaque véhicule a un ID unique côté HTML

    fetch(`/vehicule.php?action=deleteVehicle&id=${vehicleId}`, {
        method: 'POST',
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            console.log('Véhicule supprimé avec succès');
            vehicleItem.remove();
        } else {
            console.error('Erreur lors de la suppression du véhicule:', result.message);
        }
    })
    .catch(error => console.error('Erreur réseau:', error));
}
document.querySelectorAll('[data-bs-target="#confirmationModal"]').forEach(button => {
    button.addEventListener('click', function () {
        const vehiculeId = this.getAttribute('data-vehicule-id'); // Récupère l'ID du véhicule
        document.getElementById('confirm-delete').setAttribute('data-vehicule-id', vehiculeId); // Associe au bouton
    });
});

document.getElementById('confirm-delete').addEventListener('click', function () {
    const vehiculeId = this.getAttribute('data-vehicule-id'); // Récupère l'ID du bouton
    if (vehiculeId) {
        // Redirection vers un script PHP pour supprimer
        window.location.href = `supprimer_vehicule.php?vehicule_id=${vehiculeId}`;
    }
});
