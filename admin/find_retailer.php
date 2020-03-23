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

$sql = "SELECT * FROM retailers WHERE nome='".$_POST['nome']."' AND city='".$_POST['city']."'";
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
	// navigation block of admin subsite
    require_once 'admin_nav.php';
	?>

	<br>
	<h2>Risultati</h2>
	
	<?php  
    if(mysqli_num_rows($result) > 0){
        echo "<table border='1' >";
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
	
	<br>
	<a href="retailers.php">Torna indietro.</a>

	
</body>
</html>