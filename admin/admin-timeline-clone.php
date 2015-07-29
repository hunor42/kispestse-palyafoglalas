<?php
	require_once($base_path.'classes/checkframework.php');
	
	$clone_court = $_POST['clone_court'];
	$clone_from = $_POST['clone_from'];
	$clone_to = $_POST['clone_to'];
	
	$success = TimeUnit::myclone($clone_court,$clone_from,$clone_to,$selected_date);
	echo $success;
?>