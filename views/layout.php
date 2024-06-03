<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $pageTitle; ?></title>
        <link rel="stylesheet" href="css/style.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700&family=Rubik:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">
    </head>
    <body>
        <header>
            <h1>Aquasterion</h1>
        </header>
        <nav>
            <ul class="nav-list">
                <li><a href="index.php?page=home">Accueil</a></li>
                <li><a href="index.php?page=pool_status">Statut de la Piscine</a></li>
                <?php
                // Vérifiez si l'utilisateur est connecté
                if (isset($_SESSION["ID"])) {
                    // Affichez le bouton ou l'onglet du profil
                    echo '<li><a href="index.php?page=history">Historique</a></li>';
                    echo '<li><a href="index.php?page=alerts">Alertes</a></li>';
                    echo '<li><a href="index.php?page=profile">Profil</a></li>';
                    echo '<li><a href="index.php?page=pomp_manager">Pomp</a></li>';
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
            <div class="footer-content">
            <div class="footer-left">
    <a href="http://aquasterion.fr/"><img src="../css/logo/aquasterion.png" class="company-logo" alt="Logo Aquasterion"></a>
    <div class="social-media-icons">
        <a href="https://tiktok.com"><img src="css/logo/tiktok.png"  class="social-icon" alt="TikTok"></a>
        <a href="https://www.instagram.com/asterion_le_bon/"><img src="css/logo/instagram.png"  class="social-icon" alt="Instagram"></a>
        <a href="https://x.com/aubolangi/"><img src="css/logo/twitter.png"  class="social-icon" alt="Twitter"></a>
        <a href="https://www.youtube.com/@OlivierTrading"><img src="css/logo/youtube.png"  class="social-icon" alt="YouTube"></a>
        <a href="https://www.linkedin.com/in/christophe-chatelot/"><img src="css/logo/linkedin.png"  class="social-icon" alt="LinkedIn"></a>
    </div>
</div>

                <p class="footer-right">&copy; <?php echo date('Y'); ?> Aquasterion</p>
            </div>
        </footer>
    </body>
</html>
