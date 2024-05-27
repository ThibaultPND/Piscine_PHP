<?php
require_once 'models/Database.php';
class UserModel
{
    private $db;
    public function __construct() {
        $this->db = new Database();
        $username = "unknown";
        $id = NULL;
    }
    public function authenticate($username, $password)
    {
        if ($row = $this->db->getRowByQuery("SELECT ID,Password FROM User WHERE username = ?", array($username))) {

            if (password_verify($password, $row["Password"])) {
                session_start();
                $_SESSION["username"] = $username;
                $_SESSION["ID"] = $row['ID'];
                return 0;
            }
        }
        return 1;
    }
    public function changeUsername($id, $new_username, $new_username_confirmation, $password)
    {
        if ($new_username != $new_username_confirmation) {
            $_SESSION["bad_confirmation"] = true;
            return;
        }

        if ($row = $this->db->getRowByQuery("SELECT Password FROM User WHERE ID = ?", array($id))) {
            if (password_verify($password, $row['Password'])) {
                $this->db->query("UPDATE User SET username = '$new_username' WHERE ID = ?", array($id));
                $_SESSION["username"] = $new_username;
            } else {
                $_SESSION["wrong_password"] = true;
            }
        }
    }

    function changePassword($id, $password, $new_password, $new_password_confirmation)
    {
        if ($new_password != $new_password_confirmation) {
            $_SESSION["change_error"] = "Les mots de passe ne sont pas identiques.";
            return;
        }
        $row = $this->db->getRowByQuery("SELECT Password FROM User WHERE ID = ?", [$id]);
        
        if (password_verify($password, $row['Password'])) {
            $new_password = password_hash($new_password, PASSWORD_ARGON2I);
            $sql = "UPDATE User SET Password = '$new_password' WHERE ID = " . $id;
            $this->db->query($sql);
        } else {
            $_SESSION["change_error"] = "Le  mot de passe actuel est invalide";
        }
    }

}
