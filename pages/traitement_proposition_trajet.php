<?php
require "../auth.php"; 

$sql = "SELECT vehicule_id, modele, marque, couleur, energie, nb_place FROM vehicule WHERE utilisateur_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$utilisateur_id]);
$vehicules = $stmt->fetchAll(PDO::FETCH_ASSOC);


if (isset($_POST['ville_depart'], $_POST['date_depart'], $_POST['heure_depart'], $_POST['ville_arrivee'], $_POST['heure_arrivee'], $_POST['date_arrivee'], $_POST['vehicule'], $_POST['prix'])) {
    $ville_depart = $_POST['ville_depart'];
    $date_depart = $_POST['date_depart'];
    $heure_depart = $_POST['heure_depart'];
    $ville_arrivee = $_POST['ville_arrivee'];
    $date_arrivee = $_POST['date_arrivee'];
    $heure_arrivee = $_POST['heure_arrivee'];
    $vehicule_id = $_POST['vehicule'];
    $prix = $_POST['prix'];

    try {
        // Insérer la proposition de trajet dans la base de données
        $stmt = $PDO->prepare("
            INSERT INTO trajet (ville_depart, date_depart, heure_depart, ville_arrivee, date_arrivee, heure_arrivee, vehicule_id, prix, utilisateur_id)
            VALUES (:ville_depart, :date_depart, :heure_depart, :ville_arrivee, :date_arrivee, :heure_arrivee, :vehicule_id, :prix, :utilisateur_id)
        ");
        $stmt->execute([
            ':ville_depart' => $ville_depart,
            ':date_depart' => $date_depart,
            ':heure_depart' => $heure_depart,
            ':ville_arrivee' => $ville_arrivee,
            ':date_arrivee' => $date_arrivee,
            ':heure_arrivee' => $heure_arrivee,
            ':vehicule_id' => $vehicule_id,
            ':prix' => $prix,
            ':utilisateur_id' => $_SESSION['utilisateur_id']
        ]);

        // Rediriger après succès
        header("Location: confirmation_trajet.php"); 
        exit();
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
} else {
    echo "Veuillez remplir tous les champs.";
}
