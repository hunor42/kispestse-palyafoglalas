<?php
	require_once('classes/_loader.php');
	require_once('classes/init.php');
	
	$id = $_GET['id'];
	
	$basket->clear();
	$ret = $basket->get_basket();
	echo json_encode($ret);
	
	require_once('classes/close.php');
?>