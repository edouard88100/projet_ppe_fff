<?php
session_start(); // On démarre la session
// On inclu les fichiers dont on a besoin
require_once("util/class.pdofff.inc.php");
include("vues/v_entete.php") ;
include("vues/v_bandeau.php") ;
if(!isset($_REQUEST['uc']))
    $uc = 'GestionAdmin';
else
    $uc = $_REQUEST['uc'];

$pdo = Pdofff::getPdofff();
switch($uc)
{
    case 'accueil':
    {include("vues/v_accueil.php");break;}
    case 'GestionAdmin' :
    {include("controleurs/c_GestionAdmin.php");break;}
}
include("vues/v_pied.php") ;