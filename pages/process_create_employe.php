<?php
require "../auth.php";  

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars(trim($_POST['employee_name']));
    $email = filter_input(INPUT_POST, 'employee_email', FILTER_VALIDATE_EMAIL);
    $password = $_POST['employee_password'];

    if ($name && $email && $password) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        try {
            $stmt = $PDO->prepare("INSERT INTO utilisateur (pseudo, email, motdepasse, role_meta, statut) 
                                   VALUES (:pseudo, :email, :motdepasse, 'employe', 'actif')");
            $stmt->execute([
                ':pseudo' => $name,
                ':email' => $email,
                ':motdepasse' => $hashedPassword
            ]);

            header("Location: /admin.php"); 
            exit;

        } catch (PDOException $e) {
            die("Erreur lors de la création de l'employé : " . $e->getMessage());
        }
    } else {
        echo "Veuillez remplir tous les champs correctement.";
    }
}
?>
