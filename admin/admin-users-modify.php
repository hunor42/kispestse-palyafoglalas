<?php
	require_once($base_path.'classes/checkframework.php');
	
	$username = $_GET['did'];
	$user = User::get_by_username($username);
	if($user!==false){
		$smarty->assign('user',$user);
		if(count($_POST)>0){
			$errors = array();
			if($_POST['password']!=""){
				$user->set_data_for_changepassword($_POST);
				
				$valid = $user->is_valid();
				if($valid===true){
					$user->change_password();
				}else{
					$errors = $valid;
				}
			}else{
				$user->set_data_for_change($_POST);
				
				$valid = $user->is_valid();
				if($valid===true){
					$user->save();
				}else{
					$errors = $valid;
				}
			}
			$smarty->assign('errors',$errors);
			$smarty->display('return.tpl');
		}else{
			$smarty->display('admin-users-modify.tpl');
		}
	}
?>