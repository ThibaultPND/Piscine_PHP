<div class='tab-content'>
    <h1>Connexion</h1>

    <?php
        if (isset($_SESSION['login_error'])) {
            echo '<div id="errorDiv">' . $_SESSION['login_error'] . '</div>';
            unset($_SESSION['login_error']); // Efface l'erreur de la session après l'affichage
        }
    ?>

    <form id="loginForm" action="index.php?page=process_login" method="post">
        <label for="username">Nom d'utilisateur :</label>
        <input type="text" name="username" required>

        <label for="password">Mot de passe :</label>
        <input type="password" name="password" required>

        <button type="submit">Se connecter</button>
    </form>
</div>
