<div class="tab-content">
    <h2>Profil</h2>
    <?php
    $UserModel = new UserModel();
    // Utilisez des déclarations préparées pour éviter les injections SQL
    if (isset ($_POST)):
        ?>
        <p><strong>Nom d'utilisateur : </strong>
            <?php echo $_SESSION["username"]; ?>
        </p>
        <button class="blue_button" onclick="window.location.href='index.php?page=change_username'">Changer le nom
            d'utilisateur</button>
        <button class="blue_button" onclick="window.location.href='index.php?page=change_password'">Changer le mot de
            passe</button>
        <button class="red_button" onclick="window.location.href='views/logout.php'">Se déconnecter</button>
        <?php
    endif;
    ?>
</div>