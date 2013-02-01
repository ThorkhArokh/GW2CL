<?php
//Constantes

//Version du site
$GLOBALS['VERSION'] = "1.5";

//Mode dev
$local = ( (substr($NomServeur, 0, 7) == '192.168') || ($NomServeur == 'localhost') || (substr($NomServeur, 0, 3) == '127') );
$GLOBALS['MODE_DEV'] = $local;

?>
