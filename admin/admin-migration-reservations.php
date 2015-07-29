<?php
	error_reporting(E_ALL ^ E_NOTICE);
	ini_set('display_errors',1);
	
	require_once($base_path.'classes/checkframework.php');
	
	function reservation_migrate($timeunit_id,$username,$price,$add_date){
		global $DB;
		
		$sql = 'INSERT INTO reservations (`timeunit_id`,`username`,`price`,`status`,`last_status_update`,`add_date`) VALUES(:timeunit_id,:username,:price,1,:add_date,:add_date)';
		$sth = $DB->prepare($sql);
		$sth->bindParam(':timeunit_id',$timeunit_id,PDO::PARAM_INT);
		$sth->bindParam(':username',$username,PDO::PARAM_STR);
		$sth->bindParam(':price',$price,PDO::PARAM_STR);
		$sth->bindParam(':add_date',$add_date,PDO::PARAM_STR);
		$sth->execute();
		
		$sql = 'UPDATE timeunits SET reserved_by=:reserved_by WHERE id=:timeunit_id';
		$sth = $DB->prepare($sql);
		$sth->bindParam(':timeunit_id',$timeunit_id,PDO::PARAM_INT);
		$sth->bindParam(':reserved_by',$username,PDO::PARAM_STR);
		$sth->execute();
	}
	
	$sth = $DB->prepare('set names latin1;');
	$sth->execute();
	
	$sql = '
		SELECT
			kispests_db.user.login,
			kispests_db.reservation.date,
			kispests_db.reservation.palya,
			kispests_db.reservation.price,
			kispests_db.reservation.created
		FROM kispests_db.`reservation`
		LEFT JOIN kispests_db.user ON (kispests_db.reservation.user_id=kispests_db.user.id)
		WHERE
			kispests_db.reservation.`date` > UNIX_TIMESTAMP( "2015-01-01 00:00:00" ) AND
			kispests_db.user.login!="uszoda"
	';
	$sth = $DB->prepare($sql);
	$sth->execute();
	$reservations = array();
	foreach($sth->fetchAll() as $res){
		$date = date("Y-m-d",$res['date']);
		$username = $res['login'];
		$court_id = $res['palya'];
		// 3-as pály az igazából 6-os pálya, 4-es az 7-es, 5-ös az 8-as
		if($court_id>=3)$court_id+=3;
		$price = $res['price'];
		$from = date("H:i",$res['date']-(2*60*60));
		$add_date = date("Y-m-d H:i:s",$res['created']);
		
		$timeunit = TimeUnit::get_by_date_and_from($date,$from,$court_id);
		
		reservation_migrate($timeunit->id,$username,$price,$add_date);
		
		echo '"'.$username.'";"'.$date.'";"'.$from.'";"'.$price.'";"'.$court_id.'";"'.$timeunit->id.'"'."\n";
		
		$two_hour_timeunits = array('08:00'=>'09:00','10:00'=>'11:00','12:00'=>'13:00');
		
		if(in_array($from,array_keys($two_hour_timeunits))){
			$next_hour = $two_hour_timeunits[$from];
			$timeunit = TimeUnit::get_by_date_and_from($date,$next_hour,$court_id);
			echo '"'.$username.'";"'.$date.'";"'.$next_hour.'";"'.$price.'";"'.$court_id.'";"'.$timeunit->id.'";"2ND HOUR"'."\n";
			reservation_migrate($timeunit->id,$username,$price,$add_date);
		}
		
	}
?>