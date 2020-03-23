<?php 
session_start();
require_once '../config/config.php'; 
require_once '../login/verify_authentication.php';
require_once '../login/user_info_class.php';

// VERIFY AUTHORIZATION
verify_developer('../index.php');
?>

<!DOCTYPE html>
<html>
<head>
	<title>Test - <?php echo $sito_internet ?></title>
	<script type="text/javascript" src=""></script>
</head>
<body>
	<?php 
	// navigation block of test subsite
	require_once 'test_nav.php';
	?>
</body>
</html>