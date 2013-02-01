<?php
	include_once($_SERVER["DOCUMENT_ROOT"]."/class/Objet.php");
	include_once($_SERVER["DOCUMENT_ROOT"]."/class/Recette.php");
	include_once($_SERVER["DOCUMENT_ROOT"]."/class/connexionMySql.php");
	include_once($_SERVER["DOCUMENT_ROOT"]."/class/User.php");
	include_once($_SERVER["DOCUMENT_ROOT"]."/class/constantes.php");
	include_once($_SERVER["DOCUMENT_ROOT"]."/class/session.php");
	include_once($_SERVER["DOCUMENT_ROOT"]."/class/outils.php");
	include_once($_SERVER["DOCUMENT_ROOT"]."/class/Mobile_Detect.php");
	
	//On d�marre la session
	session_start();
	
	//On initialise l'objet pour la d�tection du support (mobile ou PC)
	$detect = new Mobile_Detect();
	
	//On r�cup�re l'identifiant de l'utilisateur
        getSession();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
    	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Guild Wars 2 - Craft L&eacute;gendaire</title>
		<link rel="icon" type="image/png" href="images/favIco.png" />
		<?php 
		//On s�lectionne la feuille de style ad�quat
		if ($detect->isMobile()) {
			echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/gw2clMobile.css\" />";
		} else {
			echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/gw2cl.css\" />";
		}
		?>
		
	</head>
	<body>
    	<div id="wrapper">	
    		<div id='DEBUG'></div>
            <div id="loading" class="loadingAjax" ><div class='zoneImage'><img src='./images/ajax-loader.gif' /></div></div>
            <?php 
            echo "<div id='param' class='loadingAjax' >";
            include($_SERVER["DOCUMENT_ROOT"]."/pages/parametres.php");
            echo "</div>";
            echo "<div id='signatureDiv' class='loadingAjax' >";
            include($_SERVER["DOCUMENT_ROOT"]."/pages/signature.php");
            echo "</div>";
            
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
	            	include($_SERVER["DOCUMENT_ROOT"]."/pages/avanceeMembre.php");
	            echo "</div>";
	            echo "<div class='box'>";
		            echo "<div class=\"titre\">Infos utiles</div>";
		            include($_SERVER["DOCUMENT_ROOT"]."/pages/infos.php");
	            echo "</div>";
	            echo "</div>";
            }
            
            echo "<div id=\"divCentre\" class='".$classDivCentre."'>";
            ?>
            	<div class='box'>
                <form id="formSelectArme" method="post" action="index.php">
                <?php 
                    if(isset($_SESSION['userLog'])) {
                        $armeSelect = $_SESSION['userLog']->armeChoisie;
                        $isChecked = false;
                        if (isset($_POST['selectRecette']) && isset($_POST['selectRecettePere'])) {
                        	$armeSelect = $_POST['selectRecettePere'];
                        } else {
                        	if(isset($_SESSION['recettePereSelect'])) {
                        		$armeSelect = $_SESSION['recettePereSelect'];
                        	}
                        }
                        if($armeSelect == $_SESSION['userLog']->armeChoisie) {
                        	$isChecked=true;
                        }
                        //On affiche le select pour les objets p�res
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
                if(isset($_SESSION['userLog'])) {
                	$recetteSelectionne = $_SESSION['userLog']->armeChoisie;
                    if (isset($_POST['selectRecette']) && isset($_POST['selectRecettePere'])) {
                        $_SESSION['recettePereSelect'] = $_POST['selectRecettePere'];
                        $recetteSelectionne = $_POST['selectRecettePere'];
                    } else {
                    	if(isset($_SESSION['recettePereSelect'])) {
                    		$recetteSelectionne = $_SESSION['recettePereSelect'];
                    	}	
                    }
                    if($recetteSelectionne != -1) {
                        echo "<div class=\"titre\">Avanc&eacute;e Recette</div>";
                        echo "<div id='resultatAjax' >";
                        afficheRecettePere($_SESSION['userLog']->id, $recetteSelectionne);
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
                    <span> Cr&eacute;&eacute; par <b>Thorkh</b> avec l'aide de <b>Liath</b></span>
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
		<script type="text/javascript">afficheColorTab();</script>
	</body>
</html>
