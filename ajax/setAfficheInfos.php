<?php
//On d�marre la session
session_start();

if(isset($_GET['isAffiche']) && !empty($_GET['isAffiche']) ) {
	$_SESSION['isAffiche'] = $_GET['isAffiche'];
}

?>