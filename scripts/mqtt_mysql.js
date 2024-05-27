// mqtt_mysql.js

const mqtt = require('mqtt');
const mysql = require('mysql2');

// Configuration MQTT
const client = mqtt.connect('mqtt://192.168.0.71', { port: 1883 });

// Configuration Base de données
const db = mysql.createConnection({
    host: '127.0.0.1',
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

module.exports = {
    client,
    db
};
