const mqtt = require('mqtt');
const mysql = require('mysql2');

//! Configuration MQTT
const client = mqtt.connect('mqtt://192.168.0.71', {
    port: 1883
});

//!Configuration Base de données
const db = mysql.createConnection({
    host: '127.0.0.1', // Utilisez l'adresse IPv4
    user: 'root',
    password: 'pool',
    database: 'piscine'
});
db.connect(err => {
    if (err) {
        console.error('Erreur de connexion à la base de données :', err);
        return;
    }
    console.log('Connecté à la base de données MySQL');
});

//! Mesures
function insertMeasure(data_id, value) {
    const sql = 'INSERT INTO Measure_history (data_id, date, value) VALUES (?, CURRENT_TIMESTAMP(), ?)';
    db.query(sql, [data_id, value], (err, result) => {
        if (err) {
            console.error('Erreur lors de l\'insertion de la mesure dans la base de données :', err);
            return;
        }
        console.log('Mesure insérée avec succès dans la base de données :', { data_id, value });
    });
}
function insertAllMeasures(temperature, orp, turbidity, ph) {
    insertMeasure(1, temperature); // ID pour Température
    insertMeasure(2, orp);         // ID pour ORP
    insertMeasure(3, turbidity);   // ID pour Turbidité
    insertMeasure(4, ph);          // ID pour pH
}

//!Valeur de la pompe
let previousActivated;
let previousIsAuto;
function updatePreviousValues(A, B) {
    previousActivated = A;
    previousIsAuto = B;
}
function getABValuesFromDB(callback) {
    const sql = 'SELECT Activated, IsAuto FROM Pomp';
    db.query(sql, (err, results) => {
        if (err) {
            console.error('Erreur lors de la récupération des valeurs de A et B depuis la base de données :', err);
            callback(err);
        } else {
            // Les valeurs de A et B sont dans la première ligne du résultat
            const A = results[0].Activated;
            const B = results[0].IsAuto;
            if (previousIsAuto === 0 && B === 1) {
                checkPumpLimits();
            }
            if (A !== previousActivated || B !== previousIsAuto) {
                callback(null, A, B);
            }
            updatePreviousValues(A, B);
        }
    });
}

setInterval(() => {
    getABValuesFromDB((err, A, B) => {
        if (err) {
            console.error('Erreur lors de la récupération des valeurs de A et B depuis la base de données.');
            return;
        }
        sendMQTTMessage(A, B);
    });
}, 100);

//! Verification Alertes
function checkAlerts() {
    // Récupérer toutes les alertes
    const sqlAlerts = `SELECT * FROM Alerts`;
    db.query(sqlAlerts, (err, alerts) => {
        if (err) {
            console.error('Erreur lors de la récupération des alertes :', err);
            return;
        }
        alerts.forEach(alert => {
            const limiteId = alert.Limite_ID;
            // Récupérer les limites et les comparer avec les dernières mesures
            const sqlLimits = `SELECT l.data_id, l.name, l.value, 
                                      (SELECT mh.value 
                                       FROM Measure_history mh 
                                       WHERE mh.data_id = l.data_id 
                                       ORDER BY mh.date DESC LIMIT 1) AS last_value
                               FROM limite l 
                               WHERE l.ID = ?`;
            db.query(sqlLimits, [limiteId], (err, results) => {
                if (err) {
                    console.error('Erreur lors de la vérification des limites :', err);
                    return;
                } else {
                    console.log("Donnée vérifiée");
                }
                if (results.length > 0) {
                    const { data_id, name, value: limit_value, last_value } = results[0];
                    if (name === 'minimum') {
                        if (last_value < limit_value) {
                            console.log(`Alerte: La valeur ${last_value} de la mesure (ID: ${data_id}) est < limite minimale ${limit_value}`);
                        } else {
                            console.log(`Pas d'alerte: La valeur ${last_value} de la mesure (ID: ${data_id}) est >= limite minimale ${limit_value}`);
                        }
                    } else if (name === 'maximum') {
                        if (last_value > limit_value) {
                            console.log(`Alerte: La valeur ${last_value} de la mesure (ID: ${data_id}) est > limite maximale ${limit_value}`);
                        } else {
                            console.log(`Pas d'alerte: La valeur ${last_value} de la mesure (ID: ${data_id}) est <= limite maximale ${limit_value}`);
                        }
                    }
                }
            });
        });
    });
}
//! Verification limites
function checkPumpLimits() {
    const sqlPompAuto = 'SELECT * FROM Pomp WHERE IsAuto = 1';
    db.query(sqlPompAuto, (err, pompes) => {
        if (err) {
            console.error('Erreur lors de la vérification du mode automatique de la pompe :', err);
            return;
        }
        console.log("pompes.length "+ pompes.length)
        if (pompes.length > 0) {
            pompes.forEach(pomp => {
                const pompId = pomp.ID;
                const sqlPumpLimits = `SELECT pl.Limite_ID, l.data_id, l.name, l.value, 
                                          (SELECT mh.value 
                                           FROM Measure_history mh 
                                           WHERE mh.data_id = l.data_id 
                                           ORDER BY mh.date DESC LIMIT 1) AS last_value
                                       FROM Pompes_Limites pl
                                       JOIN limite l ON pl.Limite_ID = l.ID
                                       WHERE pl.Pompe_ID = ?`;
                db.query(sqlPumpLimits, [pompId], (err, results) => {
                    if (err) {
                        console.error('Erreur lors de la vérification des limites de la pompe :', err);
                        return;
                    }
                    let pompeActivee = false;
                    results.forEach(row => {
                        const { data_id, name, value: limit_value, last_value } = row;
                        if (name === 'minimum' && last_value < limit_value) {
                            console.log(`Alerte: La valeur ${last_value} de la mesure (ID: ${data_id}) est < limite minimale ${limit_value} pour la pompe ${pompId}`);
                            pompeActivee = true;
                        } else if (name === 'maximum' && last_value > limit_value) {
                            console.log(`Alerte: La valeur ${last_value} de la mesure (ID: ${data_id}) est > limite maximale ${limit_value} pour la pompe ${pompId}`);
                            pompeActivee = true;
                        }
                    });
                    if (pompeActivee) {
                        activatePump(pompId);
                    } else {
                        deactivatePump(pompId);
                    }
                });
            });
        }
    });
}
// Fonction pour activer la pompe
function activatePump(pompId) {
    const sql = 'UPDATE Pomp SET Activated = 1 WHERE ID = ?';
    db.query(sql, [pompId], (err, result) => {
        if (err) {
            console.error('Erreur lors de l\'activation de la pompe :', err);
            return;
        }
        console.log(`Pompe ${pompId} activée automatiquement.`);
    });
}

// Fonction pour désactiver la pompe
function deactivatePump(pompId) {
    const sql = 'UPDATE Pomp SET Activated = 0 WHERE ID = ?';
    db.query(sql, [pompId], (err, result) => {
        if (err) {
            console.error('Erreur lors de la désactivation de la pompe :', err);
            return;
        }
        console.log(`Pompe ${pompId} désactivée automatiquement.`);
    });
}

function sendMQTTMessage(A, B) {
    const message = `${A}:${B}`;
    // Vérifier si les valeurs actuelles sont différentes des précédentes
    if (A !== previousActivated || B !== previousIsAuto) {
        client.publish('ESP_READER', message, (err) => {
            if (err) {
                console.error('Erreur lors de l\'envoi de la trame MQTT :', err);
                return;
            }
        });
        updatePreviousValues(A, B);
    }
}
// L'événement 'message' est déclenché lorsqu'un message est reçu
client.on('message', (topic, message) => {
    checkAlerts();
    checkPumpLimits();
    const messageString = message.toString();
    console.log('Réponse reçue:', messageString);
    // Séparer les valeurs
    const values = messageString.split(':');
    // Vérifier si le message contient les quatre valeurs attendues
    if (values.length === 4) {
        // Stocker les valeurs dans des variables
        const temperature = parseFloat(values[0]);
        const orp = parseInt(values[1]);
        const turbidity = parseInt(values[2]);
        const ph = parseFloat(values[3]);
        // Afficher les valeurs extraites
        console.log('Température:', temperature);
        console.log('ORP:', orp);
        console.log('Turbidité:', turbidity);
        console.log('pH:', ph);

        // Insérer toutes les mesures dans la base de données
        insertAllMeasures(temperature, orp, turbidity, ph);
    } else {
        console.log("Le message ne contient pas le format attendu.");
    }
});
// Connecter au serveur MQTT
client.on('connect', () => {
    console.log('Connecté au serveur MQTT');
    client.subscribe('RASP_READER', (err) => {
        if (!err) {
            console.log('Souscription au topic RASP_READER réussie');
            checkAlerts();
            checkPumpLimits();
        } else {
            console.error('Erreur de souscription :', err);
        }
    });
});
client.on('error', (err) => {
    console.error('Erreur de connexion :', err);
});
client.on('close', () => {
    console.log('Connexion fermée');
});
client.on('reconnect', () => {
    console.log('Tentative de reconnexion');
});
client.on('offline', () => {
    console.log('Client en mode hors ligne');
});