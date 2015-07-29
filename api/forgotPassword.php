<?php
	require_once('classes/_loader.php');
	require_once('classes/init.php');

	$ret = array();

	if(count($_POST)>0){
		$username = $_POST['username'];

		$user = User::get_by_username($username);
		$errors = array();
		if($user===false){
			$errors['username'] = 'Nincs ilyen felhasználónévvel regisztrált felhasználónk!';
		}elseif(!$user->is_activated())
			$errors['username'] = 'Ez a felhasználó még nem aktivált!';

		if(count($errors)==0){
			require_once('classes/init_smarty.php');
			$password = $user->generate_password();

			$smarty->assign('user',$user);
			$smarty->assign('password',$password);
			$body=$smarty->fetch('emails/forgotpassword-body.tpl');
			$subject=$smarty->fetch('emails/forgotpassword-subject.tpl');

			$email = new Email($user->email,$subject,$body);
			$email->sendHtml();

			$ret['success'] = true;
		}else{
			$ret['success'] = false;
			$ret['errors'] = $errors;
		}
	}

	echo json_encode($ret);

	require_once('classes/close.php');
?>