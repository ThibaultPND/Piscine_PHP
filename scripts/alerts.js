// alerts.js

const { db } = require('./mqtt_mysql');

function checkAlerts() {
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

module.exports = {
    checkAlerts
};
