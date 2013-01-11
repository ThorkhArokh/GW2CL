<?php
	include_once($_SERVER["DOCUMENT_ROOT"]."/GW2CL/class/Objet.php");
	include_once($_SERVER["DOCUMENT_ROOT"]."/GW2CL/class/Recette.php");
	Require_once($_SERVER["DOCUMENT_ROOT"]."/GW2CL/class/connexionMySql.php");
?>
<html>
	<head>
		<title>Guild Wars 2 - Craft L&eacute;gendaire</title>
		<link rel="stylesheet" type="text/css" href="css/gw2cl.css" />
		<script type="text/javascript" src="js/core.js"></script>
	</head>
	<body>	
		<div id="container">
		<div id='resultatAjax' >

		<?php 
				
			$connection = connectMySql();
			
			$reqSelectRecettes = "SELECT r.id as idRecette FROM recette r where r.indicRecettePere = 'O'";
			$resRecettes = mysql_query($reqSelectRecettes) or die("<div class='erreur'>[MySql] Erreur : ".htmlentities(mysql_error())."</div>");
			
			$i = 0;
			while($row = mysql_fetch_array( $resRecettes )) {
				$nouvelleRecette = getRecette($row['idRecette']);
				$nouvelleRecette->afficheRecette(0, $i);
				$i++;
			}
			
			closeConnectMySql($connection);

		?>
		</div>
		</div>
	</body>
</html>