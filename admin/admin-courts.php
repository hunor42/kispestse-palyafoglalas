<?php
	require_once($base_path.'classes/checkframework.php');
	
	switch($_GET['a2id']){
		case "new":
			require_once('admin-courts-modify.php');
			break;
		case "modify":
			require_once('admin-courts-modify.php');
			break;
		default:
			$courts = Court::get_all();
			$smarty->assign('courts',$courts);
			
			$smarty->display('admin-courts.tpl');
	}
?>