<?php
session_start();

if (isset($_SESSION['utilisateur_id'])) {
    session_unset();

    session_destroy();

    session_start(); 
    $_SESSION['message'] = "Déconnexion réussie ! À bientôt.";

    header('Location: /'); 
    exit(); 
} else {
    header('Location: /');
    exit();
}
?>
