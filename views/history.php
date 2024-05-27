<?php require_once "models/Database.php"; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau PHP</title>
    <style>
        body {
            
            background-size: cover;
            background-repeat: no-repeat;
            height: 100vh; /* Cette propriété permet au fond d'écran de couvrir toute la hauteur de la page */
        }

        .tab-content {
            
            /*ui*/
        }

        table {
            border-collapse: collapse;
            width: 50%;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
<div class="tab-content">
    <table>
        <tr>
            <th>date</th>
            <th>Données</th>
            <th>valeurs</th>
            
        </tr>
        <?php
        $db = new Database();
        $sql = "SELECT Date,Value,Name FROM Measure_history mh JOIN Data d ON d.ID = mh.Data_id ORDER BY  Date DESC";
        $req = $db->query($sql);
        while ($row = $req->fetch_assoc()) {
            ?>
            <tr>
                
                <td><?php echo $row['Date'] ?></td>
                <td><?php echo $row['Name'] ?></td>
                <td><?php echo $row['Value'] ?></td>
               
            </tr>
            <?php
        }
        ?>
    </table>
</div>
</body>
</html>
