<?php
    require_once 'models/PoolModel.php';

    $poolModel = new PoolModel();

    $seuilPh = $poolModel->getSeuilAlert(DataType::PH);
    $seuilTurb = $poolModel->getSeuilAlert(DataType::TURB);
    $seuilTemp = $poolModel->getSeuilAlert(DataType::TEMP);
    $seuilORP = $poolModel->getSeuilAlert(DataType::ORP);
?>



<div class="tab-content">
 <h2>Modifier les seuils</h2>
   
 <form id="changeAlertForm" action="index.php?page=change_alerts_process" method=POST>
    <table border="0">       
        <tr>
            <td>
                <table border ="0">
                    <tr>
                        <td><label for="minpH">Nouveau seuil de pH minimal :</label></td>
                        <td><input type="number" id="minpH" name="minpH" value="<?php echo$seuilPh['minimum'] ?>" min="6" max="8" step="0.1" require></td>
                    </tr>
                    <tr>
                        <td><label for="maxpH">Nouveau seuil de pH maximal :</label></td>
                        <td><input type="number" id="maxpH" name="maxpH" value="<?php echo$seuilPh['maximum'] ?>" min="6" max="8" step="0.1" require></td>
                    </tr>
                </table>
            </td>
                
            <td>
                <table border="0">
                    <tr>
                        <td><label for="mintemp">Nouveau seuil de température minimal :</label></td>
                        <td><input type="number" id="mintemp" name="mintemp" value="<?php echo$seuilTemp['minimum'] ?>" min="10" max="40" require></td>
                    </tr>
                    <tr>
                        <td><label for="maxtemp">Nouveau seuil de température maximal :</label></td>
                        <td><input type="number" id="maxtemp" name="maxtemp" value="<?php echo$seuilTemp['maximum'] ?>" min="10" max="40" require></td>
                    </tr>
                </table>
            </td>

            <td>
                <table border="0">
                        <tr>
                            <td><label for="minturb">Nouveau seuil de tubilité minimal : </label></td>
                            <td><input type="number" id="minturb" name="minturb" value="<?php echo$seuilTurb['minimum'] ?>" min="0" max="15" require></td>
                        </tr>
                        <tr>
                            <td><label for="maxturb">Nouveau seuil de tubilité maximal : </label></td>
                            <td><input type="number" id="maxturb" name="maxturb" value="<?php echo$seuilTurb['maximum'] ?>" min="0" max="15" require></td>
                        </tr>
                </table>
            </td>

            <td>
                <table border="0">
                        <tr>
                            <td><label for="minchl">Nouveau seuil de chlore minimal :</label></td>
                            <td><input type="number" id="minchl" name="minchl" value="<?php echo$seuilORP['minimum'] ?>" min="0" max="3" step="0.1" require></br></td>
                        </tr>
                        <tr>
                            <td><label for="maxchl">Nouveau seuil de chlore maximal :</label></td>
                            <td><input type="number" id="maxchl" name="maxchl" value="<?php echo$seuilORP['maximum'] ?>" min="0" max="3" step="0.1" require></br></td>
                        </tr>
                </table>
            </td>
        </tr>
    </table>
        <button type="submit" >Changer les seuils</button>
</form>

<button class="red_button" onclick="window.location.href='index.php?page=alerts'">Annuler</button>
   
</div>