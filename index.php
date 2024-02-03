<?php
session_start();

require_once 'controllers/PoolController.php';
require_once 'controllers/HomeController.php';
require_once 'controllers/AuthController.php';
require_once 'controllers/ProfileController.php';

$poolController = new PoolController();
$homeController = new HomeController();
$authController = new AuthController();
$profileController = new ProfileController();

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

switch ($page) {
    case 'pool_status':
        $poolController->showStatus();
        break;
    case 'login':
        $authController->showLogin();
        break;
    case 'process_login':
        $authController->login();
        break;
    case 'profile':
        $profileController->showProfil();
        break;
    case 'change_username':
        $authController->showChangeUsername();
        break;
    case 'change_username_process':
        $authController->updateUsername();
        break;
    case 'change_password':
        $authController->showChangePassword();
        break;
    case 'change_password_process':
        $authController->updatePassword();
        break;
    default:
        $homeController->showHome();
}
?>