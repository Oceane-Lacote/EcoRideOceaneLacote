<?php
namespace App;

class Security {
    // Méthode pour sécuriser un email
    public static function sanitizeEmail($email) {
        return filter_var($email, FILTER_SANITIZE_EMAIL);
    }

    // Méthode pour valider un mot de passe
    public static function validatePassword($password): string {
        return strlen($password) >= 8; // Exemple de validation basique
    }

    // Méthode pour hacher un mot de passe
    public static function hashPassword($password): string {
        return password_hash($password, PASSWORD_BCRYPT);
    }
}
?>