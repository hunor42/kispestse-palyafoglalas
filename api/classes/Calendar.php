<?php
	class Calendar{
		public function __construct(){
		}
		
		public static function get_days(){
			return array('hétfő','kedd','szerda','csütörtök','péntek','szombat','vasárnap');
		}
		
		public static function get_times(){
			$range = range(1,48);
			$ret = array();
			foreach($range as $r){
				$all_minutes_from = ($r-1)*30;
				$all_minutes_to = $r*30;
				
				$hour_from = floor($all_minutes_from/60);
				$minute_from = $all_minutes_from%60;
				
				$hour_to = floor($all_minutes_to/60);
				$minute_to = $all_minutes_to%60;
				
				$from_str = str_pad($hour_from, 2, "0", STR_PAD_LEFT).':'.str_pad($minute_from, 2, "0", STR_PAD_LEFT);
				$to_str = str_pad($hour_to, 2, "0", STR_PAD_LEFT).':'.str_pad($minute_to, 2, "0", STR_PAD_LEFT);
				
				$ret[] = array($from_str, $to_str);
			}
			return $ret;
		}
	}
?>