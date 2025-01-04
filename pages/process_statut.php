<?php
require "../auth.php";

$action = $_POST['action'] ?? null;
$utilisateur_id = $_POST['utilisateur_id'] ?? null;

if ($action && $utilisateur_id) {
    $stmt = $PDO->prepare("SELECT statut FROM utilisateur WHERE utilisateur_id = :utilisateur_id");
    $stmt->execute([':utilisateur_id' => $utilisateur_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if ($action === 'suspendre' && $user['statut'] === 'actif') {
            $stmt = $PDO->prepare("UPDATE utilisateur SET statut = 'suspendu' WHERE utilisateur_id = :utilisateur_id");
            $stmt->execute([':utilisateur_id' => $utilisateur_id]);
            echo "Le compte a été suspendu.";
        } elseif ($action === 'reactiver' && $user['statut'] === 'suspendu') {
            $stmt = $PDO->prepare("UPDATE utilisateur SET statut = 'actif' WHERE utilisateur_id = :utilisateur_id");
            $stmt->execute([':utilisateur_id' => $utilisateur_id]);
            echo "Le compte a été réactivé.";
        } else {
            echo "Aucune modification nécessaire, le statut est déjà correct.";
        }
    } else {
        echo "Utilisateur non trouvé.";
    }
} else {
    echo "Données manquantes.";
}
?>
