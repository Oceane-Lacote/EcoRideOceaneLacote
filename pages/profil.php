<?php
if (!isset($_SESSION['utilisateur_id'])) {
    echo '<div class="error-message">Veuillez vous connecter pour accéder à cette fonctionnalité !</div>';
    exit();
}

require "../auth.php"; 

try {
    $stmt = $PDO->prepare("SELECT * FROM utilisateur WHERE utilisateur_id = :id");
    $stmt->execute([':id' => $_SESSION['utilisateur_id']]);
    $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($utilisateur && isset($utilisateur['image_profil'])) {
        $image_profil = $utilisateur['image_profil'];
    } else {
        $image_profil = null; 
    }
} catch (PDOException $e) {
    exit("Erreur : " . $e->getMessage());
}

try {
    $stmt = $PDO->prepare("SELECT pseudo, nom, prenom, email, roles, preference_fumeur, preference_animal, preference_personnalisee FROM utilisateur WHERE utilisateur_id = :id");
    $stmt->execute([':id' => $_SESSION['utilisateur_id']]);
    $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$utilisateur) {
        die("Erreur : Utilisateur non trouvé.");
    }
} catch (PDOException $e) {
    die("Erreur de base de données : " . $e->getMessage());
}

try {
    $stmt = $PDO->prepare("SELECT date_depart, heure_depart, ville_depart, date_arrive, heure_arrive, ville_arrive FROM covoiturage WHERE id_conducteur= :id");
    $stmt->execute([':id' => $_SESSION['utilisateur_id']]);
    $trajet = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Gestion des erreurs de base de données
    die("Erreur de base de données : " . $e->getMessage());
}

$stmt = $PDO->prepare("SELECT AVG(note) AS moyenne FROM avis WHERE utilisateur_id_recepteur = :id");
$stmt->execute(params: [':id' => $_SESSION['utilisateur_id']]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$moyenne = ($result && $result['moyenne']) ? round($result['moyenne'], 2) : 0;

$stmt = $PDO->prepare("SELECT modele, couleur, marque, energie, vehicule_date, vehicule_plate, nb_place FROM vehicule WHERE utilisateur_id = :id");
$stmt->execute([':id' => $_SESSION['utilisateur_id']]);
$vehicules = $stmt->fetchAll(PDO::FETCH_ASSOC);

$query = $PDO->prepare("SELECT note FROM avis WHERE utilisateur_id_recepteur = :utilisateur_id LIMIT 1");
$query->execute([':utilisateur_id' => $_SESSION['utilisateur_id']]);  
$note = $query->fetchColumn();

try {
    $stmt = $PDO->prepare("SELECT total_credits FROM credits WHERE utilisateur_id = :id");
    $stmt->execute([':id' => $_SESSION['utilisateur_id']]);
    $credits = $stmt->fetch(PDO::FETCH_ASSOC);

    $credits_utilisateur = ($credits && isset($credits['total_credits'])) ? $credits['total_credits'] : 0;
} catch (PDOException $e) {
    die("Erreur de base de données pour les crédits : " . $e->getMessage());
}
?>


<style>
   
    .section-box {
        flex: 1 1 calc(30% - 20px);
        max-width: 30%;
        min-width: 250px;
    }

    .section-header {
        cursor: pointer;
        padding: 10px 10px;
        background-color: #267aa7;
        color: white;
        border-radius: 5px;
        text-align: center;
        font-weight: bold;
        margin-bottom: 10px;
        transition: background-color 0.3s ease;
    }

    .section-header:hover {
        background-color: #e69500;
    }

    .section-content {
        display: none;
        background-color: #D9D9D9;
        padding: 20px;
        border-radius: 5px;
        margin-bottom: 15px;
    }

    .section-content.open {
        display: block;
    }

    .btn-modifier-info {
        background-color: #FCB941;
        color: white;
        border: none;
        padding: 10px 20px;
        font-size: 14px;
        border-radius: 5px;
    }

    .btn-modifier-info:hover {
        background-color: #e69500;
    }

    .profile-header {
        background-color: #D9D9D9;
        color: black;
        padding: 20px;
        border-radius: 10px;
        text-align: center;
    }

    .profile-header img {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 3px solid white;
        margin-bottom: 10px;
    }

    .profile-header h3 {
        margin-bottom: 10px;
    }

    
    .star {
    font-size: 24px; 
    color: gray;     
    background-color: white;
    padding: 0.2%;
}

.star.filled {
    color: gold; 
    background-color: white;
    padding: 0.2%;
}

.star.half-filled {
    color: orange;
    background-color: white;    
    padding: 0.2%;
}

.row {
        display: flex;
        justify-content: flex-end; 
        margin-bottom: 20px;
        margin-top: 20px;
        margin-left: 50px;
 }

    .col-md-4 {
        flex: 1;
        max-width: 30%;
        min-width: 250px;
        margin-right: 20px;
    }


    .list-group {
        margin-top: 10px;
        gap: 15px;
        display: flex;
        flex-direction: column;
    }

    .list-group-item {
        position: relative;
        padding: 15px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #f9f9f9;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .vehicle-item {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .btn-danger {
        position: absolute;
        top: 5px;
        right: 5px;
        border: none;
        background-color: transparent;
        font-size: 1.5rem;
        color: #dc3545;
        cursor: pointer;
    }

    .btn-supprimer {
        
        border: none;
        background-color: #dc3545;
        color: white;
        cursor: pointer;
    }

    .btn-danger:hover {
        color: #a71d2a;
    }


    .btn-modifier-info {
        margin-top: 15px;
        display: block;
        width: 100%;
        text-align: center;
    }

    #vehicles {
        margin-top: 20px;
    }

    .credits-info {
    background-color: #f9f9f9;
    padding: 5px 10px;
    border-radius: 8px;
    max-width: 200px;
}

.credits-info h4 {
    text-align: center; 
    margin: 0;          
    font-size: 18px;    
}

.credits-balance {
    display: flex;            
    justify-content: center;  
    align-items: center;      
}

.credits-balance strong {
    font-size: 30px;
    color: #28a745;
    text-align: center;       
    margin: 0;                
}
    
</style>

<div class="container mt-5">
    <div class="profile-header">
        <?php if ($image_profil): ?>
            <img src="data:image/jpeg;base64,<?php echo base64_encode($image_profil); ?>" alt="Image de profil">
        <?php endif; ?>
        <h3 id="pseudo"><?php echo htmlspecialchars($utilisateur['pseudo']); ?></h3>
        <div class="star-rating" id="star-rating">
            <?php
            for ($i = 1; $i <= 5; $i++) {
                if ($i <= floor($note)) {
                    echo '<span class="star filled">&#9733;</span>'; 
                } elseif ($i - $note < 1) {
                    echo '<span class="star half-filled">&#9734;</span>'; 
                } else {
                    echo '<span class="star">&#9734;</span>'; 
                }                
            }
            ?>
        </div>

<div class="credits-info text-end">
            <h4>Vos crédits</h4>
            <p class="credits-balance">
            <strong><?php echo htmlspecialchars($credits_utilisateur); ?></strong>
            </p>
        </div>
    </div>
</div>
</div>
</div>

    <div class="row">
        <div class="col-md-4">
                <div class="section-header" onclick="toggleSection('personal-info')">Profil</div>
                <div id="personal-info" class="section-content">
                    <p><strong>Nom:</strong> <?php echo htmlspecialchars($utilisateur['nom']); ?></p>
                    <p><strong>Prénom:</strong> <?php echo htmlspecialchars($utilisateur['prenom']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($utilisateur['email']); ?></p>
                    <p><strong>Rôles:</strong> <?php echo htmlspecialchars($utilisateur['roles']); ?></p>
                    <br>
                    <p><strong>Préférences de voyage</strong> 
                    <p><strong>Fumeur:</strong> <?php echo htmlspecialchars($utilisateur['preference_fumeur']); ?></p>
                    <p><strong>Animaux:</strong> <?php echo htmlspecialchars($utilisateur['preference_animal']); ?></p>
                    <p><strong>Autres:</strong> <?php echo htmlspecialchars($utilisateur['preference_personnalisee']); ?></p>
                    <button class="btn btn-modifier-info btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#editPersonalInfoModal">Modifier</button>
                </div>
            </div>

        <div class="col-md-4">
                <div class="section-header" onclick="toggleSection('vehicles')">Véhicules</div>
                <div id="vehicles" class="section-content">
                    <ul class="list-group" id="vehicle-list">
                        <?php if (!empty($vehicules)): ?>
                            <?php foreach ($vehicules as $vehicule): ?>
                                <li class="list-group-item">
                                    <div>
                                        <span><strong>Marque:</strong> <?= htmlspecialchars($vehicule['marque']) ?></span><br>
                                        <span><strong>Modèle:</strong> <?= htmlspecialchars($vehicule['modele']) ?></span><br>
                                        <span><strong>Couleur:</strong> <?= htmlspecialchars($vehicule['couleur']) ?></span><br>
                                        <span><strong>Énergie:</strong> <?= htmlspecialchars($vehicule['energie']) ?></span><br>
                                        <span><strong>Places:</strong> <?= htmlspecialchars($vehicule['nb_place']) ?></span>
                                    </div>
                                    <button class="btn btn-danger" 
                        data-bs-toggle="modal" 
                        data-bs-target="#confirmationModal" 
                        data-vehicule-id="<?= isset($vehicule['vehicule_id']) ? htmlspecialchars($vehicule['vehicule_id']) : '' ?>">
                    &#10006;
                </button>                                
            </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li class="list-group-item">Aucun véhicule trouvé.</li>
                        <?php endif; ?>
                    </ul>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addVehicleModal">Ajouter un véhicule</button>
                </div>
            </div>
       

        <div class="col-md-4">
                <div class="section-header" onclick="toggleSection('history')">Historique</div>
                <div id="history" class="section-content">
    <?php if ($utilisateur): ?>
        <li class="list-group-item">
        <div>
        <span><strong>Date et Heure de départ :</strong> <?= htmlspecialchars($trajet['date_depart']) . ' - ' . htmlspecialchars($trajet['heure_depart']) ?></span><br>
        <span><strong>Ville de départ :</strong> <?= htmlspecialchars($trajet['ville_depart']) ?></span><br>
        <span><strong>Date et Heure d'arrivée :</strong> <?= htmlspecialchars($trajet['date_arrive']) . ' - ' . htmlspecialchars($trajet['heure_arrive']) ?></span><br>
        <span><strong>Ville d'arrivée :</strong> <?= htmlspecialchars($trajet['ville_arrive']) ?></span><br>

</div>
        <?php else: ?>
        <p>Pas de trajet récent disponible.</p>
    <?php endif; ?>
</div>
            </div>
        </div>
        
    <div class="row">
        <div class="col-md-7">
            <div class="section-box">
                <div class="section-header" onclick="toggleSection('offer-ride')">Proposer un trajet</div>
                <div id="offer-ride" class="section-content">
                    <p>Proposez un trajet à la communauté :</p>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#offerRideModal">Proposer un trajet</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'modals.php'; ?>
<script src="script/profil.js"></script>