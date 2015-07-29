<?php
	require_once($base_path.'classes/checkframework.php');
	$login->logout();
	session_destroy();
	header('location: /?rand='.mt_rand(0,10000));
?>