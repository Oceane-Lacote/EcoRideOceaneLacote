<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Connexion à la base de données
$host = 'localhost';
$dbname = 'ecoride';
$username = 'administrateur';
$password = '@dminEc0Rid3!';
$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$action = $_POST['action'] ?? '';

// Si l'action est l'inscription
if ($action === 'signup') {
    $prenom = $_POST['prenom'] ?? '';
    $nom = $_POST['nom'] ?? '';
    $pseudo = $_POST['pseudo'] ?? '';
    $email = $_POST['email'] ?? '';
    $motdepasse = $_POST['motdepasse'] ?? '';

    if (empty($prenom) || empty($nom) || empty($pseudo) || empty($email) || empty($motdepasse)) {
        echo json_encode(["status" => "error", "message" => "Tous les champs sont obligatoires."]);
        exit();
    }

    // Vérifier si l'email existe déjà
    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo json_encode(["status" => "error", "message" => "Cet email est déjà utilisé."]);
        exit();
    }

    // Hasher le mot de passe avant de l'insérer
    $hashedPassword = password_hash($motdepasse, PASSWORD_BCRYPT);

    // Insertion dans la base de données
    $stmt = $pdo->prepare("INSERT INTO utilisateur (prenom, nom, pseudo, email, mot_de_passe) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$prenom, $nom, $pseudo, $email, $hashedPassword]);

    echo json_encode(["status" => "success", "message" => "Inscription réussie !"]);
}
?>
