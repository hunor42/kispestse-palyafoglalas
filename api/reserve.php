<?php
	require_once('classes/_loader.php');
	require_once('classes/init.php');
	
	$id = $_GET['id'];
	
	$timeunit = TimeUnit::get_by_id($id);
	if(!is_object($timeunit)){
		$ret = array('error'=>'Invalid timeunit id');
	}else{
		if($timeunit->is_prereserveable()){
			$login = new Login('users');
			$logined_user = ($login->doLogin()==1 ? $login->obj : null);
			$basket->add($timeunit,$logined_user);
			$ret = $basket->get_basket();
		}else{
			$ret = array('error'=>'Már foglalt időpont!');
		}
	}
	echo json_encode($ret);
	
	require_once('classes/close.php');
?>