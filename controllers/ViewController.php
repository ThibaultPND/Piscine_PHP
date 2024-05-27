<?php
class ViewController
{
    public static function showView($view = "home")
    {
        $pageTitle = self::getPageTitleByView($view);
        ob_start();
        include 'views/' . $view . '.php';
        $content = ob_get_clean();
        include 'views/layout.php';
    }
    private static function getPageTitleByView($view)
    {
        return match ($view) {
            "alerts" => "Page d'alertes",
            "change_alerts" => 'Changement d\'alertes',
            "change_password" => "Changement mot de passe",
            "change_username" => "Changement nom d'utilisateur",
            "history" => "Historique des mesures",
            "home" => "Page d'acceuil",
            "login" => "Connexion",
            "logout" => "Deconnexion",
            "pool_status" => "Statut de la piscine",
            "profile" => "Profile",
            "pomp_manager" => "Gestionnaire de pompe",
            default => "Erreur404"
        };
    }
}
