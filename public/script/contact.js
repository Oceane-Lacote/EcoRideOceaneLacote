// Fonction pour soumettre le signalement
function submitReport() {
    const description = document.getElementById('reportDescription').value;
    const fileUpload = document.getElementById('fileUpload').files[0];

    if (description.trim() === '') {
        alert('Veuillez décrire le comportement inapproprié.');
        return;
    }

    // Exemple de traitement
    console.log('Description:', description);
    if (fileUpload) {
        console.log('Fichier:', fileUpload.name);
    }

    alert('Votre signalement a été soumis avec succès!');
    // Réinitialiser le formulaire
    document.getElementById('reportDescription').value = '';
    document.getElementById('fileUpload').value = ''; // Réinitialiser le champ de fichier

    // Redirection vers la page d'accueil
    window.location.href = '/';
}

// Fonction pour soumettre la demande de support technique
function submitTechnicalSupport() {
    const supportDescription = document.getElementById('supportDescription').value;

    if (supportDescription.trim() === '') {
        alert('Veuillez décrire votre problème.');
        return;
    }

    // Exemple de traitement pour la demande de support
    console.log('Demande de support technique:', supportDescription);

    alert('Votre demande de support technique a été soumise avec succès!');
    // Réinitialiser le formulaire
    document.getElementById('supportDescription').value = '';

    // Redirection vers la page d'accueil
    window.location.href = '/';
}

// Fonction pour demander de l'aide
function requestHelp() {
    alert('Nous avons reçu votre demande d\'aide. Un agent vous contactera bientôt.');
    
    // Redirection vers la page d'accueil
    window.location.href = '/';
}

// Fonction pour afficher le contenu correspondant à l'élément cliqué 
function showContent(contentId) {
    // Cacher tous les contenus
    const contents = document.querySelectorAll('.support-content');
    contents.forEach(content => {
        content.style.display = 'none'; // Cacher chaque contenu
    });

    // Afficher le contenu sélectionné
    const selectedContent = document.getElementById(contentId);
    if (selectedContent) {
        selectedContent.style.display = 'block'; // Afficher le contenu sélectionné
    }
}

// Fonction pour afficher les détails du problème sélectionné
function showDetails(event, problemType) {
    event.preventDefault(); // Empêche le lien de naviguer

    // Réinitialiser la sélection
    const links = document.querySelectorAll('#account ul li a');
    links.forEach(link => {
        link.classList.remove('selected'); // Supprime la classe 'selected' de tous les liens
    });

    // Ajouter la classe 'selected' au lien cliqué
    const selectedLink = event.target;
    selectedLink.classList.add('selected');

    const description = document.getElementById('accountProblemDescription');
    const detailsSection = document.getElementById('accountDetails');

    switch (problemType) {
        case 'loginIssues':
            description.textContent = "Vous avez un problème avec : Problèmes de connexion. Assurez-vous que vos identifiants sont corrects.";
            break;
        case 'accountModification':
            description.textContent = "Vous avez un problème avec : Modification des informations du compte. Accédez à 'Mon Compte' pour mettre à jour vos informations.";
            break;
        case 'accountSuspension':
            description.textContent = "Vous avez un problème avec : Suspension ou désactivation du compte. Contactez-nous pour plus d'informations.";
            break;
        case 'securityIssues':
            description.textContent = "Vous avez un problème avec : Problèmes de sécurité. Changez votre mot de passe et contactez notre support.";
            break;
        default:
            description.textContent = "";
            break;
    }

    detailsSection.style.display = 'block'; // Affiche la section de détails
}
