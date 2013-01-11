<?php 
session_start();

function getUserId()
{
	setcookie("TestCookie", "TEST_JFEU");
    $userId = "";
    echo "getUserId ".count($_COOKIE)."<br/>";
    foreach ($_COOKIE as $key => $value) {
    	echo "key : ".$key." - value : ".$value."<br/>";
        if (substr($key, 0, 7) == "phpbb3_" && substr($key, -2) == "_u") {
            $userId = $value;
            break;
        }
    }
    return $userId;
}

if (getUserId() > 1) {
    echo "Connecté ! ".getUserId();
} else {
    echo "Pas connecté !";
}

?>