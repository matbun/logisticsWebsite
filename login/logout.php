<?php
session_start();
$_SESSION = array();

session_unset();
session_destroy(); 

session_start();
$_SESSION['authorized']	= -1;

/*Redirect alla pagina di login*/
echo '<script language=javascript>document.location.href="../index.php"</script>';
?>