<?php
	require_once('classes/_loader.php');
	require_once('classes/init.php');
	
	$date = $_GET['date'];
	$date = str_replace(' (Central Europe Daylight Time)','',$date);
	$date = strtotime($date);
	if($date===false){
		$ret = array('error'=>'Invalid date');
	}else{
		$date_formatted = date('Y-m-d',$date);
		
		$login = new Login('users');
		$logined_user = ($login->doLogin()==1 ? $login->obj : null);
		$timeunits = TimeUnit::get_day_timeunits($date_formatted,$logined_user);
		
		$ret = $timeunits;
	}
	echo json_encode($ret);
	
	require_once('classes/close.php');
?>