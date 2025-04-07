<?php
session_start();  // Démarrer la session pour gérer l'authentification
require 'bdd.php';
// Créer une instance de la classe Database
$database = new Database();
$conn = $database->getConnection(); // Obtenir la connexion

//if ($conn) {
   // echo "Connected successfully";
//} else {
   // echo "Connection failed";
//}

// Partie Inscription
if (isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['email']) && isset($_POST['password'])) {
    $nom = $conn->real_escape_string($_POST['nom']);
    $prenom = $conn->real_escape_string($_POST['prenom']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT);  // Hachage du mot de passe


    // Vérification si l'email existe déjà
    $checkQuery = "SELECT * FROM client WHERE email='$email'";
    $result = $conn->query($checkQuery);

    if ($result->num_rows > 0) {
        echo "<script>alert('Cet email est déjà utilisé.');</script>";
    } else {
        $sql = "INSERT INTO client (nom, prenom, email, password) VALUES ('$nom', '$prenom', '$email', '$password')";
        if ($conn->query($sql) === TRUE) {
            // Inscription réussie, démarrage de session automatique
            $_SESSION['client'] = $email; // Stocke l'email dans la session
            header("Location: accueil.php"); // Redirige vers le tableau de bord
            exit();
        } else {
            echo "<script>alert('Erreur : " . $conn->error . "');</script>";
        }
    }

}

// Partie Connexion
if (isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['email']) && isset($_POST['password'])) {
    $nom = $conn->real_escape_string($_POST['nom']);
    $prenom = $conn->real_escape_string($_POST['prenom']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT);

    $checkQuery = "SELECT * FROM client WHERE email='$email'";
    $result = $conn->query($checkQuery);
    if ($result->num_rows > 0) {
        echo "<script>alert('Cet email est déjà utilisé.');</script>";
    } else {
        $sql = "INSERT INTO client (nom, prenom, email, password) VALUES ('$nom', '$prenom', '$email', '$password')";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['client'] = $email;
            $_SESSION['logged_in'] = true;
            header("Location: accueil.php");
            exit();
        } else {
            echo "<script>alert('Erreur : " . $conn->error . "');</script>";
        }
    }
}

if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = $conn->real_escape_string($_POST['email']);
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM client WHERE email='$email'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['client'] = $email;
            $_SESSION['logged_in'] = true;
            header("Location: accueil.php");
            exit();
        } else {
            echo "<script>alert('Mot de passe incorrect.');</script>";
        }
    } else {
        echo "<script>alert('Aucun utilisateur trouvé avec cet email.');</script>";
    }
}


?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaires Inscription & Connexion</title>
    <style>
        /* Global Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #f8f9fa; /* Gris clair pour le fond */
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #ffffff; /* Fond blanc pour le formulaire */
            border-radius: 8px; /* Coins arrondis */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Ombre douce */
            width: 360px;
            overflow: hidden;
        }

        /* Toggle Logic */
        #toggle {
            display: none;
        }

        .form-wrapper {
            display: flex;
            width: 200%;
            transition: transform 0.6s ease;
        }

        #toggle:checked ~ .form-wrapper {
            transform: translateX(-50%);
        }

        form {
            width: 50%;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        h2 {
            color: green; /* Orange vif pour le titre */
            font-weight: bold;
            font-size: 24px; /* Taille du titre */
            margin-bottom: 20px;
            text-align: center;
        }

        label {
            font-size: 16px;
            margin-bottom: 8px;
            color: #333; /* Gris foncé pour les labels */
        }

        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc; /* Bordure légère */
            border-radius: 5px;
            font-size: 16px;
            background-color: #f8f9fa; /* Fond gris clair pour les champs */
        }

        input:focus {
            border-color: green; /* Bordure orange lors du focus */
            outline: none;
        }

        button {
            background-color: green; /* Orange vif */
            color: #fff; /* Blanc pour le texte */
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color:green; /* Orange foncé au survol */
        }

        .switch {
            text-align: center;
            margin-top: 10px;
        }

        .switch label {
            font-size: 0.9rem;
            color: #333; /* Gris foncé pour les labels */
            cursor: pointer;
        }

        .switch label:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <input type="checkbox" id="toggle">

        <div class="form-wrapper">
            <!-- Formulaire d'inscription -->
            <form action="" method="POST">
                <h2>Inscription</h2>
                <label for="nom">Nom :</label>
                <input type="text" id="nom" name="nom" required>

                <label for="prenom">Prénom :</label>
                <input type="text" id="prenom" name="prenom" required>

                <label for="email">Email :</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required>

                <button type="submit">S'inscrire</button>
                <div class="switch">
                    <label for="toggle">Déja enregistré ? Connectez vous!</label>
                </div>
            </form>

            <!-- Formulaire de connexion -->
            <form action="" method="POST">
                <h2>Connexion</h2>
                <label for="login-email">Email :</label>
                <input type="email" id="login-email" name="email" required>

                <label for="login-password">Mot de passe :</label>
                <input type="password" id="login-password" name="password" required>

                <button type="submit">Se connecter</button>
                <div class="switch">
                    <label for="toggle">Pas de compte ? Enregistrez vous!</label>
                </div>
            </form>
        </div>
    </div>
</body>
</html>