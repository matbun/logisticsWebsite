<?php
session_start();
require_once '../config/config.php'; 
require_once '../login/verify_authentication.php';

// VERIFY AUTHORIZATION
verify_login('admin', '../index.php');


$result_msg = "";



//APERTURA CONNESSIONE
$link = mysqli_connect($host, $db_user, $db_psw, $db_name);

// Connection check
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}




//ELIMINAZIONE CLIENTE
if(isset($_POST['deleteItem']) and is_numeric($_POST['item_id'])){
    $delete = $_POST['item_id'];
    $sql = "DELETE FROM `clients` where `client_id` = $delete";
    if(!mysqli_query($link, $sql)){
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
        $result_msg = "<p>Errore eliminazione cliente!</p>";
    }
    else{
        $result_msg = "<p>Cliente eliminato correttamente.</p>";
    }
}




// QUERY FOR CLIENTS LIST
$sql = "SELECT * FROM clients";
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
	<h1>Clienti</h1>
	<h3>Nuovo cliente</h3>
	<li><a href="new_client.php">Aggiungi cliente</a></li>

	<h2>Cerca cliente</h2>
	<form action="find_client.php" method="post">
	    <input name="nome" type="text" placeholder="Nome" autofocus required>
	    <input name="cognome" type="text" placeholder="Cognome" required>
	    <input type="submit" id="submit" value="Cerca">
	</form>

	<h2>Lista clienti</h2>

	<?php 
    echo $result_msg;

    
    if(mysqli_num_rows($result) > 0){
        $delete_confirm = "'Sei sicuro di voler cancellare questo cliente?'";
        $form_header = '<form action="" method="post" onsubmit="return confirm('.$delete_confirm.');">';
        
        echo '<table border="1"	>';
            echo "<tr>";
                echo "<th>Nome</th>";
                echo "<th>Cognome</th>";
                echo "<th>Telefono</th>";
                echo "<th>Indirizzo</th>";
                echo "<th>Email</th>";
            echo "</tr>";
        
        while($row = mysqli_fetch_array($result)){
        	$interno = empty($row['interno'])? ", ": ", Interno ".$row['interno'].", ";
            echo "<tr>";
                echo "<td>" . $row['nome'] . "</td>";
                echo "<td>" . $row['cognome'] . "</td>";
                echo "<td>" . $row['tel'] . "</td>";
                echo "<td>". 
                	 $row['via']." ".
                	 $row['n_civico'].$interno.
                	 $row['cap']." ".
                	 $row['city']." ".
                	 "(" . $row['prov'] . ") " .
                	 "</td>";
                echo "<td>" . $row['mail'] . "</td>";

                //ATTENZIONE: l'item_id andrebbe cifrato e non passato in chiaro. Altrimenti chiunque potrebbe modificare l'html e 
                // cercare di eliminare righe a caso dal DB.
                echo '<td>'.
                          $form_header.'
                          <input name="item_id" value="'.$row['client_id'].'" hidden="true">
                          <input type="submit" name="deleteItem" value="Elimina">
                          </form>
                      </td>';
            echo "</tr>";
        }
    
        echo "</table>";
        
        // Free result set
        mysqli_free_result($result);
        
    } else{
        echo "<p>Nessun cliente trovato...</p>";
    }

	?>

	
</body>
</html>