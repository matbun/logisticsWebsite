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
 *
 * @package default
 * @author Matteo
 **/
class OrderIndex{
	function __construct($cl_id, $ret_id, $date){
		$this->client_id = $cl_id;
		$this->retailer_id = $ret_id;
		$this->date = $date; //string

		// db_id: concatenation of db primary key elements
		$this->db_id = sprintf("cl%d_ret%d_%s", $cl_id, $ret_id, $date);
	}
}


/* "hash table" for items ID translation.
*  This is to avoid sending to the webpage 
*  plain dabatabse IDs (in this case retailer_id, client_id).
*/
class IdTraslation{
	function __construct(){
		// associative array: (client_id + reytailer_id + date) => frnt_id
		$this->db_to_frontend = array();
		// associative array: frnt_id => (client_id + reytailer_id + date)
		$this->frontend_to_db = array();
		// size
		$this->size = 0;
	}
	

	function addElement($elem){
		if(!array_key_exists ($elem->db_id , $this->db_to_frontend)) {

			// knowing order detailed DB info I get its frontend id
			$this->db_to_frontend[$elem->db_id] = $this->size;

			// knowing the frontend_ID I can get the element DB info, wrapped in a object
			$this->frontend_to_db[] = $elem;

			$id = $this->size;
			$this->size++;
		}
		else
			$id = $this->db_to_frontend[$elem->db_id];

		return $id;
	}

	function getElementByFrontendId($id){
		if(array_key_exists ($id , $this->frontend_to_db)) {
			return $this->frontend_to_db[$id];
		}
		// if not present
		return null;
	}

	function getFrontendId($elem){
		if(array_key_exists ($elem->db_id , $this->db_to_frontend)) {
			return $this->db_to_frontend[$elem->db_id];
		}
		// if not present
		return -1;
	}
}


// SETUP SESSION TO SAVE IdTraslation OBJECT
if(!isset($_SESSION['orders_id_translation']))
	$_SESSION['orders_id_translation'] = new IdTraslation();



// building response
$response = array();

while($row = mysqli_fetch_array($result)){
	//join di indirizzi e nomi
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

	$ord_number = $_SESSION['orders_id_translation']->addElement(new OrderIndex($row['client_id'], $row['retailer_id'], $row['ord_date'])); //database identifier

	//preparazione della response
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

 ?>
