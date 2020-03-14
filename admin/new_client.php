<?php
session_start();
require_once '../config/config.php'; 
require_once '../login/verify_authentication.php';

// VERIFY AUTHORIZATION
verify_login('admin', '../index.php');

// default value if not set
$result = "";




//APERTURA CONNESSIONE
$link = mysqli_connect($host, $db_user, $db_psw, $db_name);

// Connection check
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}




//LOADING CITTA E PROVINCIE
$sql = "SELECT DISTINCT city from cities";
if($db_cities = mysqli_query($link, $sql)){
    if(mysqli_num_rows($db_cities) < 0){
        echo "Errore: non riesco a trovare la tabella delle città nel DB.";
    }
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}

$sql = "SELECT DISTINCT prov from cities";
if($db_provinces = mysqli_query($link, $sql)){
    if(mysqli_num_rows($db_provinces) < 0){
        echo "Errore: non riesco a trovare la tabella delle città nel DB.";
    }
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}





//AGGIUNTA NUOVO CLIENTE
// se è stato premuto il bottone "aggiungi cliente"
if(isset($_POST['aggiungi_cl'])){

	// Query to add a new client

	//Handling Nullable fields
	$mail = empty($_POST['mail'])? "NULL": "'".$_POST['mail']."'";
	$interno = empty($_POST['interno'])? "NULL": $_POST['interno']; #integer
	$frazione = empty($_POST['frazione'])? "NULL": "'".$_POST['frazione']."'";

	$sql = "INSERT INTO `clients` (`client_id`, 
								   `nome`, 
								   `cognome`, 
								   `tel`, 
								   `mail`,
								   `via`, 
								   `n_civico`, 
								   `interno`, 
								   `cap`,
								   `frazione`,
								   `city`,
								   `prov`)
			VALUES (NULL, 
					'".$_POST['nome']."', 
					'".$_POST['cognome']."', 
					'".$_POST['tel']."', 
					".$mail.",
					'".$_POST['via']."',
					".$_POST['n_civico'].", 
					".$interno.",
					".$_POST['cap'].",
					".$frazione.",
					'".$_POST['city']."',
					'".$_POST['prov']."'
					)";

	if($result = mysqli_query($link, $sql)){
		//the query went well
	    $result = "<p>Cliente inserito correttamente!</p>";
	} 
	else{
		//a problem occurred during the query
	    $result = "ERROR: Could not able to execute $sql. " . mysqli_error($link);
	}
	 
}


// Close connection
mysqli_close($link);

?>

<!DOCTYPE html>
<html>
<head>
	<title>Admin page - <?php echo $sito_internet ?></title>
</head>
<body>
	<?php 
	// blocco html della navigation sottosito admin
	echo file_get_contents('admin_nav.html');
	?>

	<br>

	<h2>Nuovo cliente</h2>

	<?php echo $result; ?>

	<form action="new_client.php" method="post">
	    <input name="nome" type="text" placeholder="Nome" autofocus required ><br>
	    <input name="cognome" type="text" placeholder="Cognome" required ><br>
	    <input name="tel" type="text" placeholder="Telefono" required ><br>
	    <input name="mail" type="email" placeholder="Email" ><br>
	    <input name="via" type="text" placeholder="Via/Corso" required ><br>
	    <input name="n_civico" type="text" placeholder="Numero civico" required ><br>
	    <input name="interno" type="text" placeholder="Interno" ><br>
	    <input name="cap" type="text" placeholder="CAP" required ><br>
	    <input name="frazione" type="text" placeholder="Frazione" ><br>
	    <input list="city" name="city" placeholder="Città" required=""><br>
		  <datalist id="city">
		  	<?php  
			while($city = mysqli_fetch_array($db_cities)){
				echo '<option value="'.$city['city'].'">';
			}
		  	?>
		  </datalist>
		<input list="prov" name="prov" placeholder="Provincia" required=""><br>
		  <datalist id="prov">
		    <?php  
			while($prov = mysqli_fetch_array($db_provinces)){
				echo '<option value="'.$prov['prov'].'">';
			}
		  	?>
		  </datalist>
	    

	    <input type="submit" id="submit" name="aggiungi_cl" value="Aggiungi cliente">
	</form>

	<a href="clients.php">Torna indietro.</a>

	
</body>
</html>