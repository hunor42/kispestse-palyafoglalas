<?php
	require_once('classes/_loader.php');
	require_once('classes/init.php');
	
	$ret = array();
	
	$login = new Login('users');
	$doLogin=$login->doLogin();
	if($doLogin===1){
		$ret['redirectToLogin'] = false;
		$logined_user = $login->obj;
		$ret['success'] = true;
		$ret['reservations'] = $logined_user->get_reservations();
	}else{
		$ret['success'] = false;
		$ret['redirectToLogin'] = true;
		$ret['errors'][] = "Nincs bejelentkezve felhasználó!";
	}
	
	echo json_encode($ret);
	
	require_once('classes/close.php');
?>