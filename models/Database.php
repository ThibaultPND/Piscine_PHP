<?php
class Database 
{
    private $conn;

    public function __construct() {
        $this->conn = new mysqli("localhost", "root", "pool", "piscine");

        if ($this->conn->connect_error) {
            die("Echec de la connexion à la base de données : " . $this->conn->connect_error);
        }
    }

    private function reconnect() {
        $this->conn = new mysqli("localhost", "root", "pool", "piscine");

        if ($this->conn->connect_error) {
            die("Echec de la reconnexion à la base de données : " . $this->conn->connect_error);
        }
    }

    public function query($sql, $param = array()) {
        // Exécuter la requête SQL
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            $this->reconnect();
            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                die("Echec de la préparation de la requête SQL : " . $this->conn->error);
            }
        }

        if (!empty($param)) {
            $types = str_repeat('s', count($param));
            if (!$stmt->bind_param($types, ...$param)) {
                die("Echec lors du bind des paramètres : " . $stmt->error);
            }
        }

        if (!$stmt->execute()) {
            die("Echec de la requête SQL : " . $stmt->error);
        }

        $result = $stmt->get_result();

        $stmt->close();

        return $result;
    }

    public function getRowByQuery($query, $param = array()){
        $result = $this->query($query, $param);
        return $result->fetch_assoc();
    }

    // Fermer la connexion à la base de données
    public function close() {
        $this->conn->close();
    }
}
?>
