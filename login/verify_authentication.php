<?php 
/**
 * Checks if the user is authorized.
 *
 * @param user_type is the type of user. Eg. admin, driver...
 * @param index_page is the path to the page to return 
 * @return void
 **/
function verify_login($user_type, $index_page){
	// if the user isn't logged in or it doesn't have the proper rights
	if (!isset($_SESSION['usr_type']) or 
		$_SESSION['usr_type'] != $user_type or 
		!isset($_SESSION['authorized']) or 
		$_SESSION['authorized'] != 1) {
	  
	  echo "<h1>Area riservata, accesso negato.</h1>";
	  echo "Per effettuare il login clicca <a href='$index_page'><font color='blue'>qui</font></a>";
	  exit();
	}

	// Ohterwise show the username
	$username = $_SESSION['username']; 
	echo "$user_type Username: $username";
}

?>