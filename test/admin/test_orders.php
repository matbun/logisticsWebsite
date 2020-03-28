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
	<h2>Basic request to get_orders.php</h2>
	<p>Parameter: date = <span id="date"></span></p>
	<p>PHP response to a basic request:</p>
	<span id="my_response"></span>

	<script type="text/javascript">
		//today date
		var today = new Date();
		var today_compact = new Date(today.getTime() - (today.getTimezoneOffset() * 60000)).toISOString().slice(0, 10); // format yyyy-mm-dd
		date.innerHTML = today_compact;
		//request
		var basicReq = new AjaxRequest("POST", "../../admin/php/get_orders.php", function(response){my_response.innerHTML = response});
		basicReq.sendRequest("date=" + today_compact);
	</script>
</body>
</html>