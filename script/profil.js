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
