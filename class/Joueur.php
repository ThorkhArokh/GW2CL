<?php
class Joueur
{
	var $id;
	var $nom;
	var $login;
	var $mdp;

	function Objet($idIn, $nomIn, $loginIn, $mdpIn)
	{
		$this->id = $idIn;
		$this->nom = $nomIn;
		$this->login = $loginIn;
		$this->mdp = $mdpIn;
	}
}