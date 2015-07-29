<?php
	require_once($base_path.'classes/checkframework.php');
	
	$did = $_GET['did'];
	if($did!=""){
		$timeunit = TimeUnit::get_by_id($did);
	}
	
	if(is_object($timeunit)){
		$errors = array();
		$valid = $timeunit->is_deletable();
		if($valid===true){
			$timeunit->delete($admin_username);
		}else{
			$errors = $valid;
		}
		$smarty->assign('errors',$errors);
		
		$smarty->display('return.tpl');
	}
?>