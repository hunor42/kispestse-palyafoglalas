<?php
	error_reporting(E_ALL ^ E_NOTICE);
	ini_set('display_errors',1);
	
	require_once($base_path.'classes/checkframework.php');
	
	$sth = $DB->prepare('set names latin1;');
	$sth->execute();
	
	$sql = 'SELECT `login` as username, `pass` as `password`, `pass` as `password2`, `login` as full_name, email, phone FROM kispests_db.user WHERE (balance>0 OR (SELECT MAX(date) FROM kispests_db.reservation WHERE user_id=user.id)>UNIX_TIMESTAMP("2014-01-01 00:00:00"))';
	$sth = $DB->prepare($sql);
	$sth->execute();
	$users = array();
	foreach($sth->fetchAll() as $user){
		$tmp = new User($user);
		$tmp->set_phone_migrate($user['phone']);
		
		$users[] = $tmp;
	}
	$sth = $DB->prepare('set names utf8;');
	$sth->execute();
	
	foreach($users as $user){
		$is_valid = $user->is_valid();
		if($is_valid===true){
			$user->migrate();
			echo 'Sikeres migrálás: '.$user->username.'<br />';
		}else{
			echo 'Sikertelen migrálás: '.$user->username.'<br />';
		}
	}
?>