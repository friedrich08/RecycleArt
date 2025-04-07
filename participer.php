<?php
session_start();

// Traitement du formulaire de contact
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['contact_submit'])) {
    // Récupérer les données du formulaire
    $name = htmlspecialchars($_POST['name']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $message = htmlspecialchars($_POST['message']);

    // Formater le message pour WhatsApp
    $formattedMessage = urlencode("Nom: $name $prenom\nMessage: $message");

    // Rediriger vers WhatsApp avec le message formaté
    $whatsappUrl = "https://wa.me/22896392020?text=$formattedMessage";
    header("Location: $whatsappUrl");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Je veux participer</title>
    <style>
        /* Styles globaux */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f7e6;
            margin: 0;
            padding: 0;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh; /* Utilise toute la hauteur de la fenêtre */
            overflow: hidden; /* Empêche le défilement global */
        }
        .container {
            max-width: 800px;
            width: 90%;
            margin: 0 auto;
            padding: 20px;
            box-sizing: border-box;
            overflow-y: auto; /* Scroll interne si nécessaire */
            max-height: 90vh; /* Limite la hauteur pour éviter le débordement */
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        header {
            background-color: #4CAF50;
            color: white;
            text-align: center;
            padding: 10px;
            border-radius: 5px 5px 0 0;
        }
        h1 {
            margin: 0;
            font-size: 24px; /* Taille réduite pour mieux s'adapter */
        }
        .content {
            padding: 15px; /* Réduction des paddings */
        }
        form {
            margin-top: 10px;
        }
        .form-group {
            margin-bottom: 10px; /* Espacement réduit */
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input, select {
            width: 100%;
            padding: 8px; /* Réduction des paddings */
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px; /* Taille de police réduite */
        }
        button {
            background-color: lightgreen;
            color: #333;
            border: none;
            padding: 10px 15px; /* Réduction des paddings */
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            width: 100%; /* Bouton plein largeur pour économiser de l'espace */
        }
        button:hover {
            background-color: white;
        }
        .contact-link {
            display: block;
            margin-top: 15px;
            text-align: center;
            background-color: #4CAF50;
            color: black;
            padding: 8px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
        }
        .contact-link:hover {
            background-color: black;
        }

        /* Styles pour la navbar */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: white;
            padding: 10px 20px;
            border-radius: 5px 5px 0 0;
        }
        .navbar-brand img {
            height: 40px;
        }
        .navbar-nav {
            list-style: none;
            display: flex;
            gap: 15px;
            margin: 0;
            padding: 0;
        }
        .navbar-nav .nav-item {
            display: inline-block;
        }
        .navbar-nav .nav-link {
            color: black;
            text-decoration: none;
            font-size: 14px;
        }
        .navbar-nav .nav-link:hover {
            text-decoration: underline;
        }
        .btn-orange {
            background-color: orange;
            color: black;
            padding: 5px 10px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
        }
        
        
       
    </style>
</head>
<body>
    <div class="container">
        <!-- Navbar -->
        <nav class="navbar">
            <a href="accueil.php" class="navbar-brand">
                <img src="logo.jpg" alt="RecycleArt" class="logo1">
            </a>
            <ul class="navbar-nav">
                <li class="nav-item"><a href="accueil.php" class="nav-link">Accueil</a></li>
                <li class="nav-item"><a href="#about" class="nav-link">À propos</a></li>
                <li class="nav-item"><a href="#products" class="nav-link">Articles</a></li>
                <li class="nav-item"><a href="#contact" class="nav-link">Contact</a></li>
                <li class="nav-item"><a href="participer.php" class="nav-link">Je veux participer 🙋‍♂️</a></li>
                <li class="nav-item"><a href="panier.php" class="btn-orange">Panier 🛒</a></li>
            </ul>
        </nav>

        <header>
            <h1>Faire un don!</h1>
        </header>
        <div class="content">
            <section>
                <h2 style="font-size: 18px;">À propos</h2>
                <p style="font-size: 14px; line-height: 1.4;">
                    Bienvenue sur notre plateforme de participation qui vous permet de faire des dons d'habits usés ou en piteux état ! Nous sommes ravis de votre intérêt pour notre événement/produit. Veuillez remplir le formulaire ci-dessous pour vous inscrire.
                </p>
            </section>
            
            <form id="participationForm">
                <div class="form-group">
                    <label for="nom">Nom:</label>
                    <input type="text" id="nom" name="nom" required>
                </div>
                
                <div class="form-group">
                    <label for="prenom">Prénom:</label>
                    <input type="text" id="prenom" name="prenom" required>
                </div>
                
                <div class="form-group">
                    <label for="age">Âge:</label>
                    <input type="number" id="age" name="age" required min="18" max="120">
                </div>
                
                <div class="form-group">
                    <label for="telephone">Numéro de téléphone:</label>
                    <input type="tel" id="telephone" name="telephone" required>
                </div>
                
                <div class="form-group">
                    <label for="quantite">Quelle quantité:</label>
                    <input type="number" id="quantite" name="quantite" required min="1">
                </div>
                
                <button type="submit">Soumettre ma participation</button>
            </form>
            
            <a href="mailto:contact@exemple.com" class="contact-link">Nous contacter directement</a>
        </div>
    </div>

    <script>
        document.getElementById('participationForm').addEventListener('submit', function(event) {
            event.preventDefault();
            alert('Merci pour votre participation ! Nous vous contacterons bientôt.');
        });
    </script>
</body>
</html>