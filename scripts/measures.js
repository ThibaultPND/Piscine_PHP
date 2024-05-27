// measures.js

const { db } = require('./mqtt_mysql');

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
    insertMeasure(4, ph);        
}

module.exports = {
    insertMeasure,
    insertAllMeasures
};
