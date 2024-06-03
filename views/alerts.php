<?php
require_once ('controllers/PoolController.php');
$poolController = new PoolController();
$alerts = $poolController->getAlerts();

?>
<div class="tab-content">
    <h2>Gestion des alertes</h2>
    <?php
    $alerts = $poolController->getAlerts();
    foreach ($alerts as $alert) {
        // Récupérer les détails de la limite et du message pour cette alerte spécifique
        $limit = $poolController->getLimit($alert['Limite_ID']);
        $message = $poolController->getMessage($alert['Message_ID']);
    ?>
        <p>
            <?= $message['Message'] ?> : de <?= $limit['Value'] ?> à <?= $limit['maximum'] ?>
        </p>
    <?php } ?>
    <button class='blue_button' onclick="window.location.href='index.php?page=change_alerts'">Modifier</button>
</div>
