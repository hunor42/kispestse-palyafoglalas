<?php
	require_once('classes/_loader.php');
	require_once('classes/init.php');
	
	$ret = array();
	
	$login = new Login('users');
	$doLogin=$login->doLogin();
	switch($doLogin){
		case 1:
			$ret['success'] = true;
			$ret['user'] = $login->obj->get_array();
			$basket->set_user($login->obj);
			break;
		case 2:
			$ret['success'] = false;
			$ret['errors']['username'][] = 'Hibás felhasználónév és/vagy jelszó!';
			break;
		case 3:
			$ret['success'] = false;
			$ret['errors']['username'][] = 'Nem aktivált felhasználó!';
			break;
		default:
			$ret['success'] = false;
			$ret['errors']['username'][] = 'Hibás felhasználónév és/vagy jelszó!';
			break;
	}
	
	echo json_encode($ret);
	
	require_once('classes/close.php');
?>