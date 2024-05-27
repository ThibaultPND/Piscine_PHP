<?php
require_once 'models/PoolModel.php';

$poolModel = new PoolModel();
$seuilPh = $poolModel->getSeuilAlert(DataType::PH);
$seuilTurb = $poolModel->getSeuilAlert(DataType::TURB);
$seuilTemp = $poolModel->getSeuilAlert(DataType::TEMP);
$seuilORP = $poolModel->getSeuilAlert(DataType::ORP);
?>

<div class="tab-content">
    <h2>Gestion des alertes</h2>
    <p> 
        Seuil de pH             :  de <?php 
        echo $seuilPh['minimum'];
        echo " à ";
        echo $seuilPh['maximum'];
        echo " pH";
        ?></br>
        Seuil de température    : de <?php  
        echo $seuilTemp['minimum'];
        echo " à ";
        echo $seuilTemp['maximum'];
        echo " °C";
        ?></br>
        Seuil de turbilité      : <?php  
        echo $seuilTurb['minimum'];
        echo " à ";
        echo $seuilTurb['maximum'];
        echo " NTU";
        ?></br>
        Seuil de chlore         : <?php  
        echo $seuilORP['minimum'];
        echo " à ";
        echo $seuilORP['maximum'];
        echo " mg/L";
        ?></br>
    </p>

    <button class='blue_button' onclick="window.location.href='index.php?page=change_alerts'">Modifier</button>

</div>