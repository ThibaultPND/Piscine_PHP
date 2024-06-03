<?php
require_once 'models/Database.php';
abstract class DataType
{
    const ORP = 2;
    const TEMP = 1;
    const TURB = 3;
    const PH = 4;
}

class PoolModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getLastMeasureTime()
    {
        $sql = 'SELECT Measure_history.Date
                    FROM Measure_history
                    ORDER BY Date DESC
                    LIMIT 1';
        $data = $this->db->getRowByQuery($sql);
        return $data['Date'];
    }
    public function getActualData($type)
    {
        $sql = "SELECT Measure_history.Value 
                    FROM Measure_history 
                    JOIN Data ON Measure_history.Data_ID = Data.ID
                    WHERE Data.name = ? 
                    ORDER BY Date DESC 
                    LIMIT 1";

        $data = $this->db->getRowByQuery($sql, [$type]);

        return $data['Value'];
    }

    public function getSeuilAlert($type)
    {
        $sql = "SELECT minimum, maximum  FROM alerts WHERE donnee = ?";
        $Database = new Database();
        $data = $Database->getRowByQuery($sql, array($type));

        return $data;
    }

    public function changeAlerts($type)
    {
    }
    public function changePumpMode($mode, $activated)
    {
        if ($mode != 'auto' && $mode != 'manuel') {
            return false;
        }
        $modeValue = ($mode == "auto") ? 1 : 0;
        $activateValue = ($activated == "on") ? 1 : 0;
        if ($modeValue) {
            $sql = "UPDATE Pomp SET isAuto = ? ";
            $this->db->query($sql, [$modeValue]);
        } else {
            $sql = "UPDATE Pomp SET Activated = ?, isAuto = ? ";
            $this->db->query($sql, [$activateValue, $modeValue]);
        }
    }

    public function pomp($A, $B)
    {
        // Ajoutez votre logique PHP ici pour utiliser les valeurs de A et B
        // Par exemple, vous pouvez les utiliser pour mettre à jour les seuils ou effectuer d'autres actions nécessaires dans votre application
        // $A et $B sont les valeurs que vous avez envoyées depuis votre script PHP
        echo "Valeur de A : $A, Valeur de B : $B";

        // URL de votre API Node.js sur la Raspberry Pi
        $url = 'http://localhost:3000/pompe';

        // Construire l'URL avec les paramètres de A et B
        $url .= '?A=' . $A . '&B=' . $B;

        // Envoyer une requête HTTP GET à votre API Node.js sur la Raspberry Pi
        $response = file_get_contents($url);

        // Vérifier si la requête a réussi
        if ($response === FALSE) {
            // La requête a échoué
            echo "Erreur lors de l'envoi des valeurs de A et B à l'API Node.js";
        } else {
            // La requête a réussi
            echo "Valeurs de A et B envoyées avec succès à l'API Node.js sur la Raspberry Pi";
        }
    }

    public function getPumpMode()
    {
        $query = "SELECT isAuto FROM Pomp WHERE ID = 1";
        $Database = new Database();
        $result = $Database->getRowByQuery($query);

        if ($result) {
            return ($result['isAuto']) ? 'auto' : 'manuel';
        } else {
            return 'manuel'; // Ou une autre valeur par défaut si la récupération échoue
        }
    }

    public function getPumpState()
    {
        $query = "SELECT Activated FROM Pomp WHERE ID = 1";
        $Database = new Database();
        $result = $Database->getRowByQuery($query);

        if ($result) {
            return ($result['Activated']) ? 'on' : 'off';
        } else {
            return 'manuel'; // Ou une autre valeur par défaut si la récupération échoue
        }
    }

    public function getPumpLimits()
    {
        $query = 'SELECT
                            p.ID AS Pump_ID,
                            pl.Limite_ID,
                            l.Name AS Limite_Name,
                            l.Value AS Data_Type
                        FROM 
                            Pomp p
                        JOIN
                            Pompes_Limites pl ON p.ID = pl.Pompe_ID
                        JOIN
                            limite l ON pl.Limite_ID = l.ID
                        JOIN
                            Data d ON l.Data_ID = d.ID
                        WHERE
                            p.ID = 1;';
        $db = new Database();
        $result =  $db->query($query);
        return $result ? $result : [$result];
    }

    public function getAlerts(){

        $query = "SELECT * FROM Alerts";
        $db = new Database();
        $result = $db->getRowByQuery($query);
        return $result;
        
    }
    public function getLimit($limitId)
{
    $sql = "SELECT * FROM limite WHERE ID = ?";
    $Database = new Database();
    $result = $Database->getRowByQuery($sql, [$limitId]);
    return $result;

}

public function getMessage($messageId)
{
    $sql = "SELECT * FROM Messages_alertes WHERE ID = ?";
    $Database = new Database();
    $result = $Database->getRowByQuery($sql,[$messageId]);
    return $result;
}

    

}
