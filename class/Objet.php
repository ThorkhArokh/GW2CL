<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/class/connexionMySql.php");

class Objet
{
	var $id;
	var $nom;
	var $type;
	var $img;
	
	function Objet($idIn, $nomIn, $imgIn, $typeIn)
	{
		$this->id = $idIn;
		$this->nom = $nomIn;
		$this->img = $imgIn;
		$this->type = $typeIn;
	}
	
	function afficheImage() {
		$image = "<img class='iconObjet' src='./images/icons/defaut.png' />";
		if(!empty($this->img)) {
			$image = "<img class='iconObjet' src='./images/icons/".$this->img."' />";
		}
		if(!empty($this->type)) {
			echo "<a href=\"".$this->type."\">".$image."</a>";
		} else {
			echo $image;
		}
	}
}

function getObjetById($idObjetIn) {
	$objetRes = null;
	
	$connection = connectMySql();
	
	$reqObjet = "select * from objet where id=".$idObjetIn;
	$resObjet = mysql_query($reqObjet) or die("<div class='erreur'>[MySql] Erreur : ".htmlentities(mysql_error())."</div>");
	
	while($row = mysql_fetch_array( $resObjet )) {
		$objetRes = new Objet($row['id'], $row['nom'], $row['image'], $row['type']);
	}
	
	closeConnectMySql($connection);
	
	return $objetRes;
}
?>