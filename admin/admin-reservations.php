<?php
	require_once($base_path.'classes/checkframework.php');
	
	switch($_GET['a2id']){
		case "free":
			$id = $_GET['did'];
			$reservation = Reservation::get_by_id($id);
			
			$errors = array();
			
			$reservation->load_user();
			$reservation->load_timeunit();
			$is_freeable = $reservation->is_freeable(true);
			if($is_freeable===true){
				$reservation->free($login->username);
			}else{
				$errors = $is_freeable;
			}
			$smarty->assign('errors',$errors);
			
			header('location: /admin/reservations/');
			die();
			break;
		default:
			$reservations = Reservation::get_all_actual();
			
			$smarty->assign('reservations',$reservations);
			
			$smarty->display('admin-reservations.tpl');
	}
?>