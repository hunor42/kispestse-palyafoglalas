<?php
	header("Access-Control-Allow-Origin: *");
    header('Content-Type: application/json');
	
    $basket = unserialize($_SESSION['basket']);
	if(!is_object($basket))
		$basket = new Basket();
	
	require_once('smarty/Smarty.class.php');
	// Smarty példányosítás
	$smarty_api = new Smarty;
	$smarty_api->assign('page_url',CFG::PAGE_URL);
?>