<?php
	require_once($base_path.'classes/checkframework.php');
	
	$did = $_GET['did'];
	if($did!="")
		$court = Court::get_by_id($did);
	
	if(count($_POST)>0){
		if($did=="")
			$court = new Court($_POST);
		else
			$court->set_data_for_change($_POST);
		
		$errors = array();
		$valid = $court->is_valid();
		if($valid===true){
			$court->save();
		}else{
			$errors = $valid;
		}
		$smarty->assign('errors',$errors);
		
		$smarty->display('return.tpl');
	}else{
		$smarty->assign('court',$court);
		$smarty->display('admin-courts-modify.tpl');
	}
?>