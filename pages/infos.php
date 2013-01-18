<?php 
	include_once($_SERVER["DOCUMENT_ROOT"]."/class/Objet.php");
	
	$fragmentObsidienne = getObjetById(25);
	$pieceMystique = getObjetById(41);
	$bouleEcto = getObjetById(28);
	$cristaux = getObjetById(40);
	$pierres = getObjetById(44);
	$pierreRune = getObjetById(9);
	$trefle = getObjetById(27);
	$poussiereCristal = getObjetById(34);
?>

<div class='infoBox'>
	<p><?php 
		echo $trefle->afficheImage()." 10 ".htmlentities($trefle->nom)." (33%) ou &agrave; d&eacute;faut 20 &agrave; 50
		mat&eacute;riaux de craft T6 (67%) :";
	?></p>
	<ul>
		<li><?php 
			echo $fragmentObsidienne->afficheImage()." 10 ".htmlentities($fragmentObsidienne->nom)." (achat au temple de baltazard pour 2100
			karma)";
		?></li>
		<li><?php 
			echo $pieceMystique->afficheImage()." 10 ".htmlentities($pieceMystique->nom);
		?></li>
		<li><?php 
			echo $bouleEcto->afficheImage()." 10 ".htmlentities($bouleEcto->nom);
		?></li>
		<li><?php 
			echo $cristaux->afficheImage()." 10 ".htmlentities($cristaux->nom)." (achat au pnj d'&agrave; c&ocirc;t&eacute;)";
		?></li>
	</ul>
</div>
<div class='infoBox'>
	<p><?php 
		echo $trefle->afficheImage()." 1 ".htmlentities($trefle->nom)." (33%) ou &agrave; d&eacute;faut 2 &agrave; 5
		mat&eacute;riaux de craft T6 (67%) :";
	?></p>
	<ul>
		<li><?php 
			echo $fragmentObsidienne->afficheImage()." 1 ".htmlentities($fragmentObsidienne->nom)." (achat au temple de baltazard pour 2100
			karma)";
		?></li>
		<li><?php 
			echo $pieceMystique->afficheImage()." 1 ".htmlentities($pieceMystique->nom);
		?></li>
		<li><?php 
			echo $bouleEcto->afficheImage()." 1 ".htmlentities($bouleEcto->nom);
		?></li>
		<li><?php 
			echo $pierres->afficheImage()." 6 ".htmlentities($pierres->nom)." (10 contre un point de comp&eacute;tence au PNJ &agrave; c&ocirc;t&eacute; de la forge mystique)";
		?></li>
	</ul>
</div>
<div class='infoBox'>
	<p><?php 
			echo $pierreRune->afficheImage()." 100 ".htmlentities($pierreRune->nom).".";
		?> S'ach&egrave;te &agrave; 1 po
		l'unit&eacute; dans la zone 70-80 Norn apr&egrave;s que la griffe de
		Jormag soit mort</p>
</div>
<div class='infoBox'>
	<p>
		Pour obtenir des objets de craft T6 &agrave; partir d'objets de craft T5 : 
	</p>
	<ul>
		<li>50 objets de craft T5</li>
		<li><?php 
			echo $poussiereCristal->afficheImage()." 5 ".htmlentities($poussiereCristal->nom)." (T6)";
		?></li>
		<li><?php 
			echo $pierres->afficheImage()." 5 ".htmlentities($pierres->nom)." (10 contre un point de comp&eacute;tence au PNJ &agrave; c&ocirc;t&eacute; de la forge mystique)";
		?></li>
		<li>1 objet de craft T6</li>
	</ul>
</div>