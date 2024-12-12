<?php

// auth.php
header("Content-Type: application/json"); // Pour que le front-end puisse traiter le retour comme du JSON
header("Access-Control-Allow-Origin: *"); // Permettre les requêtes cross-origin (facultatif en prod)

// Connexion à la base de données
$servername = "localhost";
$username = "administrateur";
$password = "@dminEc0Rid3!";
$dbname = "ecoride";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Déterminer l'action demandée
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'uploadPhoto':
        handlePhotoUpload($pdo);
        break;
    case 'updateProfile':
        handleProfileUpdate($pdo);
        break;
    case 'addVehicle':
        handleAddVehicle($pdo);
        break;
    case 'deleteVehicle':
        handleDeleteVehicle($pdo);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Action non reconnue']);
        break;
}

// Gestion de l'upload de la photo
function handlePhotoUpload($pdo) {
    if (!empty($_FILES['photo']['tmp_name'])) {
        $photoPath = 'uploads/' . uniqid() . '_' . basename($_FILES['photo']['name']);
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath)) {
            // Sauvegarder le chemin dans la base de données si nécessaire
            // Exemple : mise à jour de l'utilisateur connecté
            // $userId = 1; // Remplacez par l'ID de l'utilisateur connecté
            // $stmt = $pdo->prepare("UPDATE utilisateurs SET photo = ? WHERE id = ?");
            // $stmt->execute([$photoPath, $userId]);
            echo json_encode(['success' => true, 'path' => $photoPath]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Échec du téléchargement de la photo']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Aucun fichier reçu']);
    }
}

// Gestion de la mise à jour du profil
function handleProfileUpdate($pdo) {
    $data = json_decode(file_get_contents('php://input'), true);

    $nom = $data['nom'] ?? '';
    $pseudo = $data['pseudo'] ?? '';
    $email = $data['email'] ?? '';
    $motdepasse = $data['motdepasse'] ?? '';
    $roles = $data['roles'] ?? [];

    if ($nom && $pseudo && $email && $motdepasse) {
        // Exemple : mise à jour dans la base de données
        // $userId = 1; // Remplacez par l'ID de l'utilisateur connecté
        $hashedPassword = password_hash($motdepasse, PASSWORD_DEFAULT);
        $rolesStr = implode(',', $roles);

        $stmt = $pdo->prepare("UPDATE utilisateurs SET nom = ?, pseudo = ?, email = ?, motdepasse = ?, roles = ? WHERE id = ?");
        $success = $stmt->execute([$nom, $pseudo, $email, $hashedPassword, $rolesStr, 1]); // Remplacez 1 par l'ID utilisateur

        if ($success) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Échec de la mise à jour du profil']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Données manquantes']);
    }
}

// Gestion de l'ajout d'un véhicule
function handleAddVehicle($pdo) {
    $data = json_decode(file_get_contents('php://input'), true);

    $name = $data['name'] ?? '';
    $model = $data['model'] ?? '';
    $color = $data['color'] ?? '';
    $places = $data['places'] ?? '';
    $type = $data['type'] ?? '';
    $plate = $data['plate'] ?? '';
    $date = $data['date'] ?? '';

    if ($name && $model && $color && $places && $type && $plate && $date) {
        $stmt = $pdo->prepare("INSERT INTO vehicules (name, model, color, places, type, plate, date) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $success = $stmt->execute([$name, $model, $color, $places, $type, $plate, $date]);

        if ($success) {
            $vehicleId = $pdo->lastInsertId();
            echo json_encode(['success' => true, 'vehicleId' => $vehicleId]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Échec de l’ajout du véhicule']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Données manquantes']);
    }
}

// Gestion de la suppression d'un véhicule
function handleDeleteVehicle($pdo) {
    $vehicleId = $_GET['id'] ?? '';

    if ($vehicleId) {
        $stmt = $pdo->prepare("DELETE FROM vehicules WHERE id = ?");
        $success = $stmt->execute([$vehicleId]);

        if ($success) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Échec de la suppression du véhicule']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'ID du véhicule manquant']);
    }
}

