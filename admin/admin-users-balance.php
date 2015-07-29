<?php
	require_once($base_path.'classes/checkframework.php');
	
	$username = $_GET['did'];
	$user = User::get_by_username($username);
	if($user!==false){
		$smarty->assign('user',$user);
		switch($_GET['a3id']){
			case "add":
				require_once('admin-users-balance-add.php');
				break;
			default:
				$smarty->display('admin-users-balance.tpl');
		}
	}
?>