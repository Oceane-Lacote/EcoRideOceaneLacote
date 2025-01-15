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
        // Détecter le type MIME de l'image
        $imageData = $utilisateur['image_profil'];
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->buffer($imageData);

        // Définir l'en-tête de type MIME en fonction du type de l'image
        header("Content-Type: $mimeType");
        echo $imageData;
        exit();
    } else {
        exit('Image non trouvée');
    }
} catch (PDOException $e) {
    exit("Erreur : " . $e->getMessage());
}

?>
