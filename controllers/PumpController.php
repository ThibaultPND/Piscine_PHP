<?php
require_once 'models/PoolModel.php';

class PumpController
{
    private $model;

    public function __construct()
    {
        $this->model = new PoolModel();
    }

    public function changePumpMode()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire
            $mode = $_POST['mode'];
            $activated = $_POST['activated'];

            // Appeler la méthode changePumpMode du modèle
            $this->model->changePumpMode($mode, $activated);
        }
        ViewController::showView("pomp_manager");
    }

    public function getPumpState()
    {
        return $this->model->getPumpState();
    }
    public function getPumpMode()
    {
        return $this->model->getPumpMode();
    }

    public function getPumpLimits(){
        return $this->model->getPumpLimits();
    }
}
