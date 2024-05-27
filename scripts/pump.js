// pump.js

const { db } = require('./mqtt_mysql');
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

module.exports = {
    updatePreviousValues,
    getABValuesFromDB,
    activatePump,
    deactivatePump,
    checkPumpLimits,
    previousActivated,
    previousIsAuto
};
