<?php
	error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED ^ E_STRICT);
	ini_set('display_errors',1);
	ini_set('log_errors',1);
	
	date_default_timezone_set('Europe/Budapest');
	
	// Konfiguracio
	require_once($base_path.$base_path.'classes/cfg.php');
	
	session_start();
	
	ob_start();
	
	// Enum osztály
	require_once($base_path.$base_path.'classes/enum.php');
	// Enumok
	require_once($base_path.$base_path.'classes/enums/__include.php');
	
	// Osztalyok beepitese
	// Properties
	require_once($base_path.'classes/base/Properties.php');
	// String osztály
	require_once($base_path.'classes/string.php');
	// Login osztály
	require_once($base_path.'classes/login.php');
	// PHPMailer
	require_once($base_path.'classes/phpmailer/class.phpmailer.php');
	// email
	require_once($base_path.'classes/email.php');
	// User
	require_once($base_path.'classes/User.php');
	//captcha
	require_once($base_path.'classes/captcha/simple-php-captcha.php');
	// Court
	require_once($base_path.'classes/Court.php');
	// Calendar
	require_once($base_path.'classes/Calendar.php');
	// UserTransaction
	require_once($base_path.'classes/UserTransaction.php');
	// TimeUnit
	require_once($base_path.'classes/TimeUnit.php');
	// Basket
	require_once($base_path.'classes/Basket.php');
	// Reservation
	require_once($base_path.'classes/Reservation.php');	
	
	// Portál osztály
	require_once($base_path.'classes/portal.php');

	// Database connection
	try {
		$DB = new PDO('mysql:host=localhost;dbname='.CFG::DBDATABASE, CFG::DBUSER, CFG::DBPASS);
		$sth=$DB->prepare('SET NAMES `utf8`');
		$sth->execute();
	} catch (PDOException $e) {
		print "Database error";
		print $e->getMessage();
		die();
	}
	
	$portal = new Portal();
?>