<?php
	require_once('classes/_loader.php');
	require_once('classes/init.php');
	
	$ret = array('success' => true);
	
	$login = new Login('users');
	$login->logout();
	session_destroy();
	
	echo json_encode($ret);
	
	require_once('classes/close.php');
?>