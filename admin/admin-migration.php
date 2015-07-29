<?php
	require_once($base_path.'classes/checkframework.php');
	
	switch($_GET['a2id']){
		case "users":
			require_once('admin-migration-users.php');
			break;
		case "reservations":
			require_once('admin-migration-reservations.php');
			break;
	}
?>