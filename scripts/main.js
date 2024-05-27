// main.js

const { client } = require('./mqtt_mysql');
const { checkAlerts } = require('./alerts');
const { checkPumpLimits, getABValuesFromDB,previousActivated,previousIsAuto,updatePreviousValues } = require('./pump');
const { insertAllMeasures } = require('./measures');

// Fonction pour vérifier les valeurs de A et B et envoyer un message MQTT en cas de changement
function checkABValues() {
    getABValuesFromDB((err, A, B) => {
        if (err) {
            console.error('Erreur lors de la récupération des valeurs de A et B depuis la base de données.');
            return;
        }
        sendMQTTMessage(A, B);
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
// Vérifier les valeurs de A et B périodiquement
setInterval(checkABValues, 100);

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

client.on('message', (topic, message) => {
    checkAlerts();
    checkPumpLimits();
    const messageString = message.toString();
    console.log('Réponse reçue:', messageString);
    const values = messageString.split(':');
    if (values.length === 4) {
        const temperature = parseFloat(values[0]);
        const orp = parseInt(values[1]);
        const turbidity = parseInt(values[2]);
        const ph = parseFloat(values[3]);
        console.log('Température:', temperature);
        console.log('ORP:', orp);
        console.log('Turbidité:', turbidity);
        console.log('pH:', ph);
        insertAllMeasures(temperature, orp, turbidity, ph);
    } else {
        console.log("Le message ne contient pas le format attendu.");
    }
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
