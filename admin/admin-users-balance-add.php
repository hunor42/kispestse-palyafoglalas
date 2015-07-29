<?php
	require_once($base_path.'classes/checkframework.php');
	
	if(count($_POST)>0){
		$usertransaction = new UserTransaction($_POST);
		$usertransaction->user = $user;
		$usertransaction->type = 0;
		$usertransaction->admin_username = $admin_username;
		
		$errors = array();
		$valid = $usertransaction->is_valid();
		if($valid===true){
			$usertransaction->save();
		}else{
			$errors = $valid;
		}
		$smarty->assign('errors',$errors);
		
		$smarty->display('return.tpl');
	}
?>