<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.icon.php
 * Type:     function
 * Name:     icon
 * Purpose:  show an icon
 * -------------------------------------------------------------
 */
function smarty_function_icon($params, &$smarty)
{
	$type=$params['type'];
	switch($type){
		case 'sorsolas_inactive':
			$title='sorsolás (inaktív)';
			break;
		case 'sorsolas':
			$title='sorsolás';
			break;
		case 'activate':
			$title='aktiválás';
			break;
		case 'ct':
			$title='a vásárlás CT-s';
			break;
		case 'edit':
			$title='módosítás';
			break;
		case 'icon':
			$title='információ';
			break;
		case "delete":
			$title='törlés';
			break;
		case "up":
			$title='fel';
			break;
		case "down":
			$title='le';
			break;
		case "newuser":
			$title='új vásárló';
			break;
		case "login":
			$title='bejelentkezés';
			break;
		case "coupon":
			$title='kuponok';
			break;
		case "unstock":
			$title='készlethiány';
			break;
		case "unstock_inactive":
			$title='készlethiány';
			break;
		case "unstock":
			$title='készleten';
			break;
		case "unstock_inactive":
			$title='készleten';
			break;
		default:
			$title='';
	}
    $output = '<img src="/media/images/icon_'.$type.'.png" alt="'.$title.'" title="'.$title.'" border="0" class="icon" />';
	return $output;
}
?>