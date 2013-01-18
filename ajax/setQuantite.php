<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/class/connexionMySql.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/class/Recette.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/class/session.php");

//On démarre la session
session_start();

if(isset($_GET['id']) && !empty($_GET['id']) && isset($_GET['user']) && !empty($_GET['user']) && isset($_GET['type']) && !empty($_GET['type']) && isset($_GET['quantite']) ) {
	$connection = connectMySql();
	$nouvelleRecette = getRecette($_GET['id'], $_GET['user']);
	closeConnectMySql($connection);
	
	switch ($_GET['type']) {
		case "S":
			$nouvelleRecette->setQuantite($_GET['quantite'], $_GET['user']);
			break;
		case "A":
			if(empty($_GET['quantite'])) {
				$nouvelleRecette->addQuantite($_GET['user']);
			} else {
				$nouvelleRecette->addQuantiteByNbr($_GET['quantite'], $_GET['user']);
			}
			break;
		case "D":
			if(!isset($_GET['quantite']) || empty($_GET['quantite'])) {
				$nouvelleRecette->removeQuantite($_GET['user']);
			} else {
				$nouvelleRecette->removeQuantiteByNbr($_GET['quantite'], $_GET['user']);
			}
			break;
	}
}

if(isset($_SESSION['recettePereSelect']) && $_SESSION['recettePereSelect'] != "-1") {
	afficheRecettePere($_GET['user'], $_SESSION['recettePereSelect']);
} else {
	//deconnexion();
	echo  $_SESSION['userLog']->id;
}

?>