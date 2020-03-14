<?php
session_start();
include('../config/config.php'); 

//se non c'è la sessione registrata
if (!isset($_SESSION['usr_type']) or 
	$_SESSION['usr_type'] != 'driver' or 
	!isset($_SESSION['authorized']) or 
	$_SESSION['authorized'] != 1) {
  
  echo "<h1>Area riservata, accesso negato.</h1>";
  echo "Per effettuare il login clicca <a href='../index.php'><font color='blue'>qui</font></a>";
  exit();
}

//Altrimenti Prelevo il codice identificatico dell'utente loggato
$username = $_SESSION['username']; //id cod recuperato nel file di verifica
echo "Driver Username: ".$username;
?>