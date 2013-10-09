<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Thib'
 * Date: 13/09/13
 * Time: 14:02
 * To change this template use File | Settings | File Templates.
 */
class Pdofff
{
    private static $serveur='mysql:host=localhost';
    private static $bdd='dbname=mlg';
    private static $user='root' ;
    private static $mdp='' ;
    private static $monPdo;
    private static $monPdofff = null;
    /**
     * Constructeur privé, crée l'instance de PDO qui sera sollicitée
     * pour toutes les méthodes de la classe
     */
    private function __construct()
    {
        Pdofff::$monPdo = new PDO(Pdofff::$serveur.';'.Pdofff::$bdd, Pdofff::$user, Pdofff::$mdp);
        Pdofff::$monPdo->query("SET CHARACTER SET utf8");
    }
    public function _destruct(){
        Pdofff::$monPdo = null;
    }
    /**
     * Fonction statique qui crée l'unique instance de la classe
     *
     */
    public  static function getPdofff()
    {
        if(Pdofff::$monPdofff == null)
        {
            Pdofff::$monPdofff= new Pdofff();
        }
        return Pdofff::$monPdofff;
    }
    /*
    * Test le couple login/mdp et retourne 0 si le couple n'existe pas et 1 si il existe
    * @Parametres : login et mdp
    */
    public function testLogAdmin($l, $p)
    {
        $req = "select count(*) from compte where user = '".$l."' and mdp ='".$p."'";
        $res = Pdofff::$monPdo->query($req);
        $leResu = $res->fetch();
        // test echo var_dump($leResu);
        return $leResu;
    }
    /**
     * Retourne tous les clubs avec un tableau
     *
     * @return $lesClubs
     */
    public function getLesClubs()
    {
        $req = "select * from clubs";
        $res = Pdofff::$monPdo->query($req);
        $lesClubs = $res->fetchAll();
        return $lesClubs;
    }
    /*
    * Prend en parametre le nom, ville, nom et prenom du dirigeant pour crée un nouveau club
    */
    public function ajouterclub ($nom, $ville, $nomdirigeant, $prenomdirigeant)
    {
        $req = "INSERT INTO clubs (idc, nom, ville, nomdirigeant, prenomdirigeant) VALUES ('','".$nom."', '".$ville."', '$nomdirigeant', '".$prenomdirigeant."');";
        Pdofff::$monPdo->query($req);
    }
    /*
    * Prend en parametre les nouvelles informations du club et met à jour la table
    */
    public function modifierclub($idc, $nom, $ville, $nomdirigeant, $prenomdirigeant)
    {
        $req = "UPDATE clubs SET nom='".$nom."', ville='".$ville."', nomdirigeant = '".$nomdirigeant."', prenomdirigeant='".$prenomdirigeant."' where idc='".$idc."';";
        Pdofff::$monPdo->exec($req);
    }
    /*
     * Supprime la ligne du club dans la table clubs
     */
    public function SupprimerClub($idc)
    {
        $req = "DELETE from clubs where idc = ".$idc.";";
        Pdofff::$monPdo->exec($req);
    }
    /*
    * Retourne les infos du club mis en parametre afin de faire une fiche du club
    */
    public function getFicheClub($idc)
    {
        $req = "select * from clubs where idc='".$idc."'";
        $res = Pdofff::$monPdo->query($req);
        $LeClub = $res->fetchAll();
        return $LeClub;
    }
    /*
    * Retourne tous les joueurs
    */
    public function getLesJoueurs()
    {
        $req = " select * from joueurs";
        $res = Pdofff::$monPdo->query($req);
        $lesLignes = $res->fetchAll();
        return $lesLignes;
    }
    /*
    * Recupere la liste des joueurs d'un club passé en parametre
    */
    public function getLesJoueursDeClub($idc)
    {
        $req = " select * from joueurs WHERE idc='".$idc."'";
        $res = Pdofff::$monPdo->query($req);
        $lesJoueurs = $res->fetchAll();
        return $lesJoueurs;
    }
    /*
    * Ajoute un joueur dans la table joueur avec les informations passé en parametre puis l'inscris dans la table inscrire pour l'historique
    */
    public function ajouterjoueur ($nom,$prenom, $datenaiss, $nlicence, $idc, $idcat)
    {
        $req = "INSERT INTO joueurs (idj, nom, prenom, datenaiss, nlicence, idc, idcat) VALUES ('','".$nom."', '".$prenom."', '$datenaiss', '".$nlicence."','".$idc."','".$idcat."');";
        Pdofff::$monPdo->query($req);
        $req = "INSERT INTO inscrire (datei, idc, idj) VALUES ('".date("Y-n-j")."', '".$idc."', (SELECT MAX(idj) FROM joueurs));"; // On utilise ici la fonction date() de php pour récuperer la date du jour
        Pdofff::$monPdo->query($req);
    }
    /*
    * Modifier un joueur
     *  Recupere l'id du club du joueur pour le comparer avec le club entrer dans la vue modifier joueur afin de l'inscrire ou non dans l'historique du joueur
     *  Met à jour la ligne du joueur dans la table joueurs
    */
    public function modifierjoueur($idj, $nom,$prenom, $datenaiss, $nlicence, $idcat, $idc)
    {
        $req = "SELECT idc FROM joueurs WHERE idj='".$idj."';";
        $res = Pdofff::$monPdo->query($req);
        $oldidc = $res->fetchAll();
        $req = "UPDATE joueurs SET nom='".$nom."', prenom='".$prenom."', datenaiss='".$datenaiss."', nlicence='".$nlicence."', idcat='".$idcat."' where idj='".$idj."';";
        Pdofff::$monPdo->exec($req);
        if ($oldidc[0]['idc'] != $idc){
            $req = "INSERT INTO inscrire (datei, idc, idj) VALUES ('".date("Y-n-j")."', '".$idc."', '".$idj."');"; //Utilisation de date()
            Pdofff::$monPdo->exec($req);
        }
    }
    /*
    * Recupere les informations du joueur passé en parametre
    */
    public function getFicheJoueur($idj)
    {
        $req = "select * from joueurs where idj='".$idj."'";
        $res = Pdofff::$monPdo->query($req);
        $LeJoueur = $res->fetchAll();
        return $LeJoueur;
    }
    /*
    * Recupere l'historique dans la table inscrire, du joueur passé en parametre
    */
    public function getHistorique($idj){
        $req = "SELECT datei, nom FROM inscrire, clubs WHERE inscrire.idc = clubs.idc AND idj='".$idj."' ORDER BY datei";
        $res = Pdofff::$monPdo->query($req);
        $Historique = $res->fetchAll();
        return $Historique;
    }
    /*
    * Recupere l'id et le nom des categories
    */
    public function getLesCategories(){
        $req = "SELECT idcat, nomcategories FROM categories;";
        $res = Pdofff::$monPdo->query($req);
        $Categories = $res->fetchAll();
        return $Categories;
    }
    /*
    * Retourne un tableau contenant la liste des joueurs avec une recherche par categorie passé en parametre
    */
    public function resultRecherche($idcat){
        $req = "SELECT * FROM joueurs WHERE idcat ='".$idcat."';";
        $res = Pdofff::$monPdo->query($req);
        $lesJoueurs = $res->fetchAll();
        return $lesJoueurs;
    }
}