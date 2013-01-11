<?php
Require_once($_SERVER["DOCUMENT_ROOT"]."/GW2CL/class/connexionMySql.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/GW2CL/class/Recette.php");

if(isset($_GET['id']) && !empty($_GET['id'])
	&& isset($_GET['quantite']) && !empty($_GET['quantite']))
{
	$connection = connectMySql();
	
	$nouvelleRecette = getRecette($_GET['id']);
	
	closeConnectMySql($connection);
	
	if(!isset($_GET['quantite']) || empty($_GET['quantite'])) {
		$nouvelleRecette->removeQuantite();
	} else {
		$nouvelleRecette->removeQuantiteByNbr($_GET['quantite']);
	}
	$connection = connectMySql();
			
	$reqSelectRecettes = "SELECT r.id as idRecette FROM recette r where r.indicRecettePere = 'O'";
	$resRecettes = mysql_query($reqSelectRecettes) or die("<div class='erreur'>[MySql] Erreur : ".htmlentities(mysql_error())."</div>");
	
	$i = 0;
	while($row = mysql_fetch_array( $resRecettes )) {
		$nouvelleRecette = getRecette($row['idRecette']);
		$nouvelleRecette->afficheRecette(0, $i);
		$i++;
	}
	
	closeConnectMySql($connection);
}

?>