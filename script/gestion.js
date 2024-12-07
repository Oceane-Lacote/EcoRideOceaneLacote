 // Fonction pour valider l'avis
 document.querySelectorAll('.btn-validate').forEach(button => {
    button.addEventListener('click', function () {
        // Récupérer la ligne de l'avis
        const row = this.closest('tr');

        // Récupérer le chauffeur et sa note actuelle
        const chauffeurName = row.querySelector('.chauffeur-name').textContent;
        const currentRating = parseInt(row.getAttribute('data-rating')); // Récupère la note actuelle du chauffeur

        // Mise à jour de la note du chauffeur (ici, on ajoute 1 à la note actuelle pour l'exemple)
        const newRating = currentRating + 1; // Vous pouvez ajuster cette logique selon les besoins

        // Mettre à jour la note du chauffeur
        row.setAttribute('data-rating', newRating);
        row.querySelector('.chauffeur-name').textContent = `${chauffeurName} (${newRating}/5)`; // Affiche la nouvelle note dans la cellule Chauffeur
        row.querySelector('td:nth-child(3)').textContent = `${newRating}/5`; // Affiche la nouvelle note dans la colonne "Avis"

        // Désactiver le bouton de validation et refuser après validation
        this.disabled = true;
        row.querySelector('.btn-refuse').disabled = true;
    });
});

// Fonction pour refuser l'avis
document.querySelectorAll('.btn-refuse').forEach(button => {
    button.addEventListener('click', function () {
        const row = this.closest('tr');
        row.querySelector('.btn-validate').disabled = true;
        this.disabled = true; // Désactive également le bouton "Refuser"
        row.querySelector('td:nth-child(3)').textContent = 'Refusé'; // Marque l'avis comme refusé
    });
});

// Ajouter un événement sur les boutons "Vérifier"
document.querySelectorAll('.details-btn').forEach(button => {
    button.addEventListener('click', function () {
        // Récupérer la ligne parent de ce bouton
        const row = this.closest('tr');

        // Récupérer les informations à afficher
        const covoiturageNumber = row.cells[0].textContent.trim();
        const passengerName = row.cells[1].getAttribute('data-passenger');
        const passengerEmail = row.cells[1].getAttribute('data-passenger-email');
        const driverName = row.cells[2].getAttribute('data-driver');
        const driverEmail = row.cells[2].getAttribute('data-driver-email');
        const tripDescription = row.cells[3].textContent.trim();
        const comment = row.cells[1].getAttribute('data-comment');

        // Remplir les informations dans la modal
        document.getElementById('modal-covoiturage-number').textContent = covoiturageNumber;
        document.getElementById('modal-passenger').textContent = passengerName;
        document.getElementById('modal-passenger-email').textContent = passengerEmail;
        document.getElementById('modal-driver').textContent = driverName;
        document.getElementById('modal-driver-email').textContent = driverEmail;
        document.getElementById('modal-trip-description').textContent = tripDescription;
        document.getElementById('modal-comment').textContent = comment;
    });
});