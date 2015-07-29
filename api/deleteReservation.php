<?php
	require_once('classes/_loader.php');
	require_once('classes/init.php');
	
	$id = $_GET['id'];
	
	$login = new Login('users');
	$doLogin=$login->doLogin();
	if($doLogin===1){
		$ret['redirectToLogin'] = false;
		$logined_user = $login->obj;
		$reservation = Reservation::get_by_id($id);
		if(!is_object($reservation)){
			$ret['success'] = false;
			$ret['errors'][] = 'Hibás foglalási azonosító!';
		}else{
			if($reservation->username!=$logined_user->username){
				$ret['success'] = false;
				$ret['errors'][] = 'Jogosultság hiba (nem saját foglalás törlése?)';
			}else{
				$reservation->user = $logined_user;
				$reservation->load_timeunit();
				$is_freeable = $reservation->is_freeable();
				if($is_freeable===true){
					$reservation->free();
					$ret['success'] = true;
				}else{
					$ret['success'] = false;
					$ret['errors'] = $is_freeable;
				}
			}
		}
	}else{
		$ret['success'] = false;
		$ret['redirectToLogin'] = true;
		$ret['errors'][] = "Nincs bejelentkezve felhasználó!";
	}
	echo json_encode($ret);
	
	require_once('classes/close.php');
?>