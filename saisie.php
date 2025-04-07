<?php
session_start();

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: connexion.php');
    exit();
}

$method = isset($_POST['method']) ? $_POST['method'] : null;

if (!$method) {
    die("Méthode de paiement non spécifiée.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails de Paiement</title>
    <link rel="stylesheet" href="payement.css">
</head>
<body>
    <div class="container">
        <h1>Détails de Paiement</h1>
        <form action="process_payment.php" method="POST">
            <input type="hidden" name="method" value="<?php echo htmlspecialchars($method); ?>">

            <?php if ($method === 'tmoney'): ?>
                <label for="tmoney_phone">Numéro Tmoney :</label>
                <input type="text" id="tmoney_phone" name="tmoney_phone" required>
                <label for="tmoney_password">Code Tmoney :</label>
                <input type="password" id="tmoney_password" name="tmoney_password" required>
            <?php elseif ($method === 'flooz'): ?>
                <label for="flooz_number">Numéro Flooz :</label>
                <input type="text" id="flooz_number" name="flooz_number" required>
                <label for="flooz_code">Code Flooz :</label>
                <input type="password" id="flooz_code" name="flooz_code" required>
            <?php elseif ($method === 'ecobank'): ?>
                <label for="ecobank_account">Numéro de compte Ecobank :</label>
                <input type="text" id="ecobank_account" name="ecobank_account" required>
                <label for="ecobank_password">Mot de passe Ecobank :</label>
                <input type="password" id="ecobank_password" name="ecobank_password" required>
            <?php endif; ?>

            <button type="submit">Payer</button>
        </form>
    </div>
</body>
</html>