<?php
	require_once($base_path.'classes/checkframework.php');
	
	$did = $_GET['did'];
	if($did!=""){
		$timeunit = TimeUnit::get_by_id($did);
	}else{
		$court = Court::get_by_id($_GET['court']);
		$timeunit = new TimeUnit(array(
									'date'=>$selected_date,
									'from'=>$_GET['from'],
									'court'=>$court,
									'price'=>$court->price,
									'available'=>1,
								));
	}
	
	if(count($_POST)>0){
		$timeunit->set_data_for_change($_POST);
		
		$errors = array();
		$valid = $timeunit->is_valid();
		if($valid===true){
			$timeunit->save();
		}else{
			$errors = $valid;
		}
		$smarty->assign('errors',$errors);
		
		$smarty->display('return.tpl');
	}else{
		$smarty->assign('timeunit',$timeunit);
		$smarty->display('admin-timeline-modify.tpl');
	}
?>