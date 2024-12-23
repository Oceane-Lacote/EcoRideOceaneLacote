<?php 

ob_start();
switch($_SERVER["REQUEST_URI"]){
    case "/":
        require"../pages/home.html";
        break;
    case "/profil":
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            require "../auth.php";
            
            $nom = htmlspecialchars($_POST['nom']); 
            $prenom = htmlspecialchars($_POST['prenom']);
            $pseudo = htmlspecialchars($_POST['pseudo']);
            $email = htmlspecialchars($_POST['email']);
            $motdepasse = htmlspecialchars($_POST['motdepasse']); 
            $roles = isset($_POST['role']) ? implode(', ', $_POST['role']) : ''; 
        
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                $photoTmpPath = $_FILES['photo']['tmp_name'];
                $photoName = basename($_FILES['photo']['name']);
                $photoUploadPath = "../uploads/" . $photoName;
        
                move_uploaded_file($photoTmpPath, $photoUploadPath);
            }
        
            $user_id = $_SESSION['user_id'];
        
            $query = "UPDATE utilisateur
                      SET nom = ?, prenom = ?, pseudo = ?, email = ?, motdepasse = ?, roles = ? 
                      WHERE id = ?";
            $stmt = $db->prepare($query);
        
            $hashedPassword = password_hash($motdepasse, PASSWORD_DEFAULT);
        
            $stmt->execute([$nom, $prenom, $pseudo, $email, $hashedPassword, $roles, $user_id]);
        
            header('Location: ../pages/profil.php?success=1');
            exit();
        }
        require"../pages/profil.html";
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
                // Vérification de l'existence de l'email
                $emailCheck = $PDO->prepare("SELECT COUNT(*) FROM utilisateur WHERE email = :email");
                $emailCheck->execute([":email" => $email]);
                $emailExists = $emailCheck->fetchColumn();
    
                // Vérification de l'existence du pseudo
                $pseudoCheck = $PDO->prepare("SELECT COUNT(*) FROM utilisateur WHERE pseudo = :pseudo");
                $pseudoCheck->execute([":pseudo" => $pseudo]);
                $pseudoExists = $pseudoCheck->fetchColumn();
    
                // Vérification que l'email et le pseudo n'existent pas déjà
                if ($emailExists) {
                    echo '<div class="error-message">L\'email existe déjà. Veuillez en choisir un autre.</div>';
                    exit; 
                }
    
                if ($pseudoExists) {
                    echo '<div class="error-message">Le pseudo existe déjà. Veuillez en choisir un autre.</div>';
                    exit; 
                }
    
                // Hachage du mot de passe
                $hashedPassword = password_hash($motdepasse, PASSWORD_DEFAULT);
    
                // Insertion de l'utilisateur dans la table utilisateur
                $utilisateur = $PDO->prepare("INSERT INTO utilisateur (nom, prenom, pseudo, motdepasse, email, image_profil) VALUES (:nom, :prenom, :pseudo, :motdepasse, :email, :image_profil)");
                $resultat = $utilisateur->execute([
                    ":nom" => $nom,
                    ":prenom" => $prenom,
                    ":pseudo" => $pseudo,
                    ":motdepasse" => $hashedPassword,
                    ":email" => $email,
                    ":image_profil" => $image_profil
                ]);
    
                if ($resultat) {
                    $utilisateurId = $PDO->lastInsertId();
                    $roleQuery = $PDO->prepare("SELECT role_meta_id FROM role_meta WHERE nom = 'utilisateur'");
                    $roleQuery->execute();
                    $role = $roleQuery->fetch();
    
                    if ($role) {
                        var_dump($role); 
                        $roleInsert = $PDO->prepare("INSERT INTO utilisateur_role_meta (utilisateur_id, role_meta_id) VALUES (:utilisateur_id, :role_meta_id)");
                        $roleInsert->execute([
                            ":utilisateur_id" => $utilisateurId,
                            ":role_meta_id" => $role['role_meta_id'],
                        ]);
                        echo "Inscription réussie !";
                    } else {
                        echo "Une erreur est survenue lors de l'attribution du rôle.";
                    }
                } else {
                    echo "Une erreur est survenue. Veuillez réessayer.";
                }
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
        
            // Récupérer et valider les champs
            $email = filter_input(INPUT_POST, "login-email", FILTER_VALIDATE_EMAIL);
            $loginmotdepasse = $_POST["login-motdepasse"] ?? null;
        
            // Vérifier les champs
            if (!$email) {
                die("Email invalide ou manquant.");
            }
            if (!$loginmotdepasse) {
                die("Mot de passe manquant.");
            }
        
            try {
                // Requête pour récupérer l'utilisateur
                $stmt = $PDO->prepare("SELECT utilisateur_id, motdepasse FROM utilisateur WHERE email = :email");
                $stmt->execute([":email" => $email]);
                $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);
        
                if ($utilisateur) {
                    echo password_hash("mdpCarole1!", PASSWORD_BCRYPT);
        
                    if (password_verify($loginmotdepasse, $utilisateur["motdepasse"])) {
                        session_start();
                        $_SESSION["utilisateur_id"] = $utilisateur["utilisateur_id"];
                        $_SESSION["email"] = $email;
                        header("Location: /profil");
                        exit();
                    } else {
                        echo "Mot de passe incorrect.";
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
    }

$content = ob_get_clean();

require"home.php";