// Exemple de gestion de la soumission du formulaire de modification de profil
document.getElementById('edit-profile-form').addEventListener('submit', function(event) {
    event.preventDefault(); // Empêche la soumission du formulaire

    // Récupérer les valeurs des champs
    const nom = document.getElementById('nom').value;
    const pseudo = document.getElementById('pseudo').value;
    const email = document.getElementById('email').value;
    const motdepasse = document.getElementById('motdepasse').value;

    // Afficher ou utiliser ces valeurs comme nécessaire
    console.log(`Nom: ${nom}, Pseudo: ${pseudo}, Email: ${email}, Mot de passe: ${motdepasse}`);

    // Fermer la modale après l'enregistrement (facultatif)
    const modal = bootstrap.Modal.getInstance(document.getElementById('editProfileModal'));
    modal.hide();

    // Ici, tu peux également envoyer les données à un serveur si nécessaire
}); 
 
// Quand on clique sur "Modifier la photo"
document.getElementById('change-photo').addEventListener('click', function() {
    // Ouvre le sélecteur de fichiers
    document.getElementById('photo-input').click();
});

// Quand un fichier est sélectionné
document.getElementById('photo-input').addEventListener('change', function(event) {
    const file = event.target.files[0]; // Récupérer le fichier sélectionné
    if (file) {
        const reader = new FileReader();
        
        // Quand le fichier est chargé
        reader.onload = function(e) {
            // Mettre à jour la photo de profil avec l'image sélectionnée
            document.getElementById('profile-img').src = e.target.result;
        }
        
        // Lire le fichier
        reader.readAsDataURL(file);
    }
});

 // Fonction pour supprimer une préférence
   function removePreference(button) {
    const preferenceCard = button.parentElement;
    preferenceCard.remove();
}
 
 // Ajout dynamique de véhicule
 const form = document.getElementById('vehicle-form');
 const vehicleList = document.getElementById('vehicle-list');

 form.addEventListener('submit', (e) => {
     e.preventDefault();
     
     // Récupérer les données du formulaire
     const immatriculation = document.getElementById('immatriculation').value;
     const marque = document.getElementById('marque').value;
     const modele = document.getElementById('modele').value;
     const couleur = document.getElementById('couleur').value;
     const electrique = document.getElementById('electrique').value === 'oui' ? 'Électrique' : 'Non Électrique';

     // Ajouter un nouveau véhicule à la liste
     const newVehicle = document.createElement('li');
     newVehicle.classList.add('list-group-item');
     newVehicle.textContent = `${marque} ${modele} - ${electrique}`;
     vehicleList.appendChild(newVehicle);

     // Réinitialiser le formulaire et fermer la modale
     form.reset();
     const modal = bootstrap.Modal.getInstance(document.getElementById('addVehicleModal'));
     modal.hide();
 });

   // Fonction pour supprimer un véhicule
   function deleteVehicle(button) {
    const listItem = button.parentElement;
    listItem.remove();
}