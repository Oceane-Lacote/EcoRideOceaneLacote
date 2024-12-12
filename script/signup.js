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

// Fonction de validation du formulaire avant soumission
document.getElementById('signup-form').addEventListener('submit', function(event) {
  // Empêcher la soumission si les mots de passe ne sont pas valides
  const motdepasse = document.querySelector('input[name="motdepasse"]').value.trim(); // Retirer les espaces superflus
  const confirmMotdepasse = document.querySelector('input[name="confirm-motdepasse"]').value.trim();

  if (motdepasse === "" || confirmMotdepasse === "") {
    alert("Les mots de passe ne peuvent pas être vides.");
    event.preventDefault();  // Annule la soumission du formulaire si condition non remplie
    return;
  }

  if (motdepasse !== confirmMotdepasse) {
    alert("Les mots de passe ne correspondent pas.");
    event.preventDefault();  // Annule la soumission du formulaire si les mots de passe ne correspondent pas
    return;
  }
});
