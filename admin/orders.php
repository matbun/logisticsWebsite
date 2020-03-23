
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
	<title>Amministrazione - <?php echo $sito_internet ?></title>
	<script type="text/javascript" src="js/orders.js"></script>
	<script type="text/javascript" src="js/clients.js"></script>
	<script type="text/javascript" src="js/retailers.js"></script>
</head>
<body onload="loadOrders()">
	<?php 
	// navigation block of admin subsite
	require_once 'admin_nav.php';
	?>

	<br>
	<h1>Ordini</h1>
	<h2>Ordini domani</h2>
	<div id="tomorrow_orders"></div>
	<h2>Ordini oggi</h2>
	<div id="today_orders"></div>

</body>
</html>