<?php
	require_once('classes/_loader.php');
	require_once('classes/init.php');

	$ret = array();

	$login = new Login('users');
	$doLogin=$login->doLogin();
	if($doLogin===1){
		$ret['redirectToLogin'] = false;
		$logined_user = $login->obj;
		if(count($_POST)>0){
			$logined_user->set_data_for_change($_POST);
			$user = new User($_POST);

			$valid = $logined_user->is_valid();
			if($valid===true){
				$logined_user->save();
				$login->save_user_obj($logined_user);
				$ret['success'] = true;
			}else{
				$errors = $valid;
				$ret['errors'] = $errors;
				$ret['success'] = false;
			}
		}else{
			$ret['user'] = $logined_user->get_array();
			$ret['success'] = true;
		}
	}else{
		$ret['success'] = false;
		$ret['redirectToLogin'] = true;
		$ret['errors'][] = "Nincs bejelentkezve felhasználó!";
	}

	echo json_encode($ret);

	require_once('classes/close.php');
?>