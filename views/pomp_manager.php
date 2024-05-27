<!-- views/change_pump_mode.php -->
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
            <option value="auto">Auto</option>
            <option value="manuel">Manuel</option>
        </select><br><br>

        <div id="state-container">
            <label for="activated">État:</label>
            <select id="activated" name="activated" required>
                <option value="on">Activé</option>
                <option value="off">Désactivé</option>
            </select><br><br>
        </div>

        <input type="submit" value="Changer le mode">
    </form>
    <script>
        document.getElementById('mode').addEventListener('change', function() {
            const mode = this.value;
            const stateContainer = document.getElementById('state-container');

            if (mode === 'auto') {
                stateContainer.classList.add('hidden');
            } else {
                stateContainer.classList.remove('hidden');
            }
        });

        // Initialiser l'état au chargement de la page
        window.addEventListener('load', function() {
            const mode = document.getElementById('mode').value;
            const stateContainer = document.getElementById('state-container');

            if (mode === 'auto') {
                stateContainer.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
