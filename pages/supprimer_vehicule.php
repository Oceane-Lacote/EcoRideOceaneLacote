<?php
require "../auth.php";

if (!isset($_SESSION['utilisateur_id'])) {
    die("Erreur : Utilisateur non connecté.");
}

if (!isset($_GET['vehicule_id'])) {
    die("Erreur : ID du véhicule manquant.");
}

$vehicule_id = (int) $_GET['vehicule_id'];
$utilisateur_id = (int) $_SESSION['utilisateur_id'];

try {
    // Suppression sécurisée
    $stmt = $pdo->prepare("DELETE FROM vehicule WHERE vehicule_id = :vehicule_id AND utilisateur_id = :utilisateur_id");
    $stmt->execute([':vehicule_id' => $vehicule_id, ':utilisateur_id' => $utilisateur_id]);

    if ($stmt->rowCount() > 0) {
        header("Location: profil.php?success=Véhicule supprimé avec succès.");
    } else {
        header("Location: profil.php?error=Impossible de supprimer ce véhicule.");
    }
    exit();
} catch (PDOException $e) {
    die("Erreur de base de données : " . $e->getMessage());
}
?>
