<?php
if (isset($_REQUEST['action'])){ // Si il n'y a pas d'action renseigné, on redirige vers la connexion
    $action = $_REQUEST['action'];
} else {
    $action = 'connexion';
}
if (!isset($_SESSION['admin'])){    // Si on n'est pas connecté, on redirige vers la connexion
    $action = 'connexion';
}
switch($action){
    case 'connexion':
    {
        if(isset($_POST['login']) && (isset($_POST['pass']))){ // Si le login et mdp sont rentré, on récupere les données dans 2 variables
            $log = $_POST['login'];
            $mdp = $_POST['pass'];
            //il faut se connecter à la BD
            $resu = $pdo->testLogAdmin($log, $mdp); // On test le couple login/mdp

            if($resu[0] == 0){
                header("Location: /fff/index.php?uc=GestionAdmin&action=connexion"); // Si le couple login/mdp est faux on redirige vers connexion
            }else{
                $_SESSION['admin']=1; // Sinon on est connecté avec une variable session
                header("Location: /fff/index.php?uc=accueil");
            }
        }else{
            include("vues/v_connexion.php");
        }
        break;
    }
    case 'deconnexion': // On enleve toutes les variables de session
    {
        session_destroy();
        header("Location: /fff/index.php?uc=GestionAdmin&action=connexion");
        break;
    }
    case 'VoirClubs': // on affiche tout les clubs dans la vue clubs grâce à la fonction getLesClubs
    {
        $LesClubs = $pdo->getLesClubs();
        include("vues/v_clubs.php");
        break;
    }
    case 'VoirJoueurs': // on affiche tout les joueurs dans la vue joueurs
    {
         $lesJoueurs = $pdo->getLesJoueurs();
         $LesCategories = $pdo->getLesCategories(); // On recupere les categories pour le champ rechercher
         include ("vues/v_joueurs.php");
        break;
    }
    case 'Rechercher': // On fais une recherche par categorie grâce à la fonction resultRecherche
    {
        $idcat = $_REQUEST['idcat'];
        $lesJoueurs = $pdo->resultRecherche($idcat);
        $LesCategories = $pdo->getLesCategories();
        include ("vues/v_joueurs.php");
        break;
    }
    case 'VoirJoueursDuClub': // On affiche les joueurs d'un club précis passé en parametre dans la fonction getLesJoueursDeClub
    {
        $idc = $_REQUEST['club'];
        $lesJoueurs = $pdo->getLesJoueursDeClub($idc);
        $LesCategories = $pdo->getLesCategories();
        include("vues/v_joueurs.php");
        break;
    }
    case 'AjouterClub': // On ajoute un club avec les données passé dans la vue clubs avec la fonction ajouterclub et on affiche la liste des clubs
    {
        if(isset($_REQUEST['nom']) && (isset($_REQUEST['ville']) && (isset($_REQUEST['nomdirigeant']) && (isset($_REQUEST['prenomdirigeant']))))){
            $nom = $_REQUEST['nom'];
            $ville = $_REQUEST['ville'];
            $nomdirigeant = $_REQUEST['nomdirigeant'];
            $prenomdirigeant = $_REQUEST['prenomdirigeant'];
            $pdo->ajouterclub ($nom, $ville, $nomdirigeant, $prenomdirigeant);
            $LesClubs = $pdo->getLesClubs();
            include("vues/v_clubs.php");
        }else{
            include ("vues/v_ajouterclub.php");
        }
        break;
    }
    case 'ModifierClub': /* On affiche les cases pour modifier le club avec les champ actuel et dès qu'il y a une modification,
     On modifie un club grace a son id et la fonction modifierclub */
    {
        $idc = $_REQUEST['club'];
        if(isset($_REQUEST['nom']) && (isset($_REQUEST['ville']) && (isset($_REQUEST['nomdirigeant']) && (isset($_REQUEST['prenomdirigeant']))))){
            $nom = $_REQUEST['nom'];
            $ville = $_REQUEST['ville'];
            $nomdirigeant = $_REQUEST['nomdirigeant'];
            $prenomdirigeant = $_REQUEST['prenomdirigeant'];
            $pdo->modifierclub ($idc, $nom, $ville, $nomdirigeant, $prenomdirigeant);
            $LesClubs = $pdo->getLesClubs();
            include ("vues/v_clubs.php");
        }else{
            $LeClub = $pdo->getFicheClub ($idc);
            $nom= $LeClub[0]['nom'];
            $ville = $LeClub[0]['ville'];
            $nomdirigeant = $LeClub[0]['nomdirigeant'];
            $prenomdirigeant = $LeClub[0]['prenomdirigeant'];
            include("vues/v_modifierclub.php");
        }
        break;
    }
   case 'SupprimerClub' : // On supprime un club avec la fonction SupprimerClub
   {
       $idc = $_REQUEST['idc'];
       $pdo->SupprimerClub($idc);
       $LesClubs = $pdo->getLesClubs();
       include ("vues/v_clubs.php");
       break;
   }

   case 'AjouterJoueur': // On ajoute un joueur avec les données passés dans la vue clubs avec la fonction ajouterjoueur et on affiche la liste des joueurs
   {
       if(isset($_REQUEST['nom']) && (isset($_REQUEST['prenom']) && (isset($_REQUEST['idcat'])  && (isset($_REQUEST['idc']) && (isset($_REQUEST['nlicence'])&& (isset($_REQUEST['datenaiss']))))))){
           $nom = $_REQUEST['nom'];
           $prenom = $_REQUEST['prenom'];
           $datenaiss = $_REQUEST['datenaiss'];
           $idcat = $_REQUEST['idcat'];
           $nlicence =$_REQUEST['nlicence'];
           $idc = $_REQUEST['idc'];
           $pdo->ajouterjoueur ($nom, $prenom, $datenaiss, $nlicence, $idc, $idcat);
           $lesJoueurs = $pdo->getLesJoueurs();
           $LesCategories = $pdo->getLesCategories();
           include("vues/v_joueurs.php");
       }else{
           $LesCategories = $pdo->getLesCategories(); // si les champs ne sont pas renseigné ou que l'on vient juste d'arriver sur ajouter joueur on affiche la vue ajouterjoueur
           $LesClubs = $pdo->getLesClubs();
           include ("vues/v_ajouterjoueur.php");
       }
       break;
   }
    case 'ModifierJoueur': /* On affiche les cases pour modifier le joueur avec les champ actuel et dès qu'il y a une modification,
     On modifie un joueur grace a son id et la fonction modifierjoueur */
    {
        $idj = $_REQUEST['idjoueur'];
        if(isset($_REQUEST['nom']) && (isset($_REQUEST['prenom']) && (isset($_REQUEST['datenaiss']) && (isset($_REQUEST['nlicence']))))){
            $nom = $_REQUEST['nom'];
            $prenom = $_REQUEST['prenom'];
            $datenaiss = $_REQUEST['datenaiss'];
            $nlicence = $_REQUEST['nlicence'];
            $idcat = $_REQUEST['idcat'];
            $idc = $_REQUEST['idc'];
            $pdo->modifierjoueur ($idj, $nom, $prenom, $datenaiss, $nlicence, $idcat, $idc);
            $LeJoueur = $pdo->getFicheJoueur($idj);
            $Historique = $pdo->getHistorique($idj); // On recupere l'historique du joueur
            include ("vues/v_joueurfiche.php");
        }else{
            $unJoueur = $pdo->getFicheJoueur($idj);
            $nom = $unJoueur[0]['nom'];
            $prenom = $unJoueur[0]['prenom'];
            $oldidcat = $unJoueur[0]['idcat'];
            $datenaiss = $unJoueur[0]['datenaiss'];
            $nlicence = $unJoueur[0]['nlicence'];
            $oldidc = $unJoueur[0]['idc'];
            $LesClubs = $pdo->getLesClubs(); // On recupere les clubs pour afficher une liste deroulante
            $LesCategories = $pdo->getLesCategories(); // On recupere les categories pour afficher une liste deroulante
            include("vues/v_modifierjoueur.php");
            break;
        }
        break;
    }
    case 'FicheClub': //On affiche la fiche du club
    {
        $idc = $_REQUEST['club'];
        $LeClub = $pdo->getFicheClub($idc);
        include("vues/v_clubfiche.php");
        break;
    }
    case 'FicheJoueur': // On affiche la fiche du joueur
    {
        $idjoueur = $_REQUEST['idjoueur'];
        $LeJoueur = $pdo->getFicheJoueur($idjoueur);
        $Historique = $pdo->getHistorique($idjoueur);
        include("vues/v_joueurfiche.php");
        break;
    }
}