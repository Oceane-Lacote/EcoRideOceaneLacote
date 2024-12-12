<?php

// auth.php
header("Content-Type: application/json"); // Pour que le front-end puisse traiter le retour comme du JSON
header("Access-Control-Allow-Origin: *"); // Permettre les requêtes cross-origin (facultatif en prod)

// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecoride";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Erreur de connexion à la base de données."]);
    exit();
}

// Vérifier si c'est une inscription ou une connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'signup') {
        // Inscription
        $prenom = trim($_POST['prenom'] ?? '');
        $nom = trim($_POST['nom'] ?? '');
        $pseudo = trim($_POST['pseudo'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $motdepasse = $_POST['motdepasse'] ?? '';

        // Valider les données et vérifier si l'utilisateur existe déjà
        if (empty($prenom) || empty($nom) || empty($pseudo) || empty($email) || empty($motdepasse)) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Tous les champs sont obligatoires."]);
            exit();
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Email invalide."]);
            exit();
        }

        // Vérifier si l'email existe déjà
        $stmt = $conn->prepare("SELECT * FROM utilisateur WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            http_response_code(409);
            echo json_encode(["status" => "error", "message" => "Cet email est déjà utilisé."]);
            exit();
        }

        // Insérer dans la base de données
        $hashed_password = password_hash($motdepasse, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("INSERT INTO utilisateur (prenom, nom, pseudo, email, mot_de_passe) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $prenom, $nom, $pseudo, $email, $hashed_password);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Inscription réussie !"]);
        } else {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => "Erreur lors de l'inscription."]);
        }
    } elseif ($action === 'login') {
        // Connexion
        $email = trim($_POST['email'] ?? '');
        $motdepasse = $_POST['motdepasse'] ?? '';

        // Vérifier si l'email existe
        $stmt = $conn->prepare("SELECT * FROM utilisateur WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            // Vérifier le mot de passe
            if (password_verify($motdepasse, $user['mot_de_passe'])) {
                echo json_encode(["status" => "success", "message" => "Connexion réussie !", "data" => $user]);
            } else {
                http_response_code(401);
                echo json_encode(["status" => "error", "message" => "Mot de passe incorrect."]);
            }
        } else {
            http_response_code(404);
            echo json_encode(["status" => "error", "message" => "Utilisateur introuvable."]);
        }
    }
} else {
    // Méthode non autorisée
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Méthode non autorisée."]);
}

// Fermer la connexion à la base de données
$conn->close();
