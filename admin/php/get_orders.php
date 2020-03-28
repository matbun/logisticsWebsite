<?php 
session_start();
require_once '../../config/config.php'; 
require_once 'db_id_translation.php';


//if empty date
if(!isset($_POST['date']))
	exit();



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



// RESPONSE DATA STRUCTURES

/**
 * Wrap the data for each order
 *
 * @package default
 * @author Matteo
 **/
class Order{
	function __construct(
						 $ord_n,
						 $cl_name,
						 $cl_surname,
						 $cl_address,
						 $cl_tel,
						 $cl_mail,
						 $ret_name,
						 $ret_owner,
						 $ret_address,
						 $ret_tel,
						 $ret_mail,
						 $ord_details,
						 $ord_price,
						 $service_cost,
						 $ord_delivered
						){
		$this->order_number = $ord_n;
		$this->client_name = $cl_name;
		$this->client_surname = $cl_surname;
		$this->client_address = $cl_address;
		$this->client_tel = $cl_tel;
		$this->client_mail = $cl_mail;
		$this->retailer_name = $ret_name;
		$this->retailer_owner = $ret_owner;
		$this->retailer_address = $ret_address;
		$this->retailer_tel = $ret_tel;
		$this->retailer_mail = $ret_mail;
		$this->order_details = $ord_details;
		$this->order_price = $ord_price;
		$this->service_cost = $service_cost;
		$this->order_delivered = $ord_delivered;
	}
}


/**
 * Order indexer class: contains the IDs to uniquely identify an order (primary key)
 * This class must extend the DbElement class in which is defined the db_id attribute
 *
 * @package default
 * @author Matteo
 **/
class OrderIndex extends DbElement{
	function __construct($cl_id, $ret_id, $date){
		$this->client_id = $cl_id;
		$this->retailer_id = $ret_id;
		$this->date = $date; //string

		// db_id: concatenation of db primary key elements
		parent::__construct(sprintf("cl%d_ret%d_%s", $cl_id, $ret_id, $date));
	}
}



// PREPARING THE RESPONSE

// If not existing in session, create a new transaltion table
if(!isset($_SESSION['orders_id_translation']))
	$translation_table = new IdTraslation();
else
	$translation_table = unserialize($_SESSION['orders_id_translation']);


// building response
$response = array();

// readign the SQL response
while($row = mysqli_fetch_array($result)){

	// add to the translation table
	$ord_number = $translation_table->addElement(new OrderIndex($row['client_id'], $row['retailer_id'], $row['ord_date'])); //database identifier
	
	
	//join addresses
	$cl_full_address = $row['cl_street'] . " " .
					   $row['cl_street_n'] . ", " .
					   $row['cl_zip'] . " " .
					   $row['cl_city'] . " (" .
					   $row['cl_prov'] . ")";
	$ret_full_address = $row['ret_street'] . " " .
					    $row['ret_street_n'] . ", " .
					    $row['ret_zip'] . " " .
					    $row['ret_city'] . " (" .
					    $row['ret_prov'] . ")";

	// add element to the response
	$response[] = new Order(
							$ord_number,
							$row['cl_name'],
							$row['cl_surname'],
							$cl_full_address,
							$row['cl_tel'],
							$row['cl_mail'],
							$row['ret_name'],
							$row['ret_owner'],
							$ret_full_address,
							$row['ret_tel'],
							$row['ret_mail'],
							$row['prod_list'],
							$row['ord_tot_price'],
							$row['ord_service_cost'],
							$row['delivered']
						);
}

// sending response (text)
echo json_encode($response);

// Free result set
mysqli_free_result($result);

// saving the updated translation table in session
$_SESSION['orders_id_translation'] = serialize($translation_table);

 ?>
