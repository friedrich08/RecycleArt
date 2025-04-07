<?php
session_start();

// Logout logic
if (isset($_GET['logout'])) {
    session_destroy(); // Détruire la session
    header("Location: connexion.php"); // Rediriger vers la page de connexion
    exit();
}

// Vérifiez si un produit doit être supprimé
if (isset($_GET['remove'])) {
    $idToRemove = $_GET['remove'];
    if (isset($_SESSION['panier'][$idToRemove])) {
        unset($_SESSION['panier'][$idToRemove]); // Supprimer l'article du panier
    }
}

// Vérifiez si le formulaire a été soumis pour mettre à jour les quantités
if (isset($_POST['update_quantity'])) {
    foreach ($_POST['quantity'] as $id => $quantity) {
        if (is_numeric($quantity) && $quantity > 0) {
            $_SESSION['panier'][$id]['quantity'] = (int)$quantity; // Mettre à jour la quantité
        } else {
            unset($_SESSION['panier'][$id]); // Supprimer l'article si la quantité est invalide
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier</title>
    <link rel="stylesheet" href="panier.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="container">
            <a href="accueil.php" class="navbar-brand">
                <img src="logo.jpg" alt="RecycleArt" class="logo">
            </a>
            <ul class="navbar-nav">
                <li class="nav-item"><a href="accueil.php" class="btn-orange">Accueil</a></li>
                <li class="nav-item"><a href="connexion.php" class="btn-orange">S'inscrire</a></li>
                <li class="nav-item"><a href="panier.php" class="btn-orange">Panier 🛒</a></li>
                <?php if (isset($_SESSION['client'])): ?>
                    <li class="nav-item"><a href="panier.php?logout=1" class="btn-orange">Se déconnecter</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <!-- Contenu principal -->
    <div class="container">
        <h1>Panier</h1>
        <?php if (isset($_SESSION['client'])): ?>
            <p>Bienvenue, <?php echo htmlspecialchars($_SESSION['client']); ?>!</p>
        <?php else: ?>
            <p>Bienvenue, invité!</p>
        <?php endif; ?>

        <?php if (isset($_SESSION['panier']) && count($_SESSION['panier']) > 0): ?>
            <form method="POST">
                <table>
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Prix</th>
                            <th>Quantité</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total = 0; // Initialiser le total
                        foreach ($_SESSION['panier'] as $id => $item): 
                            $price = isset($item['price']) ? (float)$item['price'] : 0;
                            $quantity = isset($item['quantity']) ? (int)$item['quantity'] : 1;
                            $itemTotal = $price * $quantity;
                            $total += $itemTotal;
                        ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['name']); ?></td>
                                <td><?php echo htmlspecialchars($price); ?> FCFA</td>
                                <td>
                                    <input type="number" name="quantity[<?php echo $id; ?>]" value="<?php echo htmlspecialchars($quantity); ?>" min="1" class="quantity-input">
                                </td>
                                <td><?php echo htmlspecialchars($itemTotal); ?> FCFA</td>
                                <td>
                                    <a href="panier.php?remove=<?php echo $id; ?>" class="remove-link" onclick="return confirm('Voulez-vous vraiment supprimer ce produit ?')">Supprimer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr class="total-row">
                            <td colspan="3"><strong>Total</strong></td>
                            <td><strong><?php echo htmlspecialchars($total); ?> FCFA</strong></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
                <button class="pay-btn" type="submit" name="update_quantity">Mettre à jour</button>
                <button class="pay-btn" type="button" onclick="location.href='payement.php'">Procéder au paiement</button>
            </form>
        <?php else: ?>
            <p class="empty-cart">Votre panier est vide.</p>
        <?php endif; ?>
    </div>
</body>
</html>