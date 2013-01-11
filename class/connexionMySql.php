<?php

/**
 * Fonction qui retourne la connexion à la base
 * @return resource
 */
function connectMySql()
{
	$NomServeur = $_SERVER['SERVER_NAME'] ;
	$local = ( (substr($NomServeur, 0, 7) == '192.168') || ($NomServeur == 'localhost') || (substr($NomServeur, 0, 3) == '127') );
	
	$host = $local ? 'localhost' : 'localhost';
	$user = $local ? 'gw2cl' : 'gw2cl-db';
	$pass = $local ? 'gw2cl' : 'mpjDGKedBK8pqGuB';
	$database = $local ? 'gw2cl' : 'es_gw2cl';
	$verbose = $local;
	
	$connection = mysql_connect($host, $user, $pass);  
	if ($connection>0)
	{
		//echo "[MySql] Info : Connexion effectu&eacute;e";
		mysql_select_db($database,$connection);
	}
	else
	{
		echo "<div class='erreur'>[MySql] Erreur : Pas de connexion active<div class='erreur'>";
		exit();
	}
  
	return $connection;
}

/**
 * Fonction qui clos la connexion à la base
 * @param resource $connection
 */
function closeConnectMySql($connection) {
	mysql_close($connection); 
	//echo "[MySql] Info : Connexion close";
}
?>