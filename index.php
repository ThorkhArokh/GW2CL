<?php
	include_once($_SERVER["DOCUMENT_ROOT"]."/class/Objet.php");
	include_once($_SERVER["DOCUMENT_ROOT"]."/class/Recette.php");
	include_once($_SERVER["DOCUMENT_ROOT"]."/class/connexionMySql.php");
	include_once($_SERVER["DOCUMENT_ROOT"]."/class/User.php");
	include_once($_SERVER["DOCUMENT_ROOT"]."/class/constantes.php");
	include_once($_SERVER["DOCUMENT_ROOT"]."/class/session.php");
	include_once($_SERVER["DOCUMENT_ROOT"]."/class/outils.php");
	include_once($_SERVER["DOCUMENT_ROOT"]."/class/Mobile_Detect.php");
	
	//On démarre la session
	session_start();
	
	//On initialise l'objet pour la détection du support (mobile ou PC)
	$detect = new Mobile_Detect();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
    	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Guild Wars 2 - Craft L&eacute;gendaire</title>
		<link rel="icon" type="image/png" href="images/favIco.png" />
		<?php 
		//On sélectionne la feuille de style adéquat
		if ($detect->isMobile()) {
			echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/gw2clMobile.css\" />";
		} else {
			echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/gw2cl.css\" />";
		}
		?>
		
	</head>
	<body>
    	<div id="wrapper">	
            <div id="loading" class="loadingAjax" ><div><img src='./images/ajax-loader.gif' /></div></div>
            <?php 
            //On récupère l'identifiant de l'utilisateur
            getSession();
            $classDivDroite = "divDroiteShow";
            $classDivCentre = "divCentreMin";
            if(isset($_SESSION['isAffiche']) && $_SESSION['isAffiche'] == "false") {
            	$classDivDroite = "divDroiteHide";
            	$classDivCentre = "divCentreMax";
            }
            if (!$detect->isMobile()) {
            	echo "<div id=\"divGauche\">";
	            echo "</div> <!-- Div Gauche -->";
	            echo "<div id=\"divDroite\" class='".$classDivDroite."'>";
	            echo "<div class='box'>";
		            echo "<div class=\"titre\">Avanc&eacute;es des membres</div>";
		            getUsersAvancement();
	            echo "</div>";
	            echo "<div class='box'>";
		            echo "<div class=\"titre\">Infos utiles</div>";
		            include("pages/infos.php");
	            echo "</div>";
	            echo "</div>";
            }
            
            echo "<div id=\"divCentre\" class='".$classDivCentre."'>";
            ?>
            	<div class='box'>
                <form id="formSelectArme" method="post" action="index.php">
                <?php 
                    if(isset($_SESSION['userLog'])) {
                        $armeSelect = "-1";
                        $isChecked = false;
                        if (isset($_POST['selectRecette']) && isset($_POST['selectRecettePere'])) {
                        	$armeSelect = $_POST['selectRecettePere'];
                        	if($armeSelect == $_SESSION['userLog']->armeChoisie) {
                        		$isChecked=true;
                        	}
                        } 
                        //On affiche le select pour les objets pères
                        getRecettePere($_SESSION['userLog']->id, $armeSelect);
                        
                        echo " <input type=\"submit\" class='submit' name=\"selectRecette\" value=\"Afficher avancement\" />";
                        if($isChecked) {
                        	echo "<input type=\"checkbox\" checked=\"".$isChecked."\" name=\"choixArme\" value=\"choixArme\" onclick='selectArmePrincipale(this, ".$_SESSION['userLog']->id.");' /><span class='text'>Arme principale<span>";
                        } else {
                        	echo "<input type=\"checkbox\" name=\"choixArme\" value=\"choixArme\" onclick='selectArmePrincipale(this, ".$_SESSION['userLog']->id.");' /><span class='text'>Arme principale<span>";
                        }
                        if (!$detect->isMobile()) {
                        	echo "<div class='boutonsAvanceeDiv'>";
                        	echo "<input id='hideInfoBtn' value='?' title=\"Afficher/masquer la zone d'informations\" type='button' onclick='afficheInfo();' />";
                        	echo "</div>";
                        }
                    }
                ?>
                </form>
                </div>
                <div id="container">
                <?php 
                $_SESSION['recettePereSelect'] = -1;
                if(isset($_SESSION['userLog'])) {
                    if (isset($_POST['selectRecette']) && isset($_POST['selectRecettePere'])) {
                        $_SESSION['recettePereSelect'] = $_POST['selectRecettePere'];
                        echo "<div class=\"titre\">Avanc&eacute;e Recette</div>";
                        echo "<div id='resultatAjax' >";
                        afficheRecettePere($_SESSION['userLog']->id, $_POST['selectRecettePere']);
                        echo "</div>";
                    } else {
                        echo "<div class='titre'>Veuillez choisir un objet dans la liste.</div>";
                    }
                } else {
                    echo "<div class='titre'>Veuillez vous connecter pour afficher votre avanc&eacute;e.</div>";
                }
                ?>
                </div> <!-- Fin container -->
                <?php 
            	echo "<div id=\"bottom\">";
            	if (!$detect->isMobile()) {
                    echo "<div><img class='bar' src='./images/bar.png' /></div>";
            	}
                echo "<span>GW2CL &copy; 2012 - Version ".$GLOBALS['VERSION']."</span>		
                    <br/>
                    <span> Cr&eacute;&eacute; par Thorkh</span>
                </div> <!-- div bottom -->";
            ?>
            </div> <!-- Div centre -->
            
            <div class="spacer"></div>
            <!-- Pied de page -->
            
        </div>
        
        <!-- APPELS JS -->
        <script type="text/javascript" src="js/core.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
		<script type="text/javascript" src="http://static-ascalon.cursecdn.com/current/js/syndication/tt.js"></script>
	</body>
</html>