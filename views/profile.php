<div class="tab-content">
    <h2>Profil</h2>
    <?php
        $servername = "localhost";
        $db_username = "root";
        $db_password = "";
        $dbname = "piscine";

        $conn = new mysqli($servername, $db_username, $db_password, $dbname);
        if ($conn->connect_error) {
            die("Connexion base de données erreur : " . $conn->connect_error);
        }

        // Utilisez des déclarations préparées pour éviter les injections SQL
        $query = "SELECT * FROM Users WHERE ID = ".$_SESSION["user_id"];
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) :
            $row = $result->fetch_assoc();
    ?>
        <p><strong>Nom d'utilisateur :</strong> <?php echo $row["username"]; ?></p>
        <button class="blue_button" onclick="window.location.href='index.php?page=change_username'">Changer le nom d'utilisateur</button>
        <button class="blue_button" onclick="window.location.href='index.php?page=change_password'">Changer le mot de passe</button>
        <button class="red_button" onclick="window.location.href='views/logout.php'">Se déconnecter</button>
    <?php
        endif;

        // Fermez la connexion à la base de données
        $stmt->close();
        $conn->close();
    ?>
</div>
