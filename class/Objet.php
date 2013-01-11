<?php
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
}
?>