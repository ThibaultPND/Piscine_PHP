<?php 
    require_once 'models/PoolModel.php';

    $poolModel = new PoolModel();
?>
<div class="tab-content">
    <h2>Statut de la Piscine</h2>
    <div class="pool-status">
        <div class="data-item">
            <div class="data-label">Acidité :</div>
            <div class="data-value"><?= $poolModel->getActualData('PH'); ?> pH</div>
        </div>
        <div class="data-item">
            <div class="data-label">Température:</div>
            <div class="data-value"><?= $poolModel->getActualData('TEMP'); ?>°C</div>
        </div>
        <div class="data-item">
            <div class="data-label">Turbidité:</div>
            <div class="data-value"><?= $poolModel->getActualData('TURB'); ?> NTU</div>
        </div>
        <div class="data-item">
            <div class="data-label">Chlore:</div>
            <div class="data-value"><?= $poolModel->getActualData('ORP'); ?> mV</div>
        </div>
    </div>
</div>
