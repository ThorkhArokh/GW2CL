<?php 
include_once($_SERVER["DOCUMENT_ROOT"]."/class/User.php");
?>
<div id='paramDiv' class='box'>
<div class='titre'>Param&egrave;tres</div>
<?php 
	if(isset($_POST['saveParamBtn']) && isset($_POST['zoneSaisieLoginParam'])) {
		if($_POST['zoneSaisieLoginParam'] != "") {
			if(isset($_SESSION['userLog'])) {
				$userConnect = $_SESSION['userLog'];
				$userConnect->modifierLogin($_POST['zoneSaisieLoginParam']);
			}
		} 
	}
?>
<script type="text/javascript">
//<![CDATA[
function valider()
{
	resultat = true;
	document.getElementById('erreurs').innerHTML = "";
	erreurText = "";
	document.forms['modifierParamForm'].elements['zoneSaisieLoginParam'].className="";
	
	eltNom=document.forms['modifierParamForm'].elements['zoneSaisieLoginParam'];

	if(eltNom.value == "") {
		erreurText = erreurText+'<div class="erreur">Le nom d\'utilisateur ne peut pas &ecirc;tre vide.</div>';
		document.forms['modifierParamForm'].elements['zoneSaisieLoginParam'].className="inputErreur";
		resultat = false;
	}

	if(!resultat) {
		document.getElementById('erreurs').innerHTML = erreurText;
	}
	
	return resultat;
}
//]]>
</script>
<div id="erreurs"></div>
<form id='modifierParamForm' method='post' action='index.php' onsubmit="return valider()">
<div class='infoBox'>
<p>
<span>Veuillez saisir le nom d'utilisateur que vous voulez voir appara&icirc;tre :</span>
<input type='text' id='zoneSaisieLoginParam' name='zoneSaisieLoginParam'/>
</p>
</div class='infoBox'>
<p>
<input type='submit' id='saveParamBtn' name='saveParamBtn' value='Enregistrer' />
<input type='button' onclick='masqueParam();' id='annulerParamBtn' name='annulerParamBtn' value='Annuler' />
</p>
</form>
</div>
