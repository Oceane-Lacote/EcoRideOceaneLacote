<?php 
$request = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';

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
        
                    exit();
                }
            } catch (PDOException $e) {
                die("Erreur de base de données : " . $e->getMessage());
            }

        
            require "../pages/profil.php";
    break;

    case "/vehicule":

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $marque   = htmlspecialchars($_POST['marque']); 
            $modele = htmlspecialchars($_POST['modele']);
            $couleur = htmlspecialchars($_POST['couleur']);
            $energie = filter_input(INPUT_POST, 'energie', FILTER_VALIDATE_EMAIL); 
            $nb_place = htmlspecialchars($_POST['nb_place']);
            $vehicule_date = htmlspecialchars($_POST['vehicule_date']); 
            $vehicule_plate = htmlspecialchars($_POST['vehicule_plate']); 
        }
            $query = "UPDATE vehicule 
            SET marque = :marque, modele = :modele, couleur = :couleur, energie = :energie, nb_place = :nb_place, vehicule_date = :vehicule_date, vehicule_plate = :vehicule_plate";
            $query .= " WHERE utilisateur_id = :id";

 
     $params = [
      ':marque' => $marque,
      ':modele' => $modele,
      ':couleur' => $couleur,
      ':energie' => $energie,
      ':nb_place' => $nb_place,
      ':vehicule_plate' => $vehicule_plate,
      ':vehicule_date' => $vehicule_date,
      ':id' => $_SESSION['utilisateur_id']
     ];

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

    
                $utilisateur = $PDO->prepare("INSERT INTO utilisateur (nom, prenom, pseudo, motdepasse, email) VALUES (:nom, :prenom, :pseudo, :motdepasse, :email)");
                $resultat = $utilisateur->execute([
                    ":nom" => $nom,
                    ":prenom" => $prenom,
                    ":pseudo" => $pseudo,
                    ":motdepasse" => $hashedPassword,
                    ":email" => $email,
                ]);

                $stmt_credits = $PDO->prepare("INSERT INTO credits (utilisateur_id, total_credits) VALUES (:utilisateur_id, 20)");
                $stmt_credits->execute([':utilisateur_id' => $utilisateur_id]);
    
                $PDO->commit();

            echo "Inscription réussie !";
            } catch (PDOException $e) {
                echo "Erreur SQL : " . $e->getMessage();
                error_log("Erreur PDO : " . $e->getMessage()); 
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
        
                require "../pages/employe.php";
                require "../pages/process_avis.php"; 
            } catch (Exception $e) {
               
                echo "Erreur: " . $e->getMessage();
                exit(); 
            }
        break;
        

        case "/admin":
                require "../auth.php"; 
            
                if (!isset($_SESSION['role_meta']) || $_SESSION['role_meta'] !== 'administrateur') {
                    die("Accès refusé.");
                }
            
                require "../pages/admin.php";
                require "../pages/process_create_employe.php";
                require "../pages/process_statut.php";
            
                if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["create_employe"])) {
                    $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
                    $password = $_POST["password"] ?? null;
            
                    if (!$email || !$password) {
                        die("Email ou mot de passe invalide.");
                    }
            
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

        case "/resultat":
    if (isset($_GET['depart']) && isset($_GET['arrivee']) && isset($_GET['date'])) {
        $depart = $_GET['depart'];
        $arrivee = $_GET['arrivee'];
        $date = $_GET['date'];
        require "../Pages/resultat.php";
    } else {
        echo "Erreur : paramètres manquants ! Veuillez vérifier votre recherche.";
    }
    break;



 }            

$content = ob_get_clean();

require"home.php";