<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Démarrer la session seulement si elle n'est pas déjà active
}
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: connexion.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Choix du Moyen de Paiement</title>
    <link rel="stylesheet" href="payement.css">
</head>
<body>
    <div class="container">
        <h1>Choisissez votre moyen de paiement</h1>
        <form action="saisie.php" method="POST">
            <label>
                <input type="radio" name="method" value="tmoney" required> Tmoney
            </label><br>
            <label>
                <input type="radio" name="method" value="flooz"> Flooz
            </label><br>
            <label>
                <input type="radio" name="method" value="ecobank"> Ecobank
            </label><br>
            <button type="submit">Continuer</button>
        </form>
    </div>
</body>
</html>