<?php
require_once ('controllers/PumpController.php');
$pumpController = new PumpController();
$current_mode = $pumpController->getPumpMode();
$current_state = $pumpController->getPumpState();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Changer le mode de fonctionnement de la pompe</title>
    <style>
        /* Ajout d'une classe pour masquer l'élément */
        .hidden {
            display: none;
        }
    </style>
</head>

<body>
    <h1>Changer le mode de fonctionnement de la pompe</h1>
    <form action="index.php?page=change_pump_mode" method="POST">

        <label for="mode">Mode:</label>
        <select id="mode" name="mode" required>
            <option value="auto" <?= ($current_mode == 'auto') ? 'selected' : '' ?>>Auto</option>
            <option value="manuel" <?= ($current_mode == 'manuel') ? 'selected' : '' ?>>Manuel</option>
        </select><br><br>

        <div id="state-container">
            <label for="activated">État:</label>
            <select id="activated" name="activated" required>
                <option value="on" <?= ($current_state == 'on') ? 'selected' : '' ?>>Activé</option>
                <option value="off" <?= ($current_state == 'off') ? 'selected' : '' ?>>Désactivé</option>
            </select><br><br>
        </div>

        <input type="submit" value="Changer le mode">
    </form>
    <script>
        document.getElementById('mode').addEventListener('change', function () {
            const mode = this.value;
            const stateContainer = document.getElementById('state-container');

            if (mode === 'auto') {
                stateContainer.classList.add('hidden');
            } else {
                stateContainer.classList.remove('hidden');
            }
        });

        // Initialiser l'état au chargement de la page
        window.addEventListener('load', function () {
            const mode = document.getElementById('mode').value;
            const stateContainer = document.getElementById('state-container');

            if (mode === 'auto') {
                stateContainer.classList.add('hidden');
            }
        });
    </script>
    <h2>Modifier les limites de seuil de la pompe</h2>
    <?php $data['pumpLimits'] = $pumpController->getPumpLimits(); ?>
    <form action="index.php?page=change_pump_limits" method="post">
        <table border="2">
            <thead>
                <tr>
                    <th>Nom de la limite</th>
                    <th>Type de donnée</th>
                    <th>Valeur</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($data['pumpLimits'])): ?>
                    <?php foreach ($data['pumpLimits'] as $limit): ?>
                        <tr>
                            <td>
                                <select name="limits[<?= $limit['Data_Name'] ?>][data_id]" required>
                                    <option value="TEMP" <?= $limit['Data_Name'] == 'TEMP' ? 'selected' : ''; ?>>Température (°C)
                                    </option>
                                    <option value="ORP" <?= $limit['Data_Name'] == 'ORP' ? 'selected' : ''; ?>>ORP (mV)</option>
                                    <option value="TURB" <?= $limit['Data_Name'] == 'TURB' ? 'selected' : ''; ?>>Turbidité (NTU)
                                    </option>
                                    <option value="PH" <?= $limit['Data_Name'] == 'PH' ? 'selected' : ''; ?>>pH</option>
                                </select>
                            </td>
                            <td>
                                <select name="limits[<?= $limit['Limite_ID'] ?>][limite_name]" required>
                                    <option value="minimum" <?= $limit['Limite_Name'] == 'minimum' ? 'selected' : '' ?>>Inférieur à
                                    </option>
                                    <option value="maximum" <?= $limit['Limite_Name'] == 'maximum' ? 'selected' : '' ?>>Suppérieur
                                        à
                                    </option>
                                </select>
                            </td>
                            <td>
                                <input type="number" name="limits[<?= $limit['Limite_ID'] ?>][data_type]"
                                    value="<?= $limit['Data_Type'] ?>" required>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">Aucune limite de seuil trouvée pour cette pompe.</td>
                    </tr>
                <?php endif; ?>
                <td colspan="3">

                    <button type="submit" name="add_limit">Ajouter une nouvelle limite</button>
                </td>
            </tbody>
        </table>
        <br>
        <button type="submit">Enregistrer les modifications</button>
    </form>

</body>

</html>