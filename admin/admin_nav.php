<h1>Menu</h1>
<nav>
	<a href="admin.php">Dashboard</a> |
	<a href="clients.php">Clienti</a> |
	<a href="retailers.php">Commercianti</a> |
	<a href="orders.php">Ordini</a> |
	<?php 
	$user_info = isset($_SESSION['user_info']) ? unserialize($_SESSION['user_info']) : null;
		if ($user_info != null and $user_info->developer) {
			echo '<a href="../test/test.php">Test</a> |';
		}
	?>
	<a href="../login/logout.php">Logout</a>
</nav>