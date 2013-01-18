<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/class/connexionMySql.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/class/Recette.php");

class User
{
	var $id;
	var $login;
	var $armeChoisie = 0;

	function User($idIn, $loginIn) {
		$this->id = $idIn;
		$this->login = $loginIn;
	}
	
	function setArmeChoisie($armeChoisieIn) {
		$this->armeChoisie = $armeChoisieIn;
	}
	
	function modifierLogin($nomUserIn) {
		$this->login = $nomUserIn;
		
		$connection = connectMySql();
		
		$reqSaveUser ="INSERT INTO user (id, login, armeChoisie) VALUES (".$this->id.", '".$this->login."', ".$this->armeChoisie.") 
		ON DUPLICATE KEY UPDATE login='".$nomUserIn."'";	
		mysql_query($reqSaveUser) or die("<div class='erreur'>[MySql] Erreur : ".htmlentities(mysql_error())."<br/><i>".$reqSaveUser."</i></div>");
		
		closeConnectMySql($connection);
	}
}

/**
 * Fonction qui récupère un utilisateur selon l'identifiant donné
 * @param int $idUser
 * @return User|NULL
 */
function getUser($idUser) {
	$connection = connectMySql();
	
	$sql = 'SELECT count(*),u.id, u.login, u.armeChoisie FROM user u WHERE u.id='.$idUser;
	$req = mysql_query($sql) or die("<div class='erreur'>Erreur SQL !<br />".$sql."<br />".mysql_error()."</div>");
	$data = mysql_fetch_array($req);
	mysql_free_result($req);
	
	closeConnectMySql($connection);
	
	if ($data[0] == 1) {
		$user = new User($data[1],$data[2]);
		$user->setArmeChoisie($data[3]);
		return $user;	
	} else {
		return null;
	}
}

function getUsersAvancement() {
	$connection = connectMySql();
	$sql = 'SELECT u.id as idUser, u.login as login, o.nom as nomObjet, r.id as idRecette FROM user u, objet o, recette r, avanceeuser a WHERE r.id = u.armeChoisie AND r.idObjet = o.id and a.idUser = u.id and a.idRecette = r.id order by a.avancee DESC';
	$resRecettes = mysql_query($sql) or die("<div class='erreur'>[MySql] Erreur : ".htmlentities(mysql_error())."</div>");
	
	$i=0;
	while($row = mysql_fetch_array( $resRecettes )) {
		$recettePrincipale = getRecette($row['idRecette'], $row['idUser']);
		$idRecettePereDiv = $i."_".$recettePrincipale->id;
		if($i%2==0){
			echo "<div id='".$idRecettePereDiv."' name='divRecette' class=\"recette\">";
		} else {
			echo "<div id='".$idRecettePereDiv."' name='divRecette' class=\"recette2\">";
		}
		echo "<table><tr><td>";
		echo "<b>".htmlentities($row['login'])." : </b>";
		echo "</td>";
		echo "<td>";
		$image = "<img class='iconObjet' src='./images/icons/defaut.png' />";
		if(!empty($recettePrincipale->objetACraft->img)) {
			$image = "<img class='iconObjet' src='./images/icons/".$recettePrincipale->objetACraft->img."' />";
		}
		if(!empty($recettePrincipale->objetACraft->type)) {
			echo "<a href=\"".$recettePrincipale->objetACraft->type."\">".$image."</a>";
		} else {
			echo $image;
		}
		
		echo "</td>";
		echo "<td>";
		echo "<div class='barreAvancee'>";
		echo "<div class=\"meter-wrap\">";
		
		if(!$recettePrincipale->isComplet()){
			echo "<div class=\"meter-value\" style=\"background-color: #E38E00; width: ".$recettePrincipale->avancee."%;\">";
		} else {
			echo "<div class=\"meter-value2\" style=\"background-color: #E38E00; width: ".$recettePrincipale->avancee."%;\">";
		}
		echo "</div>";
		echo "</div>";
		echo "</div>";
		if($recettePrincipale->isComplet()){
			echo "<img class='imgBarreAvancee' src='./images/finBarreOn.png'/>";
		} else {
			echo "<img class='imgBarreAvancee' src='./images/finBarreOff.png'/>";
		}
		echo "</td>";
		echo "<td>";
		
		echo "(".round($recettePrincipale->avancee,1)."%)";
		echo "</td>";
		echo "</tr>";
		echo "</table>";
		echo "</div>";
		$i++;
	}
	
	closeConnectMySql($connection);
}

?>