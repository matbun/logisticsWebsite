<?php 
/**
 * Building Block used in IdTranslation Class
 * It has the db_id attribute that is unique and represents
 * the primary key in the db, for an element. It may be a concatenation. 
 *
 * @package 
 * @author 
 **/
class DbElement{
	function __construct($db_id){
		$this->db_id = $db_id;
	}
} // END class


/**
 * "hash table" for items ID translation.
 * This is to avoid sending to the webpage plain dabatabse IDs.
 * This class implements methods to translate from frontend ID to "primary key" element
 * and from db_id (primary key, concatenated if necessary) to frontend ID.
 *
 * @package 
 * @author 
 **/
class IdTraslation{
	function __construct(){
		// associative array: (client_id + reytailer_id + date) => frnt_id
		$this->db_to_frontend = array();
		// associative array: frnt_id => (client_id + reytailer_id + date)
		$this->frontend_to_db = array();
		// size
		$this->size = 0;
	}
	
	/**
	 * Add one element to the Id Translation Table
	 *
	 * @param elem extends DbElement class to have the db_id attribute
	 * @return nothing
	 * @author 
	 **/
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

	/**
	 * Returns an Element, knowing its frontend ID.
	 * The element may be of any type extending DbElement class
	 *
	 * @return void
	 * @author 
	 **/
	function getElementByFrontendId($id){
		if(array_key_exists ($id , $this->frontend_to_db)) {
			return $this->frontend_to_db[$id];
		}
		// if not present
		return null;
	}

	/**
	 * Returns the (frontend) ID used in the webpage to universally identify this element.
	 * The element extends DbElement (to get db_id attribute).
	 *
	 * @return the frontend ID if present, else -1 
	 * @author 
	 **/
	function getFrontendId($elem){
		if(array_key_exists ($elem->db_id , $this->db_to_frontend)) {
			return $this->db_to_frontend[$elem->db_id];
		}
		// if not present
		return -1;
	}
}// END class

?>