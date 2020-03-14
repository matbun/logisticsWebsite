<?php
session_start();
require_once '../config/config.php'; 
require_once '../login/verify_authentication.php';

// VERIFY AUTHORIZATION
verify_login('admin', '../index.php');




//APERTURA CONNESSIONE
$link = mysqli_connect($host, $db_user, $db_psw, $db_name);

// Connection check
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}



//QUERY PER RICAVARE TUTTE LE CITTA
$sql = "SELECT DISTINCT city from cities";
if($db_cities = mysqli_query($link, $sql)){
    if(mysqli_num_rows($db_cities) < 0){
        echo "Errore: non riesco a trovare la tabella delle città nel DB.";
    }
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}



// ESTRAZIONE LISTA DI TUTTI I COMMERCIANTI
$sql = "SELECT * FROM retailers";
if(!($result = mysqli_query($link, $sql))){
	echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
	exit();
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
	<h1>Commercianti</h1>
	<h3>Nuovo commerciante</h3>
	<li><a href="new_retailer.php">Aggiungi commmerciante</a></li>

	<h2>Cerca commerciante</h2>
	<form action="find_retailer.php" method="post">
	    <input name="nome" type="text" placeholder="Nome" autofocus required>
	    <input list="city" name="city" placeholder="Città" required=""><br>
          <datalist id="city">
            <?php  
            while($city = mysqli_fetch_array($db_cities)){
                echo '<option value="'.$city['city'].'">';
            }
            ?>
          </datalist>
	    <input type="submit" id="submit" value="Cerca">
	</form>

	<h2>Lista commercianti</h2>
	<?php  
    if(mysqli_num_rows($result) > 0){
        echo "<table border='1'	>";
            echo "<tr>";
                echo "<th>Nome</th>";
                echo "<th>Proprietario</th>";
                echo "<th>Telefono</th>";
                echo "<th>Indirizzo</th>";
                echo "<th>Email</th>";
            echo "</tr>";
        while($row = mysqli_fetch_array($result)){
        	$interno = empty($row['interno'])? ", ": ", Interno ".$row['interno'].", ";
            echo "<tr>";
                echo "<td>" . $row['nome'] . "</td>";
                echo "<td>" . $row['proprietario'] . "</td>";
                echo "<td>" . $row['tel'] . "</td>";
                echo "<td>". 
                	 $row['via']." ".
                	 $row['n_civico'].$interno.
                	 $row['cap']." ".
                	 $row['city']." ".
                	 "(" . $row['prov'] . ") " .
                	 "</td>";
                echo "<td>" . $row['mail'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Free result set
        mysqli_free_result($result);
    } else{
        echo "<p>Nessun commerciante trovato...</p>";
    }
	?>

	
</body>
</html>