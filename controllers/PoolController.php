<?php
class PoolController {
    public function showStatus() {
        $pageTitle = 'Statut de la Piscine';
        ob_start();
        include 'views/pool_status.php';
        $content = ob_get_clean();
        include 'views/layout.php';
    }
}
