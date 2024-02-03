<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Aquasterion</h1>
    </header>
    <nav>
        <ul>
            <li><a href="index.php?page=home">Accueil</a></li>
            <li><a href="index.php?page=pool_status">Statut de la Piscine</a></li>
            <?php
            // Vérifiez si l'utilisateur est connecté
            if (isset($_SESSION['user_id'])) {
                // Affichez le bouton ou l'onglet du profil
                echo '<li><a href="index.php?page=profile">Profil</a></li>';
            } else {
                // Si l'utilisateur n'est pas connecté, affichez le bouton Connexion
                echo '<li><a href="index.php?page=login">Connexion</a></li>';
            }
            ?>
        </ul>
    </nav>
    <div class="container">
        <?php echo $content; ?>
    </div>
    <footer>
        <p>&copy; <?php echo date('Y'); ?> Aquasterion</p>
    </footer>
</body>
</html>
