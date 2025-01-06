<?php

require "../auth.php";

$ville_depart = isset($_GET['depart']) ? $_GET['depart'] : '';
$ville_arrivee = isset($_GET['arrivee']) ? $_GET['arrivee'] : '';
$date_depart = isset($_GET['date']) ? $_GET['date'] : '';

try {
    $query = "
    SELECT c.*, u.pseudo, u.photo_profil, u.note 
    FROM covoiturage c
    JOIN utilisateur u ON c.id_conducteur = u.id_utilisateur
    WHERE c.ville_depart LIKE :ville_depart 
    AND c.ville_arrivee LIKE :ville_arrivee 
    AND c.date_depart = :date_depart
";    
    $stmt = $pdo->prepare($query);

    $ville_depart = "%" . $ville_depart . "%";
    $ville_arrivee = "%" . $ville_arrivee . "%";

    $stmt->bindParam(':ville_depart', $ville_depart);
    $stmt->bindParam(':ville_arrivee', $ville_arrivee);
    $stmt->bindParam(':date_depart', $date_depart);

    $stmt->execute();

    $covoiturages = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Erreur de connexion ou de requête : " . $e->getMessage();
}

?>

<style>
        /* Style de base */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            display: grid;
            grid-template-columns: 1fr 3fr;
            grid-gap: 20px;
        }

        .covoiturage-card {
            background: white;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .covoiturage-card img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
        }

        .covoiturage-card .info {
            flex-grow: 1;
        }

        .covoiturage-card .info h3 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }

        .covoiturage-card .info .note {
            color: #888;
            font-size: 14px;
        }

        .covoiturage-card .info .trajet {
            margin-top: 10px;
        }

        .covoiturage-card .info .price {
            margin-top: 10px;
            font-size: 16px;
            font-weight: bold;
            color: #27ae60;
        }

        .covoiturage-card button {
            padding: 10px 15px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .covoiturage-card button:hover {
            background-color: #2980b9;
        }

</style>

<div class="container">
        <?php if (count($covoiturages) > 0): ?>
            <?php foreach ($covoiturages as $covoiturage): ?>
                <div class="covoiturage-card">
                    <img src="<?= htmlspecialchars($covoiturage['photo_profil']) ?>" alt="Photo de profil">
                    
                    <div class="info">
                        <h3><?= htmlspecialchars($covoiturage['conducteur']) ?></h3>
                        <div class="note">Note: <?= htmlspecialchars($covoiturage['note']) ?> / 5</div>
                        
                        <div class="trajet">
                            <strong>Départ :</strong> <?= htmlspecialchars($covoiturage['ville_depart']) ?><br>
                            <strong>Arrivée :</strong> <?= htmlspecialchars($covoiturage['ville_arrivee']) ?><br>
                            <strong>Date de départ :</strong> <?= htmlspecialchars($covoiturage['date_depart']) ?><br>
                        </div>
                        
                        <div class="price">
                            Prix : <?= htmlspecialchars($covoiturage['prix']) ?> €
                        </div>
                    </div>

                    <button>Réserver une place</button>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucun covoiturage trouvé pour votre recherche.</p>
        <?php endif; ?>
    </div>

