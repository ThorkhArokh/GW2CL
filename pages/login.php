<?php 
include_once($_SERVER["DOCUMENT_ROOT"]."/class/User.php");

if(isset($_SESSION['userLog'])) {
	$userConnect = $_SESSION['userLog'];
	if(isset($_POST['saisieLoginBtn']) && isset($_POST['zoneSaisieLogin'])) {
		$userConnect->modifierLogin($_POST['zoneSaisieLogin']);
		$_SESSION['userLog'] = $userConnect;
	}
	if($userConnect->login == "") {
		echo "<div class='bandeauProfil'>";
		echo "<form id='formModifierLogin' method='post' action='index.php'>";
		echo "<span>Veuillez saisir le nom d'utilisateur que vous voulez voir appara&icirc;tre :</span>";
		echo "<input type='text' id='zoneSaisieLogin' name='zoneSaisieLogin' value=''/>";
		echo "<input type='submit' id='saisieLoginBtn' name='saisieLoginBtn' value='Enregistrer' />";
		echo "</form>";
		echo "</div>";
	} else {
		echo "<div class='bandeauProfil'>".$userConnect->login;
		//On initialise l'objet pour la détection du support (mobile ou PC)
		$detect = new Mobile_Detect();
		if (!$detect->isMobile()) {
			echo "<input title='G&eacute;n&eacute;rer signature' type='button' onclick='afficheDiv(\"signatureDiv\");' class='signatureBtn' id='signatureBtn' name='signatureBtn' value=' ' >";
			echo "<input title='Param&eacute;tres' type='button' onclick='afficheDiv(\"param\");' class='paramBtn' id='paramBtn' name='paramBtn' value=' ' >";
		}
		echo "</div>";
	}
} 
?>
