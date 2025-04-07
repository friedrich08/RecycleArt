<?php
session_start();

$message = isset($_SESSION['payment_message']) ? $_SESSION['payment_message'] : '';
$panier = $_SESSION['panier'] ?? []; // Supposons que le panier soit stocké dans la session
$total = 0;
$modeDePaiement = isset($_SESSION['mode_de_paiement']) ? $_SESSION['mode_de_paiement'] : 'Non spécifié';
unset($_SESSION['payment_message']); // Nettoyer le message
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultat du Paiement</title>
    <link rel="stylesheet" href="panier.css"> <!-- Utilisez le même fichier CSS que la page du panier -->
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="#" class="navbar-brand">
                <img src="logoshop.png" alt="MyShop" class="logo">
                MyShop
            </a>
            <ul class="navbar-nav">
                <li class="nav-item"><a href="accueil.php" class="nav-link">Accueil</a></li>
                <li class="nav-item"><a href="connexion.php" class="btn-orange">S'inscrire</a></li>
                <li class="nav-item"><a href="panier.php" class="btn-orange">Panier 🛒</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h1>Mode du Paiement</h1>
        <p><?php echo htmlspecialchars($message); ?></p>


        <?php if (!empty($panier)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Prix</th>
                        <th>Quantité</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($panier as $id => $item): 
                        $quantity = isset($item['quantity']) ? (int)$item['quantity'] : 1; // Cast to integer
                        $itemTotal = (float)$item['price'] * $quantity; // Cast price to float
                        $total += $itemTotal;
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td><?php echo htmlspecialchars($item['price']); ?> FCFA</td>
                            <td><?php echo htmlspecialchars($quantity); ?></td>
                            <td><?php echo htmlspecialchars($itemTotal); ?> FCFA</td>
                        </tr>
                    <?php endforeach; ?>
                    <tr class="total-row">
                        <td colspan="3"><strong>Total</strong></td>
                        <td><strong><?php echo htmlspecialchars($total); ?> FCFA</strong></td>
                    </tr>
                </tbody>
            </table>
        <?php else: ?>
            <p class="empty-cart">Aucun article n'a été payé.</p>
        <?php endif; ?>

        <form method="POST">
            <button type="submit" name="generate_csv" class="pay-btn">Télécharger en CSV</button>
            <button type="submit" name="generate_pdf" class="pay-btn">Télécharger en PDF</button>
        </form>
        
        <a href="accueil.php" class="btn-orange">Retour à l'accueil</a>
    </div>
</body>
</html>

<?php
// Générer le fichier CSV
if (isset($_POST['generate_csv'])) {
    $filename = 'ticket_paiement.csv';
    
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Mode de Paiement', $modeDePaiement]); // Ajouter le mode de paiement
    fputcsv($output, []); // Ligne vide pour espacer
    fputcsv($output, ['Produit', 'Prix', 'Quantité', 'Total']);
    
    foreach ($panier as $item) {
        $quantity = isset($item['quantity']) ? (int)$item['quantity'] : 1; // Cast to integer
        $itemTotal = (float)$item['price'] * $quantity; // Cast price to float
        fputcsv($output, [htmlspecialchars($item['name']), htmlspecialchars($item['price']), $quantity, $itemTotal]);
    }
    
    fputcsv($output, ['Total', '', '', $total]);
    fclose($output);
    exit();
}

// Générer le fichier PDF simple
if (isset($_POST['generate_pdf'])) {
    // En-têtes pour le téléchargement du PDF
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="ticket_paiement.pdf"');
    
    // Contenu du PDF (HTML basique)
    $htmlContent = '<html>
    <head>
        <title>Ticket de Paiement</title>
        <style>
            body { font-family: Arial, sans-serif; }
            h1 { text-align: center; }
            h2 { text-align: left; }
            table { width: 100%; border-collapse: collapse; }
            th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        </style>
    </head>
    <body>
        <h1>Ticket de Paiement</h1>
        <h2>Mode de Paiement</h2>
        <p>' . htmlspecialchars($modeDePaiement) . '</p>
        <h2>Détails de la commande</h2>
        <table>
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Prix</th>
                    <th>Quantité</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>';
    
    foreach ($panier as $item) {
        $quantity = isset($item['quantity']) ? (int)$item['quantity'] : 1; // Cast to integer
        $itemTotal = (float)$item['price'] * $quantity; // Cast price to float
        $htmlContent .= '<tr>
            <td>' . htmlspecialchars($item['name']) . '</td>
            <td>' . htmlspecialchars($item['price']) . ' FCFA</td>
            <td>' . htmlspecialchars($quantity) . '</td>
            <td>' . htmlspecialchars($itemTotal) . ' FCFA</td>
        </tr>';
    }
    
    $htmlContent .= '<tr>
            <td colspan="3"><strong>Total</strong></td>
            <td><strong>' . htmlspecialchars($total) . ' FCFA</strong></td>
        </tr>
        </tbody>
        </table>
    </body>
    </html>';

    // Afficher le contenu
    echo $htmlContent;
    exit();
}
?>