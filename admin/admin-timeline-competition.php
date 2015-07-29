<?php
	require_once($base_path.'classes/checkframework.php');
	
	$clone_court = $_POST['clone_court'];
	$competition_name = $_POST['competition_name'];
	$competition_from = $_POST['competition_from'];
	$competition_to = $_POST['competition_to'];
	$competition_fromt = $_POST['competition_fromt'];
	$competition_tot = $_POST['competition_tot'];
	$confirm = $_POST['confirm'];
	
	$ret = TimeUnit::competition_create($clone_court,$competition_name,$competition_from,$competition_to,$competition_fromt,$competition_tot,($confirm=="true"),$admin_username);
	echo json_encode($ret);
?>