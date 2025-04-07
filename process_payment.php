<?php
session_start();

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: connexion.php');
    exit();
}

$message = ''; // Initialiser le message

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $method = $_POST['method'] ?? null; // Méthode de paiement

    if (!$method) {
        die("Méthode de paiement non spécifiée.");
    }

    // Validation en fonction de la méthode
    switch ($method) {
        case 'tmoney':
            $phone = $_POST['tmoney_phone'] ?? null;
            // Vérifier que le numéro a 8 chiffres et commence par 90 à 93
            if (preg_match('/^(90|91|92|93)\d{6}$/', $phone)) {
                $isValid = true;
            } else {
                $message = "Numéro de téléphone Tmoney invalide. Il doit comporter 8 chiffres et commencer par 90, 91, 92 ou 93.";
            }
            break;
        case 'flooz':
            $phone = $_POST['flooz_number'] ?? null;
            // Vérifier que le numéro a 8 chiffres et commence par 95 à 99
            if (preg_match('/^(95|96|97|98|99)\d{6}$/', $phone)) {
                $isValid = true;
            } else {
                $message = "Numéro de téléphone Flooz invalide. Il doit comporter 8 chiffres et commencer par 95, 96, 97, 98 ou 99.";
            }
            break;
        case 'ecobank':
            $accountNumber = $_POST['ecobank_account'] ?? null;
            if (preg_match('/^\d{14}$/', $accountNumber)) {
                $isValid = true;
            } else {
                $message = "Numéro de compte Ecobank invalide.";
            }
            break;
        default:
            $message = "Moyen de paiement non pris en charge.";
            break;
    }

    if (isset($isValid) && $isValid) {
        // Simule le traitement du paiement
        $_SESSION['payment_message'] = "Paiement réussi avec le mode $method.";
        
        // Affichage du loader
        echo '<!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Chargement...</title>
            <style>
                body {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    margin: 0;
                }
                #loader {
                    text-align: center;
                }
            </style>
        </head>
        <body>
            <div id="loader">
                <img src="loader.gif" alt="Chargement...">
                <p>Veuillez patienter, nous traitons votre paiement...</p>
            </div>
        </body>
        </html>';
        
        // Redirection après 2 secondes
        sleep(2);
        header('Location: result.php'); // Redirection vers la page de résultat
        exit();
    } else {
        $_SESSION['payment_message'] = $message; // Enregistrer l'erreur
        header('Location: payement.php'); // Retourner à la page de choix
        exit();
    }
}
?>