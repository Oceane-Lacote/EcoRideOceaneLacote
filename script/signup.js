// Récupérer les éléments nécessaires
const signUpLink = document.getElementById("sign-up-link");
const loginLink = document.getElementById("login-link");
const loginForm = document.getElementById("form-section");
const signUpForm = document.getElementById("signup-form");

// Lorsque l'utilisateur clique sur "Inscris-toi!", afficher le formulaire d'inscription
signUpLink.addEventListener("click", function(e) {
  e.preventDefault();
  loginForm.style.display = "none";  // Cacher le formulaire de connexion
  signUpForm.style.display = "block";  // Afficher le formulaire d'inscription
});

// Lorsque l'utilisateur clique sur "Connecte toi maintenant!", afficher le formulaire de connexion
loginLink.addEventListener("click", function(e) {
  e.preventDefault();
  signUpForm.style.display = "none";  // Cacher le formulaire d'inscription
  loginForm.style.display = "block";  // Afficher le formulaire de connexion
});

document.addEventListener("DOMContentLoaded", function() {

// Fonction de validation du formulaire avant soumission
document.getElementById('signup-form').addEventListener('submit', function(event) {
  // Récupérer les valeurs des champs
  const signinMotdepasse = document.querySelector('input[id="signin-motdepasse"]');
  const confirmMotdepasse = document.querySelector('input[id="confirm-motdepasse"]');

  // Afficher les éléments récupérés pour vérifier si le querySelector trouve les bons éléments
  console.log("Mot de passe élément : ", motdepasse); 
  console.log("Confirmer le mot de passe élément : ", confirmMotdepasse);

  // Vérifier si les éléments existent avant de récupérer la valeur
  if (!motdepasse || !confirmMotdepasse) {
    console.error("Les éléments de mot de passe ou confirmation de mot de passe sont introuvables.");
    return; // Si les éléments ne sont pas trouvés, on arrête le traitement
  }

  const motdepasseValue = motdepasse.value.trim();
  const confirmMotdepasseValue = confirmMotdepasse.value.trim();

  // Afficher les valeurs récupérées pour débogage
  console.log("Mot de passe : ", motdepasseValue); 
  console.log("Confirmer le mot de passe : ", confirmMotdepasseValue);

  // Vérification que les mots de passe ne sont pas vides et qu'ils correspondent
  if (motdepasseValue === "" || confirmMotdepasseValue === "") {
    alert("Les mots de passe ne peuvent pas être vides.");
    event.preventDefault();  // Empêcher l'envoi du formulaire
    return;
  }

  if (motdepasseValue !== confirmMotdepasseValue) {
    alert("Les mots de passe ne correspondent pas.");
    event.preventDefault();  // Empêcher l'envoi du formulaire
    return;
  }

  // Si tout va bien, soumettre normalement (sans AJAX) en supprimant l'action AJAX ici
  console.log("Les mots de passe sont valides et prêts à être envoyés.");
  // À ce point, le formulaire est prêt à être soumis normalement.
});
});
