<?php 
require_once "user_info_class.php"; 

function reserved_area_message($index_page){
	echo "<h1>Area riservata, accesso negato.</h1>";
	echo "Per effettuare il login clicca <a href='$index_page'><font color='blue'>qui</font></a>";
}


/**
 * Checks if the user is authorized.
 *
 * @param user_type is the type of user. Eg. admin, driver...
 * @param index_page is the path to the page to return 
 * @return void
 **/
function verify_login($user_type, $index_page){
	$user_info = isset($_SESSION['user_info']) ? unserialize($_SESSION['user_info']) : null;
	//echo "info: ".var_dump($user_info);

	// if the user isn't logged in or it doesn't have the proper rights
	if ($user_info == null or 
		$user_info->user_type != strtolower($user_type) or 
		!isset($_SESSION['authorized']) or 
		$_SESSION['authorized'] != 1) {
		reserved_area_message($index_page);
		exit();
	}

	// Ohterwise show the username
	$username = $user_info->username; 
	echo "$user_type username: $username";
}

function verify_developer($index_page){
	$user_info = isset($_SESSION['user_info']) ? unserialize($_SESSION['user_info']) : null;

	// if the user isn't logged in or it doesn't have the proper rights
	if ($user_info == null or 
		!$user_info->developer or 
		!isset($_SESSION['authorized']) or 
		$_SESSION['authorized'] != 1) {
		reserved_area_message($index_page);
		exit();
	}

	// Ohterwise show the username
	$username = $user_info->username; 
	echo "Developer username: $username";
}

?>