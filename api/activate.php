<?php
	require_once('classes/_loader.php');
	require_once('classes/init.php');
	
	$ret = array();
	
	$key = $_GET['key'];
	
	$user = User::get_by_activation_key($key);
	if($user===false){
		$ret['success'] = false;
		$ret['errors']['key'][] = "Hibás kód!";
	}else{
		$ret['success'] = true;
		
		$user->activate();
		
		require_once('classes/init_smarty.php');
		
		$body=$smarty->fetch('emails/activate-body.tpl');
		$subject=$smarty->fetch('emails/activate-subject.tpl');
		
		$email = new Email($user->email,$subject,$body);
		$email->sendHtml();
	}
	
	echo json_encode($ret);
	
	require_once('classes/close.php');
?>