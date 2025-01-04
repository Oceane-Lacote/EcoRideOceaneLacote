<?php 
session_start();
ob_start();
switch($_SERVER["REQUEST_URI"]){
    case "/":
        require"../pages/home.html";
        break;
    case "/profil":
            require "../auth.php";
        
            if (!isset($_SESSION['utilisateur_id'])) {
                echo '<div class="error-message">Veuillez vous connecter pour accèder à cette fonctionnalité!</div>';;
                exit();
            }
        
            try {
                $stmt = $PDO->prepare("SELECT nom, prenom, pseudo, email, role_meta FROM utilisateur WHERE utilisateur_id = :id");
                $stmt->execute([':id' => $_SESSION['utilisateur_id']]);
                $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);
        
                if (!$utilisateur) {
                    die("Erreur : Utilisateur non trouvé.");
                }
        
                if ($_SERVER["REQUEST_METHOD"] === "POST") {
                    $nom = htmlspecialchars($_POST['nom']); 
                    $prenom = htmlspecialchars($_POST['prenom']);
                    $pseudo = htmlspecialchars($_POST['pseudo']);
                    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL); 
                    $motdepasse = $_POST['motdepasse']; 
                    $roles = isset($_POST['roles']) ? implode(', ', $_POST['roles']) : '';
        
                    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                        $photoTmpPath = $_FILES['photo']['tmp_name'];
                        $photoName = uniqid() . "_" . basename($_FILES['photo']['name']); 
                        $photoUploadPath = "../uploads/" . $photoName;
        
                        if (!move_uploaded_file($photoTmpPath, $photoUploadPath)) {
                            die("Erreur lors de l'upload de la photo.");
                        }
                    }
        
                    $hashedPassword = null;
                    if (!empty($motdepasse)) {
                        $hashedPassword = password_hash($motdepasse, PASSWORD_DEFAULT);
                    }
        
                    $query = "UPDATE utilisateur 
                              SET nom = :nom, prenom = :prenom, pseudo = :pseudo, email = :email, roles= :roles";
                    if ($hashedPassword) {
                        $query .= ", motdepasse = :motdepasse"; 
                    }
                    $query .= " WHERE utilisateur_id = :id";
        
                   
                    $params = [
                        ':nom' => $nom,
                        ':prenom' => $prenom,
                        ':pseudo' => $pseudo,
                        ':email' => $email,
                        ':roles' => $roles,
                        ':id' => $_SESSION['utilisateur_id']
                    ];
        
                    if ($hashedPassword) {
                        $params[':motdepasse'] = $hashedPassword;
                    }
        
                    $stmt = $PDO->prepare($query);
                    $stmt->execute($params);
        
                    header('Location: /profil.php');
                    exit();
                }
            } catch (PDOException $e) {
                die("Erreur de base de données : " . $e->getMessage());
            }
        
            require "../pages/profil.php";
    break;
        
    case "/signup":
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            require "../auth.php";
    
            $nom = filter_input(INPUT_POST, "nom", FILTER_SANITIZE_STRING);
            $prenom = filter_input(INPUT_POST, "prenom", FILTER_SANITIZE_STRING);
            $pseudo = filter_input(INPUT_POST, "pseudo", FILTER_SANITIZE_STRING);
            $motdepasse = filter_input(INPUT_POST, "signin-motdepasse", FILTER_DEFAULT); 
            $email = filter_input(INPUT_POST, "signin-email", FILTER_VALIDATE_EMAIL);
    
            $image_profil = 'default_profil.jpg';
    
            if (!$nom || !$prenom || !$pseudo || !$motdepasse || !$email) {
                die("Veuillez remplir tous les champs correctement.");
            }
    
            try {
                $emailCheck = $PDO->prepare("SELECT COUNT(*) FROM utilisateur WHERE email = :email");
                $emailCheck->execute([":email" => $email]);
                $emailExists = $emailCheck->fetchColumn();
    
                $pseudoCheck = $PDO->prepare("SELECT COUNT(*) FROM utilisateur WHERE pseudo = :pseudo");
                $pseudoCheck->execute([":pseudo" => $pseudo]);
                $pseudoExists = $pseudoCheck->fetchColumn();
    
                if ($emailExists) {
                    echo '<div class="error-message">L\'email existe déjà. Veuillez en choisir un autre.</div>';
                    exit; 
                }
    
                if ($pseudoExists) {
                    echo '<div class="error-message">Le pseudo existe déjà. Veuillez en choisir un autre.</div>';
                    exit; 
                }
    
                $hashedPassword = password_hash($motdepasse, PASSWORD_DEFAULT);
    
                $utilisateur = $PDO->prepare("INSERT INTO utilisateur (nom, prenom, pseudo, motdepasse, email, image_profil) VALUES (:nom, :prenom, :pseudo, :motdepasse, :email, :image_profil)");
                $resultat = $utilisateur->execute([
                    ":nom" => $nom,
                    ":prenom" => $prenom,
                    ":pseudo" => $pseudo,
                    ":motdepasse" => $hashedPassword,
                    ":email" => $email,
                    ":image_profil" => $image_profil
                ]);
            echo "Inscription réussie !";
            } catch (PDOException $e) {
                echo "Erreur SQL : " . $e->getMessage();  // Affiche l'erreur SQL exacte
                error_log("Erreur PDO : " . $e->getMessage()); // Logue l'erreur dans les logs
                die("Une erreur est survenue lors de l'attribution du rôle. Veuillez réessayer plus tard.");
            }
        }
        require "../pages/signup.html";  
        break;
        
        
        case "/login":
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                require "../auth.php";
                
                $email = filter_input(INPUT_POST, "login-email", FILTER_VALIDATE_EMAIL);
                $loginmotdepasse = $_POST["login-motdepasse"] ?? null;
        
                if (!$email) {
                    die("Email invalide ou manquant.");
                }
                if (!$loginmotdepasse) {
                    die("Mot de passe manquant.");
                }
        
                try {
                
                    $stmt = $PDO->prepare("SELECT utilisateur_id, motdepasse, role_meta FROM utilisateur WHERE email = :email");
                    $stmt->execute([":email" => $email]);
                    $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);
        
                    if ($utilisateur) {
                        if (password_verify($loginmotdepasse, $utilisateur["motdepasse"])) {
                            session_start();
                            $_SESSION["utilisateur_id"] = $utilisateur["utilisateur_id"];
                            $_SESSION["email"] = $email;
                            $_SESSION["role_meta"] = $utilisateur["role_meta"]; 
        
                            
                            switch ($utilisateur["role_meta"]) {
                                case 'administrateur':
                                    header('Location: /admin');
                                    break;
                                case 'employe':
                                    header('Location: /employe');
                                    break;
                                case 'utilisateur':
                                    header('Location: /profil');
                                    break;
                                default:
                                    echo "Rôle inconnu.";
                                    break;
                            }
                            exit();
                        }
                    } else {
                        echo "Aucun utilisateur trouvé avec cet email.";
                    }
                } catch (PDOException $e) {
                    die("Erreur PDO : " . $e->getMessage());
                }
            }
            require "../pages/signup.html";
            break;
        
    
    case "/recherche":
        require"../pages/recherche.html";
        break;
    case "/covoiturage":
        require"../pages/covoiturage.html";
        break;
    case "/resultat":
        require"../pages/resultat.html";
        break;

    case "/contact":
        require"../pages/contact.html";
        break;

        

        case "/employe":
            if (!isset($_SESSION['utilisateur_id'])) {
                header('Location: /login'); 
                exit();
            }
        
            $utilisateur_id = $_SESSION['utilisateur_id'];
        
            require "../auth.php";
        
            // Le bloc try avec catch
            try {
                $stmt = $PDO->prepare("SELECT role_meta FROM utilisateur WHERE utilisateur_id = :id");
                $stmt->execute([':id' => $utilisateur_id]);
                $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);
        
                if (!$utilisateur) {
                    die("Utilisateur introuvable.");
                }
        
                if ($utilisateur['role_meta'] !== 'employe') {
                    die("Accès refusé. Vous n'avez pas les droits nécessaires.");
                }
        
                // Si l'utilisateur est valide et a le bon rôle
                require "../pages/employe.php";  // Chargement de la page employe.php
            } catch (Exception $e) {
                // Gestion des erreurs si une exception est lancée
                echo "Erreur: " . $e->getMessage();
                exit(); // Sortie du script en cas d'erreur
            }
            break;
        

            case "/admin":
                require "../auth.php"; // Inclure l'authentification
            
                // Vérification des droits d'accès (administrateur uniquement)
                if (!isset($_SESSION['role_meta']) || $_SESSION['role_meta'] !== 'administrateur') {
                    die("Accès refusé.");
                }
            
                // Charger la page admin
                require "../pages/admin.php";
            
                // Gestion de la création des employés
                if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["create_employe"])) {
                    $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
                    $password = $_POST["password"] ?? null;
            
                    if (!$email || !$password) {
                        die("Email ou mot de passe invalide.");
                    }
            
                    // Hash du mot de passe avant insertion
                    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            
                    try {
                        $stmt = $PDO->prepare("INSERT INTO utilisateur (email, motdepasse, role_meta, statut) VALUES (:email, :motdepasse, 'employe', 'actif')");
                        $stmt->execute([
                            ':email' => $email,
                            ':motdepasse' => $hashedPassword,
                        ]);
            
                        echo "Compte employé créé avec succès.";
                    } catch (PDOException $e) {
                        die("Erreur lors de la création : " . $e->getMessage());
                    }
                }
            
                // Gestion du changement de statut (actif ou suspendu)
                if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["change_status"])) {
                    $userId = $_POST["user_id"] ?? null;
                    $newStatus = $_POST["new_status"] ?? null;
            
                    if (!$userId || !in_array($newStatus, ['actif', 'suspendu'])) {
                        die("Données invalides.");
                    }
            
                    try {
                        $stmt = $PDO->prepare("UPDATE utilisateur SET statut = :statut WHERE utilisateur_id = :id");
                        $stmt->execute([
                            ':statut' => $newStatus,
                            ':id' => $userId,
                        ]);
            
                        echo "Statut mis à jour avec succès.";
                    } catch (PDOException $e) {
                        die("Erreur lors de la mise à jour : " . $e->getMessage());
                    }
                }
            
                break;
 }            

$content = ob_get_clean();

require"home.php";