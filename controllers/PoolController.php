<?php
require_once 'models/UserModel.php';
require_once 'models/PoolModel.php';

class PoolController
{
    private $model;
    public function __construct() {
        $this->model = new PoolModel();
    }
    public function updateAlerts()
    {
        $userModel = new UserModel();

        // Préparez une requête SQL d'UPDATE avec les valeurs de votre tableau
        $sql = "UPDATE alerts
        SET minimum =
            CASE
                WHEN donnee = 'ORP' THEN ?
                WHEN donnee = 'pH' THEN ?
                WHEN donnee = 'temp' THEN ?
                WHEN donnee = 'turb' THEN ?
                ELSE minimum
            END,
            maximum =
            CASE
                WHEN donnee = 'ORP' THEN ?
                WHEN donnee = 'pH' THEN ?
                WHEN donnee = 'temp' THEN ?
                WHEN donnee = 'turb' THEN ?
                ELSE maximum
            END
        WHERE donnee IN ('ORP', 'pH', 'temp', 'turb');
        ";
        // Exécutez la requête SQL pour mettre à jour les données
        $userModel->executeQuery(
            $sql,
            [
                $_POST['minchl'],
                $_POST['minpH'],
                $_POST['mintemp'],
                $_POST['minturb'],
                $_POST['maxchl'],
                $_POST['maxpH'],
                $_POST['maxtemp'],
                $_POST['maxturb']
            ]
        );

        ViewController::showView("alerts");

        
    }
    public function changePumpMode() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire
            $pumpID = intval($_POST['pumpID']);
            $mode = $_POST['mode'];
            $activated = $_POST['activated'];

            // Appeler la méthode changePumpMode du modèle
            $result = $this->model->changePumpMode($pumpID, $mode, $activated);

            if ($result) {
                echo "Le mode de la pompe a été mis à jour avec succès.";
            } else {
                echo "Échec de la mise à jour du mode de la pompe.";
            }
        } else {
            // Afficher le formulaire
            ViewController::showView("pomp_manager");  
        }
        ViewController::showView("pomp_manager");  
    }
    public function pompManager()
    {
        ViewController::showView("pomp_manager");
        $poolModel = new PoolModel();

        // Récupérer l'état actuel de la pompe depuis la base de données
        $activatedState = $poolModel->getPumpActivatedState();
        if ($activatedState !== false) {
            echo "État actuel de la pompe : " . ($activatedState == 1 ? "Allumée" : "Éteinte");
        } else {
            echo "Impossible de récupérer l'état actuel de la pompe.";
        }
    }
}
