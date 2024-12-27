<?php
// Connexion à la base de données
require_once "../auth.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['utilisateur_id'])) {
    die("Veuillez vous connecter pour accéder à cette page.");
}

// Récupérer les préférences envoyées par le formulaire
$smoking_preference = $_POST['smoking_preference'] ?? '';
$animal_preference = $_POST['animal_preference'] ?? '';
$custom_preference = $_POST['custom_preference'] ?? '';

// Préparer la requête pour mettre à jour les préférences de l'utilisateur
$stmt = $PDO->prepare("UPDATE utilisateur SET 
    preference_fumeur = :smoking_preference,
    preference_animal = :animal_preference,
    preference_personnalisee = :custom_preference
    WHERE utilisateur_id = :id");

$stmt->execute([
    ':smoking_preference' => $smoking_preference,
    ':animal_preference' => $animal_preference,
    ':custom_preference' => $custom_preference,
    ':id' => $_SESSION['utilisateur_id']
]);

// Rediriger ou afficher un message de succès
header('Location: profil.php?success=1');  // redirige vers la page de profil après la mise à jour
exit();
?>
