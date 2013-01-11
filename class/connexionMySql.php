<?php 
function connectMySql()
{
  $connection = mysql_connect('localhost','gw2cl', 'gw2cl');  
  if ($connection>0)
  {
     //echo "[MySql] Info : Connexion effectu&eacute;e";
     mysql_select_db('gw2cl',$connection);
  }
  else
  {
     echo "<div class='erreur'>[MySql] Erreur : Pas de connexion active<div class='erreur'>";
     exit();
  }
  
  return $connection;
}

function closeConnectMySql($connection)
{
  mysql_close($connection); 
  //echo "[MySql] Info : Connexion close";
}
?> 