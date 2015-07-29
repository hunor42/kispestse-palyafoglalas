<?php
	require_once($base_path.'classes/checkframework.php');
	
	$did = $_GET['did'];
	if($did!=""){
		$timeunit = TimeUnit::get_by_id($did);
	}
	
	if(is_object($timeunit)){
		$errors = array();
		$res = $timeunit->get_reservation();
		if(!is_null($res)){
			$valid = $res->is_freeable(true);
			if($valid===true){
				$res->free($admin_username);
			}else{
				$errors = $valid;
			}
			$smarty->assign('errors',$errors);
		}
		
		$smarty->display('return.tpl');
	}
?>