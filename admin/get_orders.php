<?php 
session_start();
require_once '../config/config.php'; 


//if empty date
if(!isset($_POST['date']))
	exit();

class Order{
	function __construct($cl_name,
						 $cl_address,
						 //$cl_tel,
						 $ret_name,
						 //$ret_owner,
						 $ret_address,
						 //$ret_tel,
						 $ord_details
						 //$ord_price
						){
		$this->client_name = $cl_name;
		$this->client_address = $cl_address;
		$this->retailer_name = $ret_name;
		$this->retailer_address = $ret_address;
		$this->order_details = $ord_details;

	}
}



// OPENING CONNECTION
$link = mysqli_connect($host, $db_user, $db_psw, $db_name);

// Connection check
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

// QUERY TO GET ORDERS
$date = $_POST['date'];
$sql = "SELECT *
		FROM clients c, orders o, retailers r
	  	WHERE c.client_id = o.client_id AND r.retailer_id = o.retailer_id AND ord_date = '$date'";

if(!($result = mysqli_query($link, $sql))){
	exit();
}


// Close connection
mysqli_close($link);

$response = array();
while($row = mysqli_fetch_array($result)){
	//join di indirizzi e nomi

	//preparazione della response
	$response[] = new Order(
							$row['cl_name'], 
							$row['cl_street'],
							$row['ret_name'],
							$row['ret_street'],
							$row['prod_list']
						);
}


echo json_encode($response);

 ?>
