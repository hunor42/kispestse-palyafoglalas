<?php
	require_once($base_path.'classes/checkframework.php');
	
	if(isset($_GET['selected_date']))
		$_SESSION['selected_date'] = $_GET['selected_date'];
	if($_SESSION['selected_date']=="")
		$_SESSION['selected_date']=date('Y-m-d');
	$selected_date = $_SESSION['selected_date'];
	
	switch($_GET['a2id']){
		case "new":
			require_once('admin-timeline-modify.php');
			break;
		case "modify":
			require_once('admin-timeline-modify.php');
			break;
		case "delete":
			require_once('admin-timeline-delete.php');
			break;
		case "delete_reservation":
			require_once('admin-timeline-delete_reservation.php');
			break;
		case "deleteday":
			require_once('admin-timeline-deleteday.php');
			break;
		case "clone":
			require_once('admin-timeline-clone.php');
			break;
		case "competition":
			require_once('admin-timeline-competition.php');
			break;
		default:
			$times = Calendar::get_times();
			$timeunits = TimeUnit::get_day_timeunits_admin($selected_date);
			
			$courts = Court::get_all();
			$smarty->assign('courts',$courts);
			
			$smarty->assign('times',$times);
			$smarty->assign('timeunits',$timeunits);
			$smarty->assign('selected_date',$selected_date);
			
			$smarty->display('admin-timeline.tpl');
	}
?>