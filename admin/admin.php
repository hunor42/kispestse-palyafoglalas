<?php
	require($base_path.'classes/checkframework.php');

	$login=new Login('admin');
	$doLogin=$login->doLogin();
	if ($doLogin==1){
		$admin_username = $login->username;

		$smarty->assign('admin_username',$login->username);

		switch($_GET['aid']){
			case "migration":
				require_once('admin-migration.php');
				break;
			case "reservations":
				require_once('admin-reservations.php');
				break;
			case "users":
				require_once('admin-users.php');
				break;
			case "courts":
				require_once('admin-courts.php');
				break;
			case "timeline":
				require_once('admin-timeline.php');
				break;
			case "logout":
				require_once('admin-logout.php');
				break;
			case "report":
				require_once('admin-report.php');
				break;
			default:
				$smarty->display('admin.tpl');
		}
	}else{
		$smarty->assign('error',$doLogin);
		$smarty->assign('username',$_POST['login_username']);
		$smarty->assign('password',$_POST['login_password']);

		$smarty->display('admin-login.tpl');
	}
?>