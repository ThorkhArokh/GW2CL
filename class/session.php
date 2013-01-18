<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/class/constantes.php");

function getUserId() {
    $userId = "";
    foreach ($_COOKIE as $key => $value) {
        if (substr($key, 0, 7) == "phpbb3_" && substr($key, -2) == "_u") {
            $userId = $value;
            break;
        }
    }
    return $userId;
}

function getSession() {
	$userIdTmp = getUserId();
	if($GLOBALS['MODE_DEV']) {
		$userIdTmp = 13;
	}
	if ($userIdTmp > 1) {
		$userTmp = getUser($userIdTmp);
		if(!isset($userTmp)) {
			$userTmp = new User($userIdTmp,"");
		}
		$_SESSION['userLog'] = $userTmp;
		include($_SERVER["DOCUMENT_ROOT"]."/pages/login.php");
	} else {
		sessionExpire();
	}
}

function sessionExpire() {
	echo "<div class='erreur'>La session a expir&eacute;, veuillez vous reconnecter.</div>";
	header('Location: http://forum.endlessfr.com/ucp.php?mode=login');
}
?>