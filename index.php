<?php
session_start();
require_once 'controllers/PoolController.php';
require_once 'controllers/HomeController.php';
require_once 'controllers/AuthController.php';
require_once 'controllers/ProfileController.php';
require_once 'controllers/ViewController.php';
require_once 'controllers/PumpController.php';


require_once 'models/UserModel.php';

$poolController = new PoolController();
$pumpController = new PumpController();
$homeController = new HomeController();
$authController = new AuthController();
$profileController = new ProfileController();

$page = isset ($_GET['page']) ? $_GET['page'] : 'home';


match ($page) {
    'process_login' => $authController->login(),
    'change_username_process' => $authController->updateUsername(),
    'change_password_process' => $authController->updatePassword(),
    'change_alerts_process' => $poolController->updateAlerts(),
    'change_pump_mode' => $pumpController->changePumpMode(),
    default => ViewController::showView($page)
};