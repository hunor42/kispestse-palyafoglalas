<?php
	require_once($base_path.'classes/checkframework.php');

	switch($_GET['a2id']){
		case "modify":
			require_once('admin-users-modify.php');
			break;
		case "balance":
			require_once('admin-users-balance.php');
			break;
		default:
			if(isset($_GET['q']))
				$_SESSION['users_q'] = $_GET['q'];

			$q = $_SESSION['users_q'];

			if(is_numeric($_GET['page']))
				$_SESSION['admin_users_page']=$_GET['page'];
			if(!is_numeric($_SESSION['admin_users_page']))
				$_SESSION['admin_users_page'] = 1;
			$page = $_SESSION['admin_users_page'];
			$page_range = range(1,ceil(User::get_all_count($q)/User::PAGER));

			$users = User::get_all($page,$q);

			$smarty->assign('users',$users);
			$smarty->assign('page',$page);
			$smarty->assign('page_range',$page_range);

			$smarty->assign('q',$q);

			$smarty->display('admin-users.tpl');
	}
?>