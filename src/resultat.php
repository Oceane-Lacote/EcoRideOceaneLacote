<?php
header('Content-Type: application/json');

try {
    // Connexion à la base de données
    $pdo = new PDO('mysql:host=localhost;dbname=ecoride;charset=utf8', 'administrateur', '@dminEc0Rid3!');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupération des paramètres GET
    $depart = isset($_GET['depart']) ? $_GET['depart'] : '';
    $arrivee = isset($_GET['arrivee']) ? $_GET['arrivee'] : '';
    $date = isset($_GET['date']) ? $_GET['date'] : '';

    // Requête SQL pour chercher les trajets
    $sql = "SELECT * FROM trajets WHERE depart = :depart AND arrivee = :arrivee AND date = :date";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':depart' => $depart,
        ':arrivee' => $arrivee,
        ':date' => $date,
    ]);

    // Récupérer les résultats
    $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Retourner les résultats sous forme JSON
    echo json_encode($resultats);
} catch (Exception $e) {
    // En cas d'erreur, retourner un message d'erreur
    echo json_encode(['error' => 'Erreur de connexion ou de requête']);
}
