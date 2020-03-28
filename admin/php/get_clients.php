<?php 
session_start();
require_once '../../config/config.php';
require_once 'db_id_translation.php';


// OPENING CONNECTION
$link = mysqli_connect($host, $db_user, $db_psw, $db_name);

// Connection check
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

// QUERY TO GET Clients
$sql = "SELECT * FROM clients";

if(!($result = mysqli_query($link, $sql))){
	exit();
}


// Close connection
mysqli_close($link);



// RESPONSE DATA STRUCTURES

/**
 * Wrap the data for each client
 *
 * @package default
 * @author Matteo
 **/
class Client{
	function __construct(
						 $cl_n,
						 $cl_name,
						 $cl_surname,
						 $cl_address,
						 $cl_tel,
						 $cl_mail
						){
		$this->client_id = $cl_n;
		$this->client_name = $cl_name;
		$this->client_surname = $cl_surname;
		$this->client_address = $cl_address;
		$this->client_tel = $cl_tel;
		$this->client_mail = $cl_mail;
	}
}


/**
 * Client indexer class: contains the IDs to uniquely identify a client (primary key)
 * This class must extend the DbElement class in which is defined the db_id attribute
 *
 * @package default
 * @author Matteo
 **/
class ClientIndex extends DbElement{
	function __construct($cl_id){
		// db_id: cis the database client id
		parent::__construct($cl_id);
	}
}



// PREPARING THE RESPONSE

// If not existing in session, create a new transaltion table
if(!isset($_SESSION['clients_id_translation']))
	$translation_table = new IdTraslation();
else
	$translation_table = unserialize($_SESSION['clients_id_translation']);


// building response
$response = array();

// readign the SQL response
while($row = mysqli_fetch_array($result)){

	// add to the translation table
	$cl_number = $translation_table->addElement(new ClientIndex($row['client_id'])); //database identifier
	
	
	//join addresses
	$cl_full_address = $row['cl_street'] . " " .
					   $row['cl_street_n'] . ", " .
					   $row['cl_zip'] . " " .
					   $row['cl_city'] . " (" .
					   $row['cl_prov'] . ")";

	// add element to the response
	$response[] = new Client(
							$cl_number,
							$row['cl_name'],
							$row['cl_surname'],
							$cl_full_address,
							$row['cl_tel'],
							$row['cl_mail']
						);
}

// sending response (text)
echo json_encode($response);

// Free result set
mysqli_free_result($result);

// saving the updated translation table in session
$_SESSION['clients_id_translation'] = serialize($translation_table);
?>