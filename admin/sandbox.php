<?php  
/*
$cars['fiat'] = 500;
$cars['porsche'] = 911;
$cars['uno'] = 1;
//echo $cars['porsche'];

$a = array();
foreach ($cars as $car => $value){
	$a[] = $car;
}

echo $cars['d'];
*/


class OrderIndex{
	function __construct($cl_id, $ret_id, $date){
		$this->client_id = $cl_id;
		$this->retailer_id = $ret_id;
		$this->date = $date; //string

		// db_id: concatenation of db primary key elements
		$this->db_id = sprintf("cl%d_ret%d_%s", $cl_id, $ret_id, $date);
	}
}

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

$transl = new IdTraslation();
$transl->addElement(new OrderIndex(1,1,"2020-03-22"));
$transl->addElement(new OrderIndex(1,2,"2020-03-17"));
$transl->addElement(new OrderIndex(4,5,"2020-03-10"));

for ($i = 0; $i<5; $i++){
	$el = $transl->getElementByFrontendId($i);
	if ($el != null) {
		echo $el->db_id . " " . $transl->getFrontendId($el);
		echo "<br>";
	}
	else{
		echo "ERROR";
		echo "<br>";
	}
	
}

?>