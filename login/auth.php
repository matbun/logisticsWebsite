<?php
session_start();
require_once("../config/config.php"); 

$link = mysqli_connect($host, $db_user, $db_psw, $db_name);

//check della connessione
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}


//per evitare sql Injection uso le parametrized queries, che sono anche più efficienti se usate iterativamente

/* create a prepared statement */
if ($stmt = mysqli_prepare($link, "SELECT user_id, username, type FROM company_users WHERE username = ? AND password = sha1(?)")) {

    /* bind parameters for markers */
    mysqli_stmt_bind_param($stmt, "ss", $_POST['username'], $_POST['password']);

    /* execute query */
    mysqli_stmt_execute($stmt);

    /* bind result variables */
    mysqli_stmt_bind_result($stmt, $usr_id, $usr_name, $usr_type);

    /* fetch value */
    mysqli_stmt_fetch($stmt);

    /* close statement */
    mysqli_stmt_close($stmt);
}

//ADD HERE THE CHECK FOR OUTHER_USERS CREDENTIALS...
//...

// Close connection
mysqli_close($link);


// response class
class Response{
	public $authorized;
	public $usr_type;
	public $usr_name;
}
$response = new Response;

// check if the user exists
if(isset($usr_name)){
	$_SESSION['authorized']	= 1;
	$_SESSION['usr_id'] = $usr_id;
	$_SESSION['username'] = $usr_name;
	$_SESSION['usr_type'] = $usr_type;

	// preparing respone to ajax request
	$response->authorized = 1;
	$response->usr_type = $usr_type;
	$response->usr_name = $usr_name;
	
}
else{
	$_SESSION['authorized']	= 0;

	// json respone to ajax request
	$response->authorized = 0;
 	
}

// return the response object encoded as json string
echo json_encode($response);
 
?>