<?php
session_start();

if (isset($_SESSION['utilisateur_id'])) {
    header('Location: /covoiturage');
    exit();
} else {
    header('Location: /demandeconnexion');
    exit();
}
?>