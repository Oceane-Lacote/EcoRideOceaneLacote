<?php
// Activer l'affichage des erreurs (en développement seulement)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté
if (isset($_SESSION['utilisateur_id'])) {
    // Supprimer toutes les variables de session
    session_unset();

    // Détruire la session
    session_destroy();

    // Créer un message flash de déconnexion réussie
    session_start(); // Redémarre la session pour pouvoir stocker un message flash
    $_SESSION['message'] = "Déconnexion réussie ! À bientôt.";

    // Rediriger vers la page d'accueil
    header('Location: /'); 
    exit(); 
} else {
    // Si l'utilisateur n'est pas connecté, redirige vers la page d'accueil
    header('Location: /');
    exit();
}
?>
