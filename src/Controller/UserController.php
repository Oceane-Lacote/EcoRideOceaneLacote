<?php
namespace App;

use App\Database;
use App\Security;
use Symfony\Component\Routing\Attribute\Route;

class UserController {

    private $pdo;

    public function __construct() {
        $db = new Database();
        $this->pdo = $db->connect();
    }
    #[Route('/signin', name: 'signin', methods: ['GET'])]
    public function login($email, $password) {
        // Sécuriser l'email
        $email = Security::sanitizeEmail($email);

        // Vérifier si l'email existe dans la base de données
        $sql = "SELECT * FROM utilisateur WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['mot_de_passe'])) {
            // Si le mot de passe est correct
            echo "Connexion réussie !";
        } else {
            echo "Identifiants incorrects.";
        }
    }

    public function register($email, $password) {
        // Sécuriser l'email
        $email = Security::sanitizeEmail($email);

        // Vérifier que le mot de passe est valide
        if (!Security::validatePassword($password)) {
            echo "Le mot de passe doit contenir au moins 8 caractères.";
            return;
        }

        // Hacher le mot de passe
        $hashedPassword = Security::hashPassword($password);

        // Insérer l'utilisateur dans la base de données
        $sql = "INSERT INTO utilisateur (email, mot_de_passe) VALUES (:email, :mot_de_passe)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':mot_de_passe', $hashedPassword);
        $stmt->execute();

        echo "Utilisateur enregistré avec succès.";
    }
}
?>