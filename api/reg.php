<?php
	require_once('classes/_loader.php');
	require_once('classes/init.php');

	$ret = array();
	if(count($_POST)>0){
		$user = new User($_POST);

		$errors = array();
		$valid = $user->is_valid(true);
		$valid_captcha = (strtolower($_SESSION['captcha']['code'])==strtolower($_POST['captcha']));
		if($valid_captcha && $valid===true){
			$user->save();

			require_once('classes/init_smarty.php');
			$smarty->assign('user',$user);

			$body=$smarty->fetch('emails/reg-body.tpl');
			$subject=$smarty->fetch('emails/reg-subject.tpl');

			$email = new Email($user->email,$subject,$body);
			$email->sendHtml();
			$ret['success'] = true;
		}else{
			if($valid_captcha!==true)
				$errors['captcha'][] = 'Hibás biztonsági kód!';
			if(is_array($valid))
				$errors = array_merge($errors,$valid);
			$ret['errors'] = $errors;
			$ret['success'] = false;
		}
	}else{
		$_SESSION['captcha'] = simple_php_captcha();
		$ret['captcha'] = $_SESSION['captcha']['image_src'];
	}

	echo json_encode($ret);

	require_once('classes/close.php');
?>