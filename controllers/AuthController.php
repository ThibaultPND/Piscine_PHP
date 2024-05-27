<?php
require_once 'models/UserModel.php';

class AuthController
{
    private $model;

    public function __construct() {
        $this->model = new UserModel();
    }
    public function login()
    {
        // Vérifie si le formulaire a été soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Récupère les données du formulaire
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Vérifie les informations d'identification
            if ($this->model->authenticate($username, $password)) {
                $_SESSION['login_error'] = 'Nom d\'utilisateur ou mot de passe incorrect';
                ViewController::showView("login");
                return;
            } else {
                ViewController::showView();
            }
        }
    }

    public function updateUsername()
    {
        if (isset ($_SESSION['ID'])) {


            $user_id = $_SESSION['ID'];
            $new_username = $_POST['new_username'];
            $new_username_confirmation = $_POST['new_username_confirmation'];


            $userModel = new UserModel();
            $userModel->changeUsername($user_id, $new_username, $new_username_confirmation, $_POST['password']);

            if (isset ($_SESSION["bad_confirmation"])) {
                unset($_SESSION["bad_confirmation"]);
                header('Location: index.php?page=change_username&new_username=' . $new_username . '&new_username_confirmation=' . $new_username_confirmation);
                $_SESSION['change_error'] = "Les noms ne sont pas identiques !";
                exit();
            } elseif (isset ($_SESSION["wrong_password"])) {
                unset($_SESSION["wrong_password"]);
                header('Location: index.php?page=change_username&new_username=' . $new_username . '&new_username_confirmation=' . $new_username_confirmation);
                $_SESSION['change_error'] = "Le mot de passe est invalide !";
                exit();
            } else {
                ViewController::showView("profile");
            }
        } else {
            ViewController::showView("login");
        }
    }

    function updatePassword()
    {
        if (isset ($_SESSION['ID'])) {

            $userModel = new UserModel();

            $user_id = $_SESSION['ID'];
            $new_password = $_POST['new_password'];
            $new_password_confirmation = $_POST['new_password_confirmation'];

            $userModel->changePassword($user_id, $_POST['password'], $new_password, $new_password_confirmation);

            if (isset ($_SESSION["change_error"])) {
                header('Location: index.php?page=change_password');
                exit();
            } else { // Redirigez vers la page de profil après la mise à jour
                header('Location: index.php?page=profile');
                exit();
            }
        } else {
            // L'utilisateur n'est pas connecté, redirigez-le vers la page de connexion
            header('Location: index.php?page=login');
            exit();
        }
    }
}
