
<?php
session_start();
require_once '../config/config.php'; 
require_once '../login/verify_authentication.php';

// VERIFY AUTHORIZATION
verify_login('admin', '../index.php');



// OPENING CONNECTION
$link = mysqli_connect($host, $db_user, $db_psw, $db_name);

// Connection check
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}


// QUERY FOR TODAY ORDERS
$today = date('Y-m-d');
$tomorrow = date('Y-m-d', strtotime($today. ' + 1 days'));

/*
$sql = "SELECT  FROM clients";
if(!($result = mysqli_query($link, $sql))){
	echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
	exit();
}
*/



// Close connection
mysqli_close($link);

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
	// admin navigation block
	echo file_get_contents('admin_nav.html');
	?>

	<br>
	<h1>Ordini</h1>
	<h2>Ordini domani</h2>
	<div id="tomorrow_orders"></div>
	<h2>Ordini oggi</h2>
	<div id="today_orders"></div>

</body>
</html>