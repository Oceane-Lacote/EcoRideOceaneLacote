 // Récupérer les éléments nécessaires
 const signUpLink = document.getElementById("sign-up-link");
 const loginLink = document.getElementById("login-link");
 const formSection = document.getElementById("form-section");
 const signupForm = document.getElementById("signup-form");
 const imageSection = document.querySelector('.image-section');
 const formTitle = document.getElementById("form-title");

 // Lorsque l'utilisateur clique sur "Inscris-toi maintenant!", afficher le formulaire d'inscription
 signUpLink.addEventListener("click", function(e) {
   e.preventDefault();
   formSection.innerHTML = `
     <h1>Rejoins le mouvement !</h1>
     <form action="#" method="post">
       <div class="input-group">
         <input type="text" name="prenom" placeholder="Prénom" required>
       </div>
       <div class="input-group">
         <input type="text" name="last-name" placeholder="Nom" required>
       </div>
       <div class="input-group">
         <input type="text" name="pseudo" placeholder="Pseudo" required>
       </div>
       <div class="input-group">
         <input type="email" name="email" placeholder="Email" required>
       </div>
       <div class="input-group">
         <input type="password" name="motdepasse" placeholder="Mot de passe" required>
       </div>
       <div class="input-group">
         <input type="password" name="confirm-motdepasse" placeholder="Confirmer le Mot de passe" required>
       </div>
       <button type="submit" class="login-button">S'inscrire</button>
       <p class="login-link">Tu as déjà un compte? <a href="#" id="login-link">Connecte toi maintenant!</a></p>
     </form>
   `;
   formTitle.innerText = "Rejoins le mouvement !";
 });

 // Lorsque l'utilisateur clique sur "Connecte toi maintenant!", afficher le formulaire de connexion
 loginLink.addEventListener("click", function(e) {
   e.preventDefault();
   formSection.innerHTML = `
     <h1>Connecte toi !</h1>
     <form action="#" method="post" id="login-form">
       <div class="input-group">
         <input type="email" name="email" placeholder="Email" required>
       </div>
       <div class="input-group">
         <input type="password" name="motdepasse" placeholder="Mot de passe" required>
       </div>
       <button type="submit" class="login-button">Se connecter</button>
       <p class="signup">Tu n'as pas de compte? <a href="#" id="sign-up-link">Inscris-toi maintenant!</a></p>
     </form>
   `;
   formTitle.innerText = "Connecte toi !";
 });

 // Récupérer les éléments nécessaires pour valider les mots de passe
const passwordInput = document.querySelector('[name="motdepasse"]');
const confirmPasswordInput = document.querySelector('[name="confirm-motdepasse"]');

// Vérifier que les mots de passe correspondent avant la soumission
document.getElementById('signup-form').addEventListener('submit', function(e) {
  if (passwordInput.value !== confirmPasswordInput.value) {
    e.preventDefault();
    alert("Les mots de passe ne correspondent pas.");
  }
});