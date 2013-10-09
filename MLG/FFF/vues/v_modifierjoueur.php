<form method="POST" action="index.php?uc=GestionAdmin&action=ModifierJoueur">
    <fieldset>
        <p>
            <label for="nom">Nom :</label>
            <input id="nom" type="text" name="nom" value="<?php echo $nom ?>" size="10" maxlength="10" style=" width: auto">
        <p>
            <label for="prenom">Prenom :</label>
            <input id="prenom" type="text" name="prenom" value="<?php echo $prenom ?>" size="10" maxlength="10">
        </p>
        <p>
            <label for="datenaiss">Date de Naissance :</label>
			<table class="ds_box" cellpadding="0" cellspacing="0" id="ds_conclass" style="display: none;">
			<tr>
				<td id="ds_calclass"></td>
			</tr>
			</table>
            <input id="datenaiss" type="text" name="datenaiss" value="<?php echo $datenaiss ?>" onclick="ds_sh(this);" size="10" maxlength="10">
        </p>
        <p>
            <!----On fais une liste déroulante des categories---->
            <label for="idcat">Categorie :</label>
            <select name='idcat'>
                <?php
                foreach( $LesCategories as $UnCategorie){
                    $idCat = $UnCategorie['idcat'];
                    $nomCat = $UnCategorie['nomcategories'];
                    echo "<option";
                    if ($oldidcat == $idCat){ // On test l'id categorie du joueur et les id des categories pour pouvoir mettre par defaut lla categorie du joueur
                        echo " selected=\"selected\"";
                    }
                    echo " value=\"".$idCat."\">". $nomCat."</option>";
                }?>

            </select><br />
        </p>
        <p>
            <label for="nlicence">N°Licence :</label>
            <input id="nlicence" type="text" name="nlicence" value="<?php echo $nlicence ?>" size="10" maxlength="10">
        </p>
        <p><!-----Liste deroulante des clubs---->
            <label for="idc">Club :</label>
            <select name='idc'>
                <?php
                foreach( $LesClubs as $unClub)
                {
                    $idc = $unClub['idc'];
                    $club = $unClub['nom'];
                    echo "<option"; // On test l'id du club du joueur et les id des clubs pour pouvoir mettre par defaut le club du joueur
                    if ($idc == $oldidc){
                        echo " selected=\"selected\"";
                    }
                    echo " value=\"".$idc."\">". $club."</option>";
                }

                ?>
            </select><br />
            <input id="idjoueur" type="hidden" name="idjoueur" value="<?php echo $idj ?>">
        </p>
        <p>
            <input type="submit" value="Valider" name="valider">
            <input type="reset" value="Annuler" name="annuler">
        </p>
        </p>
    </fieldset>
</form>