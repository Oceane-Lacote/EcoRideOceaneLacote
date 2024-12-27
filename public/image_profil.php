<?php
require "../auth.php"; 


if (!isset($_SESSION['utilisateur_id'])) {
    exit('Utilisateur non connecté');
}

try {
    $stmt = $PDO->prepare("SELECT image_profil FROM utilisateur WHERE utilisateur_id = :id");
    $stmt->execute([':id' => $_SESSION['utilisateur_id']]);
    $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($utilisateur && $utilisateur['image_profil']) {
      
        header('Content-Type: image/jpeg'); 

        echo $utilisateur['image_profil'];
        exit();
    } else {
        exit('Image non trouvée');
    }
} catch (PDOException $e) {
    exit("Erreur : " . $e->getMessage());
}
?>
