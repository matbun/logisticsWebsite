<?php 
session_start();
require_once '../../config/config.php'; 
require_once '../../login/verify_authentication.php';
require_once '../../login/user_info_class.php';

// VERIFY AUTHORIZATION
verify_developer('../../index.php');
?>

<!DOCTYPE html>
<html>
<head>
	<title>Test - <?php echo $sito_internet ?></title>
	<script type="text/javascript" src="../../js/ajax_request.js"></script>
</head>
<body>
	<?php 
	// navigation block of test subsite
	require_once 'admin_test_nav.php';
	?>
	<br>
	<br>
	<h2>Basic request to get_clients.php</h2>
	<p>PHP response to a basic request:</p>
	<span id="my_response"></span>

	<script type="text/javascript">		
		var basicReq = new AjaxRequest("POST", "../../admin/php/get_clients.php", function(response){my_response.innerHTML = response});
		basicReq.sendRequest();
	</script>
</body>
</html>