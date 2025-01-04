<?php
require "../auth.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $avis_id = $_POST['avis_id'];
    $action = $_POST['action'];

    if ($action === 'validate') {
        $statut = 'validé';
    } elseif ($action === 'refuse') {
        $statut = 'refusé';
    } else {
        die('Action invalide.');
    }

    // Mise à jour du statut dans la base de données
    $query = "UPDATE avis SET statut = :statut WHERE id = :avis_id";
    $stmt = $PDO->prepare($query);
    $stmt->execute([
        ':statut' => $statut,
        ':avis_id' => $avis_id,
    ]);

    // Redirection après traitement
    header("Location: pages/employe.php");
    exit;
}
?>
