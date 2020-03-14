<?php 
session_start();
require_once('../config/config.php'); 

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

$response = array(new Order('matteo bun', 'via mas45 almese', 'macelleria rivera', 'via rivera 3', '3kg di carne'),
			 	  new Order('asdas', 'via mas45 almese', 'macelleria rivera', 'via rivera 3', '3kg di carne'));


echo json_encode($response);

 ?>
