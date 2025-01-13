// Récupérer les éléments nécessaires
const signUpLink = document.getElementById("sign-up-link");
const loginLink = document.getElementById("login-link");
const loginForm = document.getElementById("form-section");
const signUpForm = document.getElementById("signup-form");
const messageContainer = document.getElementById("message-container"); // Zone pour afficher le message

// Lorsque l'utilisateur clique sur "Inscris-toi!", afficher le formulaire d'inscription
signUpLink.addEventListener("click", function (e) {
  e.preventDefault();
  loginForm.style.display = "none"; 
  signUpForm.style.display = "block"; 
});

loginLink.addEventListener("click", function (e) {
  e.preventDefault();
  signUpForm.style.display = "none"; 
  loginForm.style.display = "block";
});

document.addEventListener("DOMContentLoaded", function () {
  document.getElementById("signup-form").addEventListener("submit", function (event) {
    const signinMotdepasse = document.querySelector('input[id="signin-motdepasse"]');
    const confirmMotdepasse = document.querySelector('input[id="confirm-motdepasse"]');

    if (!signinMotdepasse || !confirmMotdepasse) {
      console.error("Les éléments de mot de passe ou confirmation de mot de passe sont introuvables.");
      return; 
    }

    const motdepasseValue = signinMotdepasse.value.trim();
    const confirmMotdepasseValue = confirmMotdepasse.value.trim();

    if (motdepasseValue === "" || confirmMotdepasseValue === "") {
      alert("Les mots de passe ne peuvent pas être vides.");
      event.preventDefault();
      return;
    }

    if (motdepasseValue !== confirmMotdepasseValue) {
      alert("Les mots de passe ne correspondent pas.");
      event.preventDefault(); 
      return;
    }

    event.preventDefault(); 
    messageContainer.textContent = "Inscription confirmée, bienvenue !";
    messageContainer.style.display = "block"; 
    messageContainer.style.color = "green"; 

  });
});

