<?php
foreach( $LeJoueur as $unJoueur) // On recupere les infos du joueur dans une variable
{
    $idc = $unJoueur['idc'];
    $nom = $unJoueur['nom'];
    $prenom = $unJoueur['prenom'];
    $idcat = $unJoueur['idcat'];
    $idj = $unJoueur['idj'];
    $datenaiss = $unJoueur['datenaiss'];
    $nlicence = $unJoueur['nlicence'];
    ?>
    <h2>Fiche du Joueur</h2>
    <?php
    echo "<p>Nom du Joueur : " . $nom."</p>";
    echo "<p>Prénom du joueur : " . $prenom."</p>";
    echo "<p>Date de naissance : " . $datenaiss."</p>";
    if ($idcat == 1) // Si l'id de la categorie est 1 on affiche seniors sinon -18, qui correspond a nos deux categories
    {
        echo "<p>Catégorie : Seniors</p>";
    }else{
        echo "<p>Catégorie : -18ans</p>";
    }
    echo "<p>N° Licence : " . $nlicence."</p>";
    echo "Historique du Joueur : ";
    foreach( $Historique as $unHistorique) // On affiche l'historique du joueur
    {
        $date = $unHistorique['datei'];
        $nomclub = $unHistorique['nom'];

        echo "<p>" . $date ." ". $nomclub . "</p>";
    }
    echo "<a href=index.php?uc=GestionAdmin&club=$idc&action=FicheClub>Club du Joueur</a></p>";
    echo "<a href=index.php?uc=GestionAdmin&action=ModifierJoueur&idjoueur=$idj> <input type='submit' value='Modifier' style='width: 200px'/></a>";
}
?>