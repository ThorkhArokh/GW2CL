<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/class/connexionMySql.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/class/Recette.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/class/session.php");

if(isset($_GET['armeId']) && !empty($_GET['armeId']) && isset($_GET['idUser']) && !empty($_GET['idUser']) ) {
	$connection = connectMySql();
	
	$reqSaveUser ="INSERT INTO user (id, login, armeChoisie) VALUES (".$_GET['idUser'].", '', ".$_GET['armeId'].") 
				ON DUPLICATE KEY UPDATE armeChoisie=".$_GET['armeId'];	
	mysql_query($reqSaveUser) or die("<div class='erreur'>[MySql] Erreur : ".htmlentities(mysql_error())."<br/><i>".$req."</i></div>");
	
	// On insert l'avancee si ce n'est pas déjà fait
	$reqSaveRecettes ="INSERT ignore INTO avanceeuser (idUser, idRecette, quantite, avancee) VALUES (".$_GET['idUser'].", ".$_GET['armeId'].", 0, 0)";
	mysql_query($reqSaveRecettes) or die("<div class='erreur'>[MySql] Erreur : ".htmlentities(mysql_error())."<br/><i>".$req."</i></div>");
		
	closeConnectMySql($connection);
}

?>