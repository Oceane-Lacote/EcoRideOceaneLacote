<?php
require "../auth.php"; 
if (!isset($_SESSION['utilisateur_id'])) {
    die("Erreur : Utilisateur non connecté.");
}

$utilisateur_id = $_SESSION['utilisateur_id'];
$success_message = null;
$error_message = null;

function getUtilisateur($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE utilisateur_id = :id");
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function updateUtilisateur($pdo, $data, $hasImage) {
    $query = "
        UPDATE utilisateur 
        SET nom = :nom, prenom = :prenom, pseudo = :pseudo, email = :email, roles = :roles, 
            preference_fumeur = :preference_fumeur, preference_animal = :preference_animal, 
            preference_personnalisee = :preference_personnalisee";
    if ($hasImage) {
        $query .= ", image_profil = :image_profil";
    }
    $query .= " WHERE utilisateur_id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute($data);
}

try {
    $utilisateur = getUtilisateur($PDO, $utilisateur_id);
    if (!$utilisateur) {
        die("Erreur : Utilisateur non trouvé.");
    }
} catch (PDOException $e) {
    die("Erreur de base de données : " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_vehicle'])) {
    $marque = htmlspecialchars($_POST['marque']);
    $modele = htmlspecialchars($_POST['modele']);
    $couleur = htmlspecialchars($_POST['couleur']);
    $energie = htmlspecialchars($_POST['energie']);
    $vehicule_date = $_POST['vehicule_date'];
    $vehicule_plate = htmlspecialchars($_POST['vehicule_plate']);
    $nb_place = (int)$_POST['nb_place'];

    try {
        $stmt = $PDO->prepare("
            INSERT INTO vehicule (utilisateur_id, marque, modele, couleur, energie, vehicule_date, vehicule_plate, nb_place) 
            VALUES (:utilisateur_id, :marque, :modele, :couleur, :energie, :vehicule_date, :vehicule_plate, :nb_place)
        ");
        $stmt->execute([
            ':utilisateur_id' => $utilisateur_id,
            ':marque' => $marque,
            ':modele' => $modele,
            ':couleur' => $couleur,
            ':energie' => $energie,
            ':vehicule_date' => $vehicule_date,
            ':vehicule_plate' => $vehicule_plate,
            ':nb_place' => $nb_place,
        ]);
        $success_message = "Véhicule ajouté avec succès !";
    } catch (PDOException $e) {
        $error_message = "Erreur lors de l'ajout du véhicule : " . $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $pseudo = htmlspecialchars($_POST['pseudo']);
    $email = htmlspecialchars($_POST['email']);
    $roles = implode(',', $_POST['roles']);
    $preference_fumeur = $_POST['preference_fumeur'];
    $preference_animal = $_POST['preference_animal'];
    $preference_personnalisee = $_POST['preference_personnalisee'];

    $image_profil = null;
    if (isset($_FILES['image_profil']['tmp_name']) && is_uploaded_file($_FILES['image_profil']['tmp_name'])) {
        $fileType = mime_content_type($_FILES['image_profil']['tmp_name']);
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($fileType, $allowedTypes)) {
            $image_profil = file_get_contents($_FILES['image_profil']['tmp_name']);
        } else {
            $error_message = "Format d'image non supporté.";
        }
    }

    $stmt = $PDO->prepare("SELECT id, marque, modele FROM vehicule WHERE utilisateur_id = :id");
$stmt->execute([':id' => $_SESSION['utilisateur_id']]);
$vehicules = $stmt->fetchAll(PDO::FETCH_ASSOC);

    try {
        $data = [
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':pseudo' => $pseudo,
            ':email' => $email,
            ':roles' => $roles,
            ':preference_fumeur' => $preference_fumeur,
            ':preference_animal' => $preference_animal,
            ':preference_personnalisee' => $preference_personnalisee,
            ':id' => $utilisateur_id
        ];
        if ($image_profil) {
            $data[':image_profil'] = $image_profil;
        }
        updateUtilisateur($PDO, $data, $image_profil ? true : false);
        $success_message = "Informations mises à jour avec succès!";
    } catch (PDOException $e) {
        $error_message = "Erreur lors de la mise à jour : " . $e->getMessage();
    }

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


}
?>

<!-- Modal Ajouter un Véhicule -->
<div class="modal fade" id="addVehicleModal" tabindex="-1" aria-labelledby="addVehicleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addVehicleModalLabel">Ajouter un véhicule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Affichage des messages de succès ou d'erreur -->
                <?php if (isset($success_message)): ?>
                    <div class="alert alert-success"><?php echo $success_message; ?></div>
                <?php elseif (isset($error_message)): ?>
                    <div class="alert alert-danger"><?php echo $error_message; ?></div>
                <?php endif; ?>

                <!-- Formulaire d'ajout de véhicule -->
                <form id="add_vehicle" method="POST">
                    <div class="mb-3">
                        <label for="marque" class="form-label">Marque</label>
                        <input type="text" class="form-control" id="marque" name="marque" required>
                    </div>
                    <div class="mb-3">
                        <label for="modele" class="form-label">Modèle</label>
                        <input type="text" class="form-control" id="modele" name="modele" required>
                    </div>
                    <div class="mb-3">
                        <label for="couleur" class="form-label">Couleur</label>
                        <input type="text" class="form-control" id="couleur" name="couleur" required>
                    </div>
                    <div class="mb-3">
                        <label for="energie" class="form-label">Énergie</label>
                        <select class="form-select" id="energie" name="energie" required>
                            <option value="Thermique">Thermique</option>
                            <option value="Électrique">Électrique</option>
                            <option value="Hybride">Hybride</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="vehicule_date" class="form-label">Date de première immatriculation</label>
                        <input type="date" class="form-control" id="vehicule_date" name="vehicule_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="vehicule_plate" class="form-label">Plaque d'immatriculation</label>
                        <input type="text" class="form-control" id="vehicule_plate" name="vehicule_plate" required>
                    </div>
                    <div class="mb-3">
                        <label for="nb_place" class="form-label">Nombre de places</label>
                        <input type="number" class="form-control" id="nb_place" name="nb_place" min="1" max="8" required>
                    </div>
                    <button type="submit" class="btn btn-primary" name="add_vehicle">Ajouter le véhicule</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Modifier Informations Personnelles -->
<div class="modal fade" id="editPersonalInfoModal" tabindex="-1" aria-labelledby="editPersonalInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPersonalInfoModalLabel">Modifier les informations personnelles</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <!-- Section Informations personnelles -->
                    <h6>Profil :</h6>
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="nom" name="nom" value="<?php echo $utilisateur['nom']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="prenom" class="form-label">Prénom</label>
                        <input type="text" class="form-control" id="prenom" name="prenom" value="<?php echo $utilisateur['prenom']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="pseudo" class="form-label">Pseudo</label>
                        <input type="text" class="form-control" id="pseudo" name="pseudo" value="<?php echo $utilisateur['pseudo']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $utilisateur['email']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="image_profil" class="form-label">Photo de Profil</label>
                        <input type="file" class="form-control" id="image_profil" name="image_profil">
                    </div>
                    <hr>
                    <!-- Section Préférences -->
                    <h6>Préférences :</h6>
                    <div class="mb-3">
                        <label for="preference_fumeur" class="form-label">Préférence fumeur</label>
                        <select class="form-select" id="preference_fumeur" name="preference_fumeur">
                            <option value="Oui" <?php if ($utilisateur['preference_fumeur'] === 'Oui') echo 'selected'; ?>>Oui</option>
                            <option value="Non" <?php if ($utilisateur['preference_fumeur'] === 'Non') echo 'selected'; ?>>Non</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="preference_animal" class="form-label">Préférence animal</label>
                        <select class="form-select" id="preference_animal" name="preference_animal">
                            <option value="Oui" <?php if ($utilisateur['preference_animal'] === 'Oui') echo 'selected'; ?>>Oui</option>
                            <option value="Non" <?php if ($utilisateur['preference_animal'] === 'Non') echo 'selected'; ?>>Non</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="preference_personnalisee" class="form-label">Préférence personnalisée</label>
                        <textarea class="form-control" id="preference_personnalisee" name="preference_personnalisee"><?php echo $utilisateur['preference_personnalisee']; ?></textarea>
                    </div>
                    <hr>
                    <!-- Section Rôles -->
                    <h6>Rôles :</h6>
<div class="mb-3">
    <label for="roles" class="form-label"></label>
    <div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="role_passager" name="roles[]" value="passager" <?php if (in_array('passager', explode(',', $utilisateur['roles']))) echo 'checked'; ?>>
            <label class="form-check-label" for="role_passager">
                Passager
            </label>
        </div>

        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="role_conducteur" name="roles[]" value="conducteur" <?php if (in_array('conducteur', explode(',', $utilisateur['roles']))) echo 'checked'; ?>>
            <label class="form-check-label" for="role_conducteur">
                Conducteur
            </label>
        </div>
    </div>
</div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" name="update_user">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="offerRideModal" tabindex="-1" aria-labelledby="offerRideModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="offerRideModalLabel">Proposer un trajet</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Formulaire pour proposer un trajet -->
                <form method="POST">
                    <!-- Ville de départ -->
                    <div class="mb-3">
                        <label for="ville_depart" class="form-label">Ville de départ</label>
                        <input type="text" class="form-control" id="ville_depart" name="ville_depart" required>
                    </div>

                    <!-- Date de départ -->
                    <div class="mb-3">
                        <label for="date_depart" class="form-label">Date de départ</label>
                        <input type="date" class="form-control" id="date_depart" name="date_depart" required>
                    </div>

                    <!-- Heure de départ -->
                    <div class="mb-3">
                        <label for="heure_depart" class="form-label">Heure de départ</label>
                        <input type="time" class="form-control" id="heure_depart" name="heure_depart" required>
                    </div>

                    <!-- Ville d'arrivée -->
                    <div class="mb-3">
                        <label for="ville_arrivee" class="form-label">Ville d'arrivée</label>
                        <input type="text" class="form-control" id="ville_arrivee" name="ville_arrivee" required>
                    </div>

                    <!-- Date d'arrivée -->
                    <div class="mb-3">
                        <label for="date_arrivee" class="form-label">Date d'arrivée</label>
                        <input type="date" class="form-control" id="date_arrivee" name="date_arrivee" required>
                    </div>

                    <!-- Heure d'arrivée -->
                    <div class="mb-3">
                        <label for="heure_arrivee" class="form-label">Heure d'arrivée</label>
                        <input type="time" class="form-control" id="heure_arrivee" name="heure_arrivee" required>
                    </div>

                    <div class="mb-3">
    <label for="vehicule" class="form-label">Sélectionner votre véhicule</label>
    <?php if (isset($vehicules) && is_array($vehicules)): ?>
    <select class="form-select" id="vehicule" name="vehicule" required>
        <option value="">Sélectionner un véhicule</option>
        <?php foreach ($vehicules as $vehicule): 
            
                $vehicule_id = $vehicule['vehicule_id'] ?? '';
                $marque_modele = ($vehicule['marque'] ?? '') . ' ' . ($vehicule['modele'] ?? '');
            ?>
            <option value="<?= htmlspecialchars($vehicule_id) ?>">
                <?= htmlspecialchars($marque_modele) ?>
            </option>
        <?php endforeach; ?>
    </select>
<?php else: ?>
    <p>Aucun véhicule disponible.</p>
<?php endif; ?>

</div>

                    <!-- Sélectionner le prix -->
                    <div class="mb-3">
                        <label for="prix" class="form-label">Prix du trajet (en crédits)</label>
                        <input type="number" class="form-control" id="prix" name="prix" min="1" required>
                        <small>Note : 2 crédits seront retenus par la plateforme.</small>
                    </div>

                    <!-- Soumettre le formulaire -->
                    <button type="submit" class="btn btn-primary">Proposer le trajet</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir supprimer ce véhicule ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non</button>
                <button type="button" id="confirm-delete" class="btn btn-supprimer">Oui</button>
            </div>
        </div>
    </div>
</div>
