
<?php
session_start();
require_once('../config/config.php'); 

// if the user isn't logged in or it doesn't have the proper rights
if (!isset($_SESSION['usr_type']) or 
	$_SESSION['usr_type'] != 'admin' or 
	!isset($_SESSION['authorized']) or 
	$_SESSION['authorized'] != 1) {
  
  echo "<h1>Area riservata, accesso negato.</h1>";
  echo "Per effettuare il login clicca <a href='../index.php'><font color='blue'>qui</font></a>";
  exit();
}

// Ohterwise get the username
$username = $_SESSION['username']; 
echo "Admin Username: ".$username;



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
</head>
<body onload="loadOrders()">
	<?php 
	// admin navigation block
	echo file_get_contents('admin_nav.html');
	?>

	<br>
	<h1>Ordini</h1>
	<h2>Ordini domani</h2>

	<h2>Ordini oggi</h2>
	<div id="today_orders"></div>

</body>
</html>