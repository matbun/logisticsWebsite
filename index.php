<?php 
session_start();
require_once('config/config.php'); 

if(!isset($_SESSION['authorized'])){
	$_SESSION['authorized'] = 0;
}
?>

<!DOCTYPE html>
<html>
<head>

	<!--Login tutorial: https://www.targetweb.it/script-login-utente-in-php-e-mysql-sicuro/-->
    <title>Login al sito - <?php echo $sito_internet ?></title>
    <link href="css/login.css" rel="stylesheet" type="text/css" />

    <script type="text/javascript">
    	function login(){

    		// ajax connection object
    		var loginReq = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    		loginReq.onreadystatechange = function(){
    			if(this.readyState == 4 && this.status == 200){
    				// parse Json response
    				var rx = JSON.parse(this.responseText);
    				if(rx['authorized'] == 1){
    					// redirect to specific page
    					var type = rx['usr_type'];
    					switch(type){
    						case 'admin':
    							redirect_page = "admin/admin.php";
    						break;
    						case 'driver':
    							redirect_page = "driver/driver.php";
    						break;
    						default:
    							window.alert("Tipo utente non riconosciuto");
    							redirect_page = "index.php";
    						break;
    					}

    					document.location.href = redirect_page;
    				}
    				else{
    					// error message
    					document.getElementById("login_result").innerHTML = "Username o password errati!";
    					document.getElementById("login_result").style.display = "inline";

    					// wipe password
    					document.getElementById("password").value = "";
    				}
    			}

    		};

    		var user = document.getElementById("username").value;
    		var psw = document.getElementById("password").value;

    		// prepare request
    		loginReq.open("POST", "login/auth.php", true);
    		loginReq.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    		// send POST request
    		/*
    		  NOTE: username and password should be encrypted before sending in the network to the server.
    		  Without HTTPS or SSL they're sent in plain text.
    		  See two ways encryption.
    		 */
    		loginReq.send("username=" + user + "&password=" + psw);


    		// to prevent default submit of the form
    		return false
    	}
    </script>

</head>
<body>
	
	<?php
	/*
	$_SESSION['authorized]:
		- 1: access granted
		- 0: not yet logged in (neutral)
		- -1: logged out
	*/
	
	if($_SESSION['authorized'] == 1){ // authorized
		// display user info
		echo "<h1>Login effettuato correttamente</h1>";

		printf("Username: %s <br> Type: %s<br><br>", $_SESSION['username'], $_SESSION['usr_type']);
		echo '<li><a href="login/logout.php">Logout</a></li>';
	}
	else{ // not authorized

		/*
		if($_SESSION['authorized'] == -1){
			echo "<h1 id='logout_msg'>Logout effettuato correttamente</h1>";
		}*/
		
		$_SESSION['authorized'] = 0;

		// login form
		echo file_get_contents('login/login_form.html');
	}
    ?>
</body>
</html>

<?php
/*
$link = mysqli_connect("127.0.0.1", "root", "", "test");

// Attempt select query execution
$sql = "SELECT * FROM Utenti";
if($result = mysqli_query($link, $sql)){
    if(mysqli_num_rows($result) > 0){
        echo "<table>";
            echo "<tr>";
                echo "<th>Nome</th>";
                echo "<th>Cognome</th>";
                echo "<th>Nascita</th>";
                echo "<th>città</th>";
            echo "</tr>";
        while($row = mysqli_fetch_array($result)){
            echo "<tr>";
                echo "<td>" . $row['Nome'] . "</td>";
                echo "<td>" . $row['Cognome'] . "</td>";
                echo "<td>" . $row['Nascita'] . "</td>";
                echo "<td>" . $row['CIttà'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Free result set
        mysqli_free_result($result);
    } else{
        echo "No records matching your query were found.";
    }
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}
 
// Close connection
mysqli_close($link);
*/
?>