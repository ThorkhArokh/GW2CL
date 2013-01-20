<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/class/User.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/class/Recette.php");

/* Genere l'image signature
 * $joueur : Nom du joueur (ex: Liath)
 * $arme : Le nom de l'arme en cours (ex: Aurore)
 * $avancement : L'avancée en pourcentage (ex: 55)
 * $fonchoisi : Le fond choisi pour la signature (ex: 2)
 */
header('Content-type: image/png');

if(isset($_GET["joueur"]) 
&& isset($_GET["fond"]))
{ 
	$userConnect = getUser($_GET["joueur"]);
	$recettePere = getRecetteSansIngredient($userConnect->armeChoisie, $userConnect->id);
	
  // Ouverture de l'image fond
  $fichierfond = 'images/fonds/'.$_GET["fond"].'.png';
  $fond = imagecreatefrompng($fichierfond);
  
  
  // Initialisations
  $blanc = imagecolorallocate($fond,"0xFF","0xFF","0xFF");
  $orange = imagecolorallocate($fond,"0xD6","0x92","0x1E");
  $noir = imagecolorallocate($fond,"0x00","0x00","0x00");
  $gris = imagecolorallocate($fond, "0x2E", "0x2E", "0x2E");
  $alignicone = 10;
  $aligntext = 76;
  $taillebarremax = 300;
  
  // Ouverture de l'icone
  $fichiericone = 'images/icons/'.$recettePere->objetACraft->img;
  $icone = imagecreatefrompng($fichiericone); 
  
  // On fusionne les deux
  imagecopymerge($fond,$icone, $alignicone, 10, 0, 0, 56, 56, 100);
  imagerectangle($fond, $alignicone, 10, 66, 66, $noir);
  
  // On écrit le texte
  
  //imagestring($fond, 5, 76, 10, $_GET["joueur"], $blanc);
  putenv('GDFONTPATH=' . realpath('.')); //ligne obligatoire !
  // Nom du joueur
  imagettftext($fond, 20, 0, $aligntext+1, 31, $gris, 'Qlassik_TB.ttf', ucfirst($userConnect->login));  // Ombre
  imagettftext($fond, 20, 0, $aligntext, 30, $blanc, 'Qlassik_TB.ttf', ucfirst($userConnect->login));
  // Nom de l'arme
  imagettftext($fond, 11, 0, $aligntext+1, 45, $gris, 'Qlassik_TB.ttf', ucfirst($recettePere->objetACraft->nom));  // Ombre
  imagettftext($fond, 11, 0, $aligntext, 44, $blanc, 'Qlassik_TB.ttf', ucfirst($recettePere->objetACraft->nom));
  // Avancement...
  // Fond de barre
  imagefilledrectangle($fond, $aligntext, 50, $taillebarremax, 64, $gris);
  // Barre d'avancement orange
  imagefilledrectangle($fond, $aligntext, 50, $aligntext+(($taillebarremax-$aligntext)*($recettePere->avancee/100)), 64, $orange);
  // Petit contour qui va bien :)
  imagerectangle($fond, $aligntext, 50, $taillebarremax, 64, $noir);
  // On affiche l'avancement
  imagettftext($fond, 11, 0, $aligntext+4, 63, $gris, 'Qlassik_TB.ttf', "Avancement : ".round($recettePere->avancee,1)."%");
  imagettftext($fond, 11, 0, $aligntext+3, 62, $blanc, 'Qlassik_TB.ttf', "Avancement : ".round($recettePere->avancee,1)."%");
  // Icone de fin de barre, éteint si arme pas finie, allumé sinon
  $fichierfinbarre=($recettePere->avancee==100)?'images/finBarreOn.png':'images/finBarreOff.png';	
  $finbarre = imagecreatefrompng($fichierfinbarre);
  imagecopyresampled($finbarre, $finbarre, 0, 0, 0, 0, 17, 17, 21, 21);
  imagecopymerge($fond, $finbarre, $taillebarremax, 49, 0, 0, 17, 17, 100);
  
  // Désactive l'Alpha blending et définit le drapeau Alpha
  imagealphablending($fond, false);
  imagesavealpha($fond, true);
  
  // On sort l'image
  imagepng($fond);
  // Et on fait lé ménach :D
  imagedestroy($fond);
  imagedestroy($icone);
  imagedestroy($finbarre);
}


// Icones : 56*56px
// Taille signature : 460*76px
// Position icone : 10, 10
// Position texte : 76, 10
?>