<?php
	require_once('classes/_loader.php');
	require_once('classes/init.php');
	
	$id = $_GET['id'];
	
	try{
		$timeunit = TimeUnit::get_by_id($id);
		$basket->remove($timeunit);
		$timeunit->free_prereserve();
		$ret = $basket->get_basket();
	}
	catch(Exception $ex){
		$ret = array();
		$ret['success'] = false;
		$ret['errors'][] = 'Hibás időegység azonosító';
	}
	echo json_encode($ret);
	
	require_once('classes/close.php');
?>