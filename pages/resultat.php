<?php
require "../auth.php";

if (isset($_GET['depart']) && isset($_GET['arrivee']) && isset($_GET['date'])) {
    $ville_depart = htmlspecialchars($_GET['depart']); // Sécurise les entrées
    $ville_arrive = htmlspecialchars($_GET['arrivee']);
    $date_depart = htmlspecialchars($_GET['date']);

    // Requête SQL
    $sql = "SELECT ville_depart, ville_arrive, date_depart, heure_depart, prix, 
                   id_conducteur AS conducteur, statut 
            FROM covoiturage 
            WHERE ville_depart = ? 
              AND ville_arrive = ? 
              AND date_depart = ? 
              AND statut = 'prévu'";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$ville_depart, $ville_arrive, $date_depart]);

    $covoiturages = $stmt->fetchAll(PDO::FETCH_ASSOC); // Tous les trajets trouvés
    ?>
    

        <style>
            /* Style général */
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                background-color: #f4f4f4;
            }

            h1 {
                text-align: center;
                margin-top: 20px;
            }

            .container {
                width: 80%;
                margin: 20px auto;
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
                gap: 20px;
            }

            .covoiturage-card {
                background: white;
                border-radius: 8px;
                padding: 15px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                display: flex;
                flex-direction: column;
                gap: 10px;
            }

            .covoiturage-card .info h3 {
                margin: 0;
                font-size: 18px;
                font-weight: bold;
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

            .no-results {
                text-align: center;
                margin-top: 50px;
            }

            .no-results button {
                padding: 10px 20px;
                background-color: #007bff;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }

            .no-results button:hover {
                background-color: #0056b3;
            }
        </style>


        <h1>Résultats pour <?= htmlspecialchars($ville_depart) ?> → <?= htmlspecialchars($ville_arrive) ?> le <?= htmlspecialchars($date_depart) ?></h1>

        <div class="container">
            <?php if (count($covoiturages) > 0): ?>
                <?php foreach ($covoiturages as $covoiturage): ?>
                    <div class="covoiturage-card">
                        <div class="info">
                            <h3>Conducteur : <?= htmlspecialchars($covoiturage['conducteur']) ?></h3>
                            
                            <div class="trajet">
                                <strong>Départ :</strong> <?= htmlspecialchars($covoiturage['ville_depart']) ?><br>
                                <strong>Arrivée :</strong> <?= htmlspecialchars($covoiturage['ville_arrive']) ?><br>
                                <strong>Date :</strong> <?= htmlspecialchars($covoiturage['date_depart']) ?><br>
                                <strong>Heure :</strong> <?= htmlspecialchars($covoiturage['heure_depart']) ?>
                            </div>
                            
                            <div class="price">
                                Prix : <?= htmlspecialchars($covoiturage['prix']) ?> €
                            </div>
                        </div>

                        <button>Réserver une place</button>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-results">
                    <p>Aucun covoiturage trouvé pour votre recherche.</p>
                    <a href="/recherche" style="text-decoration: none;">
                        <button>Changer la recherche</button>
                    </a>
                </div>
            <?php endif; ?>
        </div>
        
    </body>
    </html>
<?php
} else {
    echo "<h1>Erreur : paramètres manquants !</h1>";
    echo "<p>Veuillez vérifier votre recherche.</p>";
}
?>

