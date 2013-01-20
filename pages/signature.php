<script type="text/javascript">
//<![CDATA[
function changeFond(url){
	var fond;
	fond=document.getElementById("signfond").value;
	document.getElementById("imgsign").src=url+"&fond="+fond;
	document.getElementById("acopier").value='[img]'+url+"&fond="+fond+'[/img]';
  }
//]]>
</script>
<?php 
include_once($_SERVER["DOCUMENT_ROOT"]."/class/User.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/class/Recette.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/class/outils.php");

if(isset($_SESSION['userLog'])) {
	echo "<div id='signatureDivBox' class='box'>";
	echo "<div class='titre'>G&eacute;n&eacute;ration de signature";
	echo "<input title='Fermer' type='button' onclick='masqueDiv(\"signatureDiv\");' class='closeBtn' id='signatureCloseBtn' name='signatureCloseBtn' value=' ' >";
	echo "</div>";
	echo "<div class='infoBox'>";
	echo "<p>";
	
	$userConnect = $_SESSION['userLog'];
	if($userConnect->armeChoisie != 0) {
		$recettePere = getRecetteSansIngredient($userConnect->armeChoisie, $userConnect->id);
		$image = explode(".", htmlentities($recettePere->objetACraft->img));
		$urlImg = curPageURL(false)."/addons/gw2clsign/gw2clsign.php?joueur=".htmlentities($userConnect->login)."&arme=".$image[0]."&avancement=".round($recettePere->avancee,1);
		
		echo "<select id='signfond' onChange='changeFond(\"".$urlImg."\");'>";
		echo "<option value='0'>Pas de fond</option>";
	  	echo "<option selected value='1'>Fond 1 - Defaut</option>";
	  	echo "<option value='2'>Fond 2</option>";
	  	echo "<option value='3'>Fond 3</option>";
	  	echo "<option value='4'>Fond 4</option>";
	  	echo "<option value='5'>Fond 5</option>";
	  	echo "<option value='6'>Fond 6</option>";
	  	echo "<option value='7'>Fond 7</option>";
	  	echo "<option value='8'>Fond 8</option>";
		echo "</select>";
	
		echo "<br /><br />";
	
		echo "<img src=\"".$urlImg."&fond=1\" id=\"imgsign\" />";
		
		echo "<br /><br />";
		echo "BBCode pour ins&eacute;rer votre signature sur le forum:<br />";
		echo "<input size=120 id='acopier' value='[img]".$urlImg."&fond=1[/img]'/>";
	} else { 
		echo "<div class='erreur'>Veuillez choisir une arme principale pour pouvoir g&eacute;n&eacute;rer la signature.</div>";
	}
	echo "</p>";
	echo "</div>";
	echo "</div>";
}
?>