<?php
session_start();
require_once('../config/config.php'); 

//se non c'è la sessione registrata
if (!isset($_SESSION['usr_type']) or 
	$_SESSION['usr_type'] != 'admin' or 
	!isset($_SESSION['authorized']) or 
	$_SESSION['authorized'] != 1) {
  
  echo "<h1>Area riservata, accesso negato.</h1>";
  echo "Per effettuare il login clicca <a href='../index.php'><font color='blue'>qui</font></a>";
  exit();
}

//Altrimenti Prelevo il codice identificatico dell'utente loggato
$username = $_SESSION['username']; //id cod recuperato nel file di verifica
echo "Admin Username: ".$username;
?>

<!DOCTYPE html>
<html>
<head>
	<title>Admin page - <?php echo $sito_internet ?></title>
</head>
<body>
	<?php 
	// blocco html della navigation sottosito admin
	echo file_get_contents('admin_nav.html');
	?>

	<br>
	<h2>Dashboard</h2>
		
	<p>Numero di clienti: <b>18</b></p>
	<p>Numero di ordini oggi: <b>12</b></p>
	<p>Numero di drivers necessari oggi: <b>2</b></p>

	<br>
	<?php echo "Data: ".	$today;?>

</body>
</html>