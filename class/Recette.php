<?php
Require_once($_SERVER["DOCUMENT_ROOT"]."/GW2CL/class/connexionMySql.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/GW2CL/class/Objet.php");

class Recette
{
	var $id;
	var $objetACraft;
	var $ingredientsLst = array();
	var $quantiteNecessaire;
	var $quantite;
	
	function Recette($idIn, $objetACraftIn, $ingredientsLstIn, $quantiteNecessaireIn, $quantiteIn) {
		$this->id = $idIn;
		$this->objetACraft = $objetACraftIn;
		$this->ingredientsLst = $ingredientsLstIn;
		$this->quantiteNecessaire = $quantiteNecessaireIn;
		$this->quantite = $quantiteIn;
	}
	
	function enregistrer() {
		$connection = connectMySql();
		
		$reqSaveRecettes = "UPDATE recette r set quantite = ".$this->quantite." where r.id = ".$this->id;
		mysql_query($reqSaveRecettes) or die("<div class='erreur'>[MySql] Erreur : ".htmlentities(mysql_error())."<br/><i>".$req."</i></div>");
		
		closeConnectMySql($connection);
	}
	
	function getNbrTotalIngredients($nbrIngredientsIn) {
		$nbrIngredients = count($this->ingredientsLst)+$nbrIngredientsIn;
		foreach($this->ingredientsLst as $ingredientTmp) {
			$nbrIngredients = $ingredientTmp->getNbrTotalIngredients($nbrIngredients);
		}
		return $nbrIngredients;
	}
	
	function getAvancee() {
		$avancee = ($this->quantite*100)/$this->quantiteNecessaire;
		return $avancee;
	}
	
	function isComplet() {
		$avancee = ($this->quantite*100)/$this->quantiteNecessaire;
		$isComplet = false;
		if($avancee == "100") {
			$isComplet = true;
		}
		
		return $isComplet;
	}
	
	function addQuantite(){
		$this->addQuantiteByNbr(1);
	}
	
	function removeQuantite(){
		$this->removeQuantiteByNbr(1);
	}
	
	function removeQuantiteByNbr($nbr){
		if($this->quantite>=$nbr){
			$this->quantite = $this->quantite-$nbr;
		} else {
			$this->quantite = 0;
		}
		
		$this->enregistrer();
	}
	
	function addQuantiteByNbr($nbr) {
		$total = $this->quantite+$nbr;
		if($total <= $this->quantiteNecessaire){
			$this->quantite = $total;
		} else {
			$this->quantite = $this->quantiteNecessaire;
		}
		
		$this->enregistrer();
	}
	
	function afficheRecette($paddingLeft, $i) {
		$nbrIngredient = count($this->ingredientsLst);
		
		if($i%2==0){
			echo "<ul><li>";
			echo "<div class=\"recette\">";
		} else {
			echo "<ul><li>";
			echo "<div class=\"recette2\">";
		}
		echo "<table><tr><td>";
		if($nbrIngredient>0) {
			echo "<input type='button' value=' ' class='moinsBtn'/>";
		} 
		echo "</td><td>";
		echo htmlentities($this->objetACraft->nom);
		echo "</td>";
		echo "<td>";
		echo "<div class=\"meter-wrap\">";
		if(!$this->isComplet()){
			echo "<div class=\"meter-value\" style=\"background-color: #FF8000; width: ".$this->getAvancee()."%;\">";
		} else {
			echo "<div class=\"meter-value2\" style=\"background-color: #FF8000; width: ".$this->getAvancee()."%;\">";
		}
		echo "</div>";
		echo "</div>";
		echo "</td><td>";
		echo "<b>".$this->quantite."/".$this->quantiteNecessaire."</b>";
		echo "</td><td>";
		echo "(".$this->getAvancee()."%)";
		echo "</td>";
		echo "<td>";
		echo "<input id='zoneSaisieQuantite".$this->id."' type='text' value='' size='6' maxlength='6' />";
		echo "<input id='addQuantiteBtn".$this->id."' name='addQuantiteBtn".$this->id."' value='+' title='Add' type='button' onclick='ajoutQuantite(".$this->id.")' value=' ' />";
		echo "<input name='removeQuantiteBtn".$this->id."' value='-' title='Remove' type='button' onclick='suppresionQuantite(".$this->id.")'  value=' ' />";
		echo "</td></tr></table>";
		echo "</div>";
		$i++;
		foreach($this->ingredientsLst as $ingredientTmp) {
			$ingredientTmp->afficheRecette($paddingLeft+30, $i);
			$i++;
		}
		echo "</li></ul>";
	}
}

function getRecette($idRecette) {
	$reqSelectRecettes = "SELECT o.id as idObjet, o.nom as nomObjet, o.type as typeObjet, o.image as imageObjet, r.id as idRecette, r.quantite as quantiteRecette, r.quantiteNecessaire as quantiteNecessaire FROM recette r, objet o where r.idObjet = o.id and r.id = ".$idRecette;
	$resRecettes = mysql_query($reqSelectRecettes) or die("<div class='erreur'>[MySql] Erreur : ".htmlentities(mysql_error())."</div>");
	
	while($row = mysql_fetch_array( $resRecettes )) {
		$nouvelObjet = new Objet($row['idObjet'], $row['nomObjet'], $row['imageObjet'], $row['typeObjet']);
		$listeIngredients = getIngredients($idRecette);
		$nouvelleRecette = new Recette($row['idRecette'], $nouvelObjet, $listeIngredients, $row['quantiteNecessaire'], $row['quantiteRecette']);
		break;
	}
	return $nouvelleRecette;
}
	
function getIngredients($idRecette) {
	$listeIngredients = array();
	$reqSelectIngredients = "SELECT r.id as idRecette FROM recette r, objet o, ingredients i where r.idObjet = o.id and r.id = i.idRecette and i.idRecettePere = ".$idRecette;
	$resIngredients = mysql_query($reqSelectIngredients) or die("<div class='erreur'>[MySql] Erreur : ".htmlentities(mysql_error())."</div>");
	while($rowIngre = mysql_fetch_array( $resIngredients )) {
		$nouvelIngredient = getRecette($rowIngre['idRecette']);
		$listeIngredients[] = $nouvelIngredient;
	}
	
	return $listeIngredients;
}
?>