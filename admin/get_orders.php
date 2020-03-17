<?php 
session_start();
require_once '../config/config.php'; 


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



// PREPARING RESPONSE

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
						 $cl_address,
						 //$cl_tel,
						 $ret_name,
						 //$ret_owner,
						 $ret_address,
						 //$ret_tel,
						 $ord_details
						 //$ord_price
						){
		$this->order_number = $ord_n;
		$this->client_name = $cl_name;
		$this->client_address = $cl_address;
		$this->retailer_name = $ret_name;
		$this->retailer_address = $ret_address;
		$this->order_details = $ord_details;

	}
}


/**
 * Order indexer class: contains the IDs to uniquely identify an order (primary key)
 *
 * @package default
 * @author Matteo
 **/
class OrderIndex{
	function __construct($cl_id, $ret_id, $date){
		$this->client_id = $cl_id;
		$this->retailer_id = $ret_id;
		$this->date = $date; //string
	}
}


/* hash table for items ID translation.
*  This is to avoid sending to the webpage 
*  plain dabatabse IDs (in this case retailer_id, client_id).
*/
$ids_translation = array();


// building response
$response = array();

// order enumeration within this query: has to be 0 based!
$ord_number = 0;

while($row = mysqli_fetch_array($result)){
	//join di indirizzi e nomi

	//preparazione della response
	$response[] = new Order(
							$ord_number,
							$row['cl_name'], 
							$row['cl_street'],
							$row['ret_name'],
							$row['ret_street'],
							$row['prod_list']
						);
	$ids_translation[] = new OrderIndex($row['client_id'], $row['retailer_id'], $row['ord_date']); //database identifier
	
	// increment order number
	$ord_number++;
}


/* I save in the current session the "hash table" to translate the id showed 
   in the web page (seen by the browser) to the ID (or IDs) used in the database as a primary key
   (which have to be secret and not showed to the client browser).
   Each time new orders are visualized, this traslation table is updated.
 */
$_SESSION['currently_visualized_orders'] = $ids_translation;


// sending response (text)
echo json_encode($response);

// Free result set
mysqli_free_result($result);

 ?>
