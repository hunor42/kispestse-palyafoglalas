<?php
	error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED ^ E_STRICT);
	ini_set('display_errors',1);
	ini_set('log_errors',1);
	
	$base_path = '../api/';
	
	require_once($base_path.'classes/_loader.php');
	
	require_once('smarty/Smarty.class.php');
	// Smarty példányosítás (API)
	$smarty_api = new Smarty;
	$smarty_api->template_dir=$base_path.'templates';
	$smarty_api->assign('page_url',CFG::PAGE_URL);
	
	// Smarty
	require_once('smarty/Smarty.class.php');
	// Smarty példányosítás
	$smarty = new Smarty;
	
	$smarty->assign('page_url',CFG::PAGE_URL);
	
	$oid = $_GET['oid'];
	if($oid=='')
		$oid='admin';
	
	switch($oid){
		case 'admin':
			require_once('admin.php');
			break;
	}
?>