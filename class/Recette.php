<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/class/connexionMySql.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/class/Objet.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/class/Mobile_Detect.php");

/*
 * Classe permettant de gérer les recettes
 */
class Recette
{
	var $id;
	var $objetACraft;
	var $ingredientsLst = array();
	var $quantiteNecessaire;
	var $quantite;
	var $avancee = 0;
	
	/**
	 * Constructeur
	 * @param unknown_type $idIn
	 * @param unknown_type $objetACraftIn
	 * @param unknown_type $ingredientsLstIn
	 * @param unknown_type $quantiteNecessaireIn
	 * @param unknown_type $quantiteIn
	 * @param unknown_type $avanceeIn
	 */
	function Recette($idIn, $objetACraftIn, $ingredientsLstIn, $quantiteNecessaireIn, $quantiteIn, $avanceeIn) {
		$this->id = $idIn;
		$this->objetACraft = $objetACraftIn;
		$this->ingredientsLst = $ingredientsLstIn;
		$this->quantiteNecessaire = $quantiteNecessaireIn;
		$this->quantite = $quantiteIn;
		$this->avancee = $avanceeIn;
	}
	
	/**
	 * Permet de sauvegarder l'avancée en base
	 * @param unknown_type $idUser
	 */
	function enregistrer($idUser) {
		$connection = connectMySql();
		
		$reqSaveRecettes ="INSERT INTO avanceeuser (idUser, idRecette, quantite, avancee) VALUES (".$idUser.", ".$this->id.", ".$this->quantite.", ".$this->avancee.") 
				ON DUPLICATE KEY UPDATE quantite=".$this->quantite.", avancee=".$this->avancee ;
		
		mysql_query($reqSaveRecettes) or die("<div class='erreur'>[MySql] Erreur : ".htmlentities(mysql_error())."<br/><i>".$req."</i></div>");
		
		closeConnectMySql($connection);
	}
	
	/**
	 * Retourne le nombre d'ingrédients de la recette
	 * @param unknown_type $nbrIngredientsIn
	 * @return number
	 */
	function getNbrTotalIngredients($nbrIngredientsIn) {
		$nbrIngredients = count($this->ingredientsLst)+$nbrIngredientsIn;
		foreach($this->ingredientsLst as $ingredientTmp) {
			$nbrIngredients = $ingredientTmp->getNbrTotalIngredients($nbrIngredients);
		}
		return $nbrIngredients;
	}

	/**
	 * Retourne l'avancée de la recette
	 */
	function getAvancee() {
		$avancee = ($this->quantite*100)/$this->quantiteNecessaire;
		return $avancee;
	}
	
	/**
	 * Permet d'obtenir l'avancée globale de la recette en fonction de tous ses ingrédients
	 * @return number
	 */
	function getAvanceeGlobale() {
		$nombreTotalIngredient = $this->getNbrTotalIngredients(0);
		
		$avanceeTotalIngredient = $this->getAvanceeIngredients(0);
		
		$res = $this->avancee;
		if($nombreTotalIngredient > 0) {
			$res = $avanceeTotalIngredient/$nombreTotalIngredient;
		}
		
		return $res ;
	}
	
	/**
	 * Permet de connaître l'avancée des ingrédients de la recette
	 * @param unknown_type $avanceeIngredientsIn
	 * @return number
	 */
	function getAvanceeIngredients($avanceeIngredientsIn) {
		$avanceeIngredients = $this->avancee + $avanceeIngredientsIn;
		foreach($this->ingredientsLst as $ingredientTmp) {
			$avanceeIngredients = $ingredientTmp->getAvanceeIngredients($avanceeIngredients);
		}
		return $avanceeIngredients;
	}
	
	/**
	 * Permet de savoir si la recette est complète
	 * @return boolean
	 */
	function isComplet() {
		$avancee = $this->getAvancee();
		$isComplet = false;
		if($avancee == "100") {
			//$this->quantite = $this->quantiteNecessaire;
			$isComplet = true;
		}
		
		return $isComplet;
	}
	
	/**
	 * Permet de définir l'avancée de la recette
	 * @param unknown_type $nbr
	 * @param unknown_type $idUser
	 */
	function setQuantite($nbr, $idUser) {
		$this->quantite = $nbr;
		$this->avancee = $this->getAvancee();
		$this->enregistrer($idUser);
		
		if($this->isComplet()){
			foreach($this->ingredientsLst as $ingredientTmp) {
				if(!$ingredientTmp->isComplet()) {
					$ingredientTmp->addQuantiteByNbr($ingredientTmp->quantiteNecessaire, $idUser);
				}
			}
		}
		
		if($this->quantite == 0){
			foreach($this->ingredientsLst as $ingredientTmp) {
				$ingredientTmp->removeQuantiteByNbr($ingredientTmp->quantiteNecessaire, $idUser);
			}
		}
		
		// On vérifie que la recette n'est pas complète et que la quantité n'est pas à 0
		if(!$this->isComplet() && $this->quantite != 0){
			foreach($this->ingredientsLst as $ingredientTmp) {
				if($ingredientTmp->quantiteNecessaire > 1 && $this->quantiteNecessaire != 0) {
					// On calcule le ratio entre le nombre d'éléments nécessaires recette et celui de ses ingrédients
					$ratio = $ingredientTmp->quantiteNecessaire/$this->quantiteNecessaire;
					$quantiteADefinir = $nbr*$ratio;
					$ingredientTmp->setQuantite($quantiteADefinir, $idUser);
				}
			}
		}
	}
	
	/**
	 * Permet d'incrémenter l'avancée de la recette
	 * @param unknown_type $idUser
	 */
	function addQuantite($idUser) {
		$this->addQuantiteByNbr(1, $idUser);
	}
	
	/**
	 * Permet de décrémenter l'avancée de la recette
	 * @param unknown_type $idUser
	 */
	function removeQuantite($idUser) {
		$this->removeQuantiteByNbr(1, $idUser);
	}
	
	/**
	 * Permet de retirer une quantité donnée à l'avancée de la recette
	 * @param unknown_type $nbr
	 * @param unknown_type $idUser
	 */
	function removeQuantiteByNbr($nbr, $idUser) {
		if($this->quantite>=$nbr){
			$this->quantite = $this->quantite-$nbr;
		} else {
			$this->quantite = 0;
		}
		$this->avancee = $this->getAvancee();
		$this->enregistrer($idUser);
		
		if($this->quantite == 0){
			foreach($this->ingredientsLst as $ingredientTmp) {
				$ingredientTmp->removeQuantiteByNbr($ingredientTmp->quantiteNecessaire, $idUser);
			}
		}
		
		// On vérifie que la recette n'est pas complète et que la quantité n'est pas à 0
		if(!$this->isComplet() && $this->quantite != 0){
			foreach($this->ingredientsLst as $ingredientTmp) {
				if($ingredientTmp->quantiteNecessaire > 1 && $this->quantiteNecessaire != 0) {
					// On calcule le ratio entre le nombre d'éléments nécessaires recette et celui de ses ingrédients
					$ratio = $ingredientTmp->quantiteNecessaire/$this->quantiteNecessaire;
					$quantiteASupprimer = $nbr*$ratio;
					$ingredientTmp->removeQuantiteByNbr($quantiteASupprimer, $idUser);
				}
			}
		}
	}
	
	/**
	 * Permet d'ajouter une quantité donnée à l'avancée de la recette
	 * @param unknown_type $nbr
	 * @param unknown_type $idUser
	 */
	function addQuantiteByNbr($nbr, $idUser) {
		$total = $this->quantite+$nbr;
		if($total <= $this->quantiteNecessaire){
			$this->quantite = $total;
		} else {
			$this->quantite = $this->quantiteNecessaire;
		}
		$this->avancee = $this->getAvancee();
		$this->enregistrer($idUser);
		
		if($this->isComplet()){
			foreach($this->ingredientsLst as $ingredientTmp) {
				if(!$ingredientTmp->isComplet()) {
					$ingredientTmp->addQuantiteByNbr($ingredientTmp->quantiteNecessaire, $idUser);
				}
			}
		}
		
		// On vérifie que la recette n'est pas complète et que la quantité n'est pas à 0
		if(!$this->isComplet() && $this->quantite != 0){
			foreach($this->ingredientsLst as $ingredientTmp) {
				if($ingredientTmp->quantiteNecessaire > 1 && $this->quantiteNecessaire != 0) {
					// On calcule le ratio entre le nombre d'éléments nécessaires recette et celui de ses ingrédients
					$ratio = $ingredientTmp->quantiteNecessaire/$this->quantiteNecessaire;
					$quantiteAAjouter = $nbr*$ratio;
					$ingredientTmp->addQuantiteByNbr($quantiteAAjouter, $idUser);
				}
			}
		}
	}
	
	/**
	 * Permet d'afficher une recette et tous ses ingrédients
	 * @param unknown_type $paddingLeft
	 * @param unknown_type $i
	 * @param unknown_type $idUser
	 * @param unknown_type $idRecettePere
	 * @param unknown_type $isRecettePere
	 * @param unknown_type $isPereComplet
	 */
	function afficheRecette($paddingLeft, $i, $idUser, $idRecettePere, $isRecettePere, $isPereComplet) {
		$idRecettePereDiv = $idRecettePere."_".$this->id;
		$nbrIngredient = count($this->ingredientsLst);
		$style = "style=\"padding-left:".$paddingLeft."px;";
		//On initialise l'objet pour la détection du support (mobile ou PC)
		$detect = new Mobile_Detect();
		
		$afficheElement = "\"";
		if($isPereComplet) {
			$afficheElement = "display:none\"";
		}
		$style = $style.$afficheElement;
		
		if($i%2==0){
			echo "<div id='".$idRecettePereDiv."' name='divRecette' ".$style." class=\"recette\">";
		} else {
			echo "<div id='".$idRecettePereDiv."' name='divRecette' ".$style." class=\"recette2\">";
		}
		echo "<div class='boutonsAvanceeDiv'>";
		if(!$isRecettePere) {
			echo "<input id='zoneSaisieQuantite".$this->id."' type='text' value='' size='6' maxlength='6' /> ";
			if (!$detect->isMobile()) {
				echo "<input id='addQuantiteBtn".$this->id."' name='addQuantiteBtn".$this->id."' value='+' title='Ajouter' type='button' onclick='setQuantite(".$this->id.", ".$idUser.", \"A\")' />";
				echo "<input id='removeQuantiteBtn".$this->id."' name='removeQuantiteBtn".$this->id."' value='-' title='Supprimer' type='button' onclick='setQuantite(".$this->id.", ".$idUser.", \"D\")' />";
			}
			echo "<input id='setQuantiteBtn".$this->id."' name='setQuantiteBtn".$this->id."' value='=' title='D&eacute;finir &agrave;' type='button' onclick='setQuantite(".$this->id.", ".$idUser.", \"S\")' />";
		}
		echo "</div>";
		echo "<table><tr>";
		echo "<td>";
		if($nbrIngredient>0) {
			if($this->isComplet()) {
				echo "<input id='btn".$idRecettePereDiv."' type='button' onclick=\"afficheDetail('btn".$idRecettePereDiv."', 'divRecette', '".$idRecettePereDiv."');\" value=' ' class='plusBtn'/>";
			} else {
				echo "<input id='btn".$idRecettePereDiv."' type='button' onclick=\"afficheDetail('btn".$idRecettePereDiv."', 'divRecette', '".$idRecettePereDiv."');\" value=' ' class='moinsBtn'/>";
			}
		} else {
			echo "<div class='aucunBtn'/>";
		}
		echo "</td>";
		echo "<td>";
		$this->objetACraft->afficheImage();
		echo "</td>";
		if (!$detect->isMobile()) {
			echo "<td><b>";
			echo htmlentities($this->objetACraft->nom);
			echo "</b></td>";
		}
		echo "<td>";
		echo "<div class='barreAvancee'>";
		echo "<div class=\"meter-wrap\">";
		
		$avanceeTmp = $this->avancee;
		if($isRecettePere) {
			$avanceeTmp = $this->getAvanceeGlobale();
			$this->avancee = $avanceeTmp;
			$this->enregistrer($idUser);
		}

		if(!$this->isComplet()){
			echo "<div class=\"meter-value\" style=\"background-color: #E38E00; width: ".$avanceeTmp."%;\">";
		} else {
			echo "<div class=\"meter-value2\" style=\"background-color: #E38E00; width: ".$avanceeTmp."%;\">";
		}
		echo "</div>";
		echo "</div>";
		echo "</div>";
		if($this->isComplet()){
			echo "<img class='imgBarreAvancee' src='./images/finBarreOn.png'/>";
		} else {
			echo "<img class='imgBarreAvancee' src='./images/finBarreOff.png'/>";
		}
		echo "</td>";
		echo "<td>";
		echo "<b>".$this->quantite."/".$this->quantiteNecessaire."</b>";
		echo "</td>";
		if (!$detect->isMobile()) {
			echo "<td>";
			echo "(".round($avanceeTmp,1)."%)";
			echo "</td>";
		}
		echo "</tr>";
		echo "</table>";
		echo "</div>";
		$i++;
		foreach($this->ingredientsLst as $ingredientTmp) {			
			$ingredientTmp->afficheRecette($paddingLeft+30, $i, $idUser, $idRecettePereDiv, false, $this->isComplet());
			$i = $i + 1 + $ingredientTmp->getNbrTotalIngredients(0);
		}
	}
}

/**
 * Méthode permettant d'afficher la liste des recettes d'armes légendaires disponibles
 * @param number $idUser
 * @param string $armeSelect
 */
function getRecettePere($idUser, $armeSelect) {
	$connection = connectMySql();

	$reqSelectRecettes = "SELECT r.id as idRecette, o.nom as nomRecette FROM recette r, objet o WHERE r.idObjet = o.id AND r.indicRecettePere = 'O'";
	$resRecettes = mysql_query($reqSelectRecettes) or die("<div class='erreur'>[MySql] Erreur : ".htmlentities(mysql_error())."</div>");

	$i = 0;
	
	echo "<select id='selectRecettePere' name='selectRecettePere'>";
	echo "<option value='-1'> -- Liste des objets</option>";
	while($row =  mysql_fetch_array( $resRecettes )) {
		$isSELECTED = "SELECTED";
		if($armeSelect != $row['idRecette'] ) {
			$isSELECTED = "";
		}
		echo "<option ".$isSELECTED." value='".$row['idRecette']."'>".htmlentities($row['nomRecette'])."</option>";
	}
	echo "</select>";
	
	closeConnectMySql($connection);
}

/**
 * Méthode permettant d'obtenir un recette sans ses ingrédients
 * @param number $idRecette
 * @param number $idUser
 * @return Ambigous <NULL, Recette>
 */
function getRecetteSansIngredient($idRecette, $idUser) {
	$connection = connectMySql();

	$reqSelectRecettes = "SELECT o.id as idObjet, o.nom as nomObjet, o.type as typeObjet, o.image as imageObjet, r.id as idRecette, a.quantite as quantiteRecette, r.quantiteNecessaire as quantiteNecessaire, a.avancee as avanceeRecette FROM objet o, recette r LEFT JOIN avanceeuser a ON a.idRecette = r.id and a.idUser = ".$idUser." where r.idObjet = o.id and r.id = ".$idRecette;
	$resRecettes = mysql_query($reqSelectRecettes) or die("<div class='erreur'>[MySql] Erreur : ".htmlentities(mysql_error())."</div>");
	
	$nouvelleRecette = null;
	while($row = mysql_fetch_array( $resRecettes )) {
		$nouvelObjet = new Objet($row['idObjet'], $row['nomObjet'], $row['imageObjet'], $row['typeObjet']);
		$listeIngredients = array();
		$quantiteRecetteTmp = $row['quantiteRecette'];
		$avanceeTmp = $row['avanceeRecette'];
		if(empty($row['quantiteRecette'])) {
			$quantiteRecetteTmp=0;
		}
		if(empty($row['avanceeRecette'])) {
			$avanceeTmp = 0;
		}
		$nouvelleRecette = new Recette($row['idRecette'], $nouvelObjet, $listeIngredients, $row['quantiteNecessaire'], $quantiteRecetteTmp, $avanceeTmp);
		break;
	}
	return $nouvelleRecette;

	closeConnectMySql($connection);
}

/**
 * Permet d'afficher une recette d'arme légendaire
 * @param number $idUser
 * @param number $idRecettePere
 */
function afficheRecettePere($idUser, $idRecettePere) {
	$connection = connectMySql();
	$nouvelleRecette = getRecette($idRecettePere, $idUser);
	closeConnectMySql($connection);
	if(!empty($nouvelleRecette)) {
		$nouvelleRecette->afficheRecette(0, 0, $idUser, $idRecettePere, true, false);
	}
	
}

/**
 * Permet de récupérer une recette
 * @param number $idRecette
 * @param number $idUser
 * @return Ambigous <NULL, Recette>
 */
function getRecette($idRecette, $idUser) {
	$reqSelectRecettes = "SELECT o.id as idObjet, o.nom as nomObjet, o.type as typeObjet, o.image as imageObjet, r.id as idRecette, a.quantite as quantiteRecette, r.quantiteNecessaire as quantiteNecessaire, a.avancee as avanceeRecette FROM objet o, recette r LEFT JOIN avanceeuser a ON a.idRecette = r.id and a.idUser = ".$idUser." where r.idObjet = o.id and r.id = ".$idRecette;
	$resRecettes = mysql_query($reqSelectRecettes) or die("<div class='erreur'>[MySql] Erreur : ".htmlentities(mysql_error())."</div>");
	
	$nouvelleRecette = null;
	while($row = mysql_fetch_array( $resRecettes )) {
		$nouvelObjet = new Objet($row['idObjet'], $row['nomObjet'], $row['imageObjet'], $row['typeObjet']);
		$listeIngredients = getIngredients($idRecette, $idUser);
		$quantiteRecetteTmp = $row['quantiteRecette'];
		$avanceeTmp = $row['avanceeRecette'];
		if(empty($row['quantiteRecette'])) {
			$quantiteRecetteTmp=0;
		}
		if(empty($row['avanceeRecette'])) {
			$avanceeTmp = 0;
		}
		$nouvelleRecette = new Recette($row['idRecette'], $nouvelObjet, $listeIngredients, $row['quantiteNecessaire'], $quantiteRecetteTmp, $avanceeTmp);
		break;
	}
	return $nouvelleRecette;
}

/**
 * Permet de récupérer les ingrédients d'un recette
 * @param number $idRecette
 * @param number $idUser
 */
function getIngredients($idRecette, $idUser) {
	$listeIngredients = array();
	$reqSelectIngredients = "SELECT r.id as idRecette FROM recette r, objet o, ingredients i where r.idObjet = o.id and r.id = i.idRecette and i.idRecettePere = ".$idRecette;
	$resIngredients = mysql_query($reqSelectIngredients) or die("<div class='erreur'>[MySql] Erreur : ".htmlentities(mysql_error())."</div>");
	while($rowIngre = mysql_fetch_array( $resIngredients )) {
		$nouvelIngredient = getRecette($rowIngre['idRecette'], $idUser);
		if(!empty($nouvelIngredient)) {
			$listeIngredients[] = $nouvelIngredient;
		}
	}
	
	return $listeIngredients;
}
?>