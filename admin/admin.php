<?php
session_start();
require_once '../config/config.php'; 
require_once '../login/verify_authentication.php';


// VERIFY AUTHORIZATION
verify_login('admin', '../index.php');
?>

<!DOCTYPE html>
<html>
<head>
	<title>Admin page - <?php echo $sito_internet ?></title>
</head>
<body>
	<?php 
	// navigation block of admin subsite
	require_once 'admin_nav.php';
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