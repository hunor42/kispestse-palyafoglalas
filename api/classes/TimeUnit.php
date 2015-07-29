<?php
	class TimeUnitNotValid extends Exception{};
	class TimeUnitNotFound extends Exception{};
	class InvalidTimeString extends Exception{};
	
	class TimeUnit extends Properties{
		const TIMEUNIT_MINUNIT = 30;
		const PRERESERVE_LIMIT = 20;
		
		public function __construct($props,$get_court=true){
			global $DB;
			
			$props['from']=self::cut_to_minute($props['from']);
			$props['to']=self::cut_to_minute($props['to']);
			$this->props=$props;
			
			if(is_null($this->court)){
				$court_props = $this->get_connected_object_props('court');
				if(count($court_props)>1)
					$this->court = new Court($court_props);
			}
			
			if(is_null($this->court) && !is_null($this->court_id) && $get_court===true){
				$this->load_court();
			}
		}
		
		public function get_props(){
			return $this->props;
		}
		
		public function load_court(){
			$this->court = Court::get_by_id($this->court_id);
		}
		
		public function get_reservation(){
			global $DB;
			
			if($this->reserved_by!=""){
				$sth = $DB->prepare('SELECT * FROM reservations WHERE timeunit_id=:id ORDER BY add_date DESC LIMIT 1');
				$sth->bindParam(':id',$this->id,PDO::PARAM_INT);
				$sth->execute();
				$tmp = $sth->fetch(PDO::FETCH_ASSOC);
				
				$ret = new Reservation($tmp);
				$ret->timeunit = $this;
				$ret->load_user();
				
				return $ret;
			}
			return NULL;
		}
		
		public function is_deletable(){
			$errors = array();
			// regen nem lehetett torolni lefoglalt idosavot
			// de mar lehet... viszont biztonsagi okokbol nemlehet lefoglalni preserved idosavot
//			if(($this->reserved_by=="" && $this->is_prereserveable())===false)
			if($this->is_prereserveable()===false)
				$errors[] = "Már lefoglalt idősávot nem lehet törölni!";
			
			if(count($errors)>0)
				return $errors;
			
			$this->deletable = true;
			return true;
		}
		
		public function is_valid(){
			global $DB;
			
			$errors = array();
			
			if($this->reserved_by!="")
				$errors[] = "Már lefoglalt idősávot nem lehet módosítani!";
			else{
				if($this->date=="" || strtotime($this->date)===false)
					$errors[] = "A dátum megadása kötelező!";
				if($this->from=="")
					$errors[] = "A kezdési időpont megadása kötelező!";
				if($this->to=="")
					$errors[] = "A vég időpont megadása kötelező!";
				if(!is_object($this->court))
					$errors[] = "A pálya megadása kötelező!";
				if(!is_numeric($this->price))
					$errors[] = "Az idősáv árának megadása kötelező és csak szám lehet!";
				
				$sql = '
					SELECT
						COUNT(*) as c
					FROM timeunits
					WHERE
						id!=:id AND
						court_id=:court AND
						`date`=:date AND
						(
							((:from>`from` AND :from<`to`) OR (:to>`from` AND :to<`to`)) OR
							(`from`>=:from AND `to`<=:to)
						)
						
				';
				$id = ($this->id!="" ? $this->id : "");
				$sth = $DB->prepare($sql);
				$sth->bindParam(':id',$id,PDO::PARAM_STR);
				$sth->bindParam(':date',$this->date,PDO::PARAM_STR);
				$sth->bindParam(':court',$this->court->id,PDO::PARAM_INT);
				$sth->bindParam(':from',$this->from,PDO::PARAM_STR);
				$sth->bindParam(':to',$this->to,PDO::PARAM_STR);
				$sth->execute();
				if($sth->fetchColumn()>0)
					$errors[] = "A kért idősáv ütközés miatt nem rögzíthető!";
			}
			
			if(count($errors)>0)
				return $errors;
			
			$this->available = $this->available=="1" ? 1 : 0;
			
			$this->valid = true;
			return true;
		}
		
		public function save(){
			if($this->valid!==true)
				throw new TimeUnitNotValid("Please call is_valid() before saving!");
			
			global $DB;
			
			$sql = '
				INSERT INTO timeunits
				(`id`,`date`,`court_id`,`from`,`to`,`price`,`comment`,`available`,`add_date`)
				VALUES(:id,:date,:court,:from,:to,:price,:comment,:available,NOW())
				ON DUPLICATE KEY UPDATE `from`=:from, `to`=:to, `price`=:price, `comment`=:comment, `available`=:available
			';
			$sth = $DB->prepare($sql);
			$sth->bindParam(':id',$this->id,PDO::PARAM_STR);
			$sth->bindParam(':date',$this->date,PDO::PARAM_STR);
			$sth->bindParam(':court',$this->court->id,PDO::PARAM_INT);
			$sth->bindParam(':from',$this->from,PDO::PARAM_STR);
			$sth->bindParam(':to',$this->to,PDO::PARAM_STR);
			$sth->bindParam(':price',$this->price,PDO::PARAM_INT);
			$sth->bindParam(':comment',$this->comment,PDO::PARAM_STR);
			$sth->bindParam(':available',$this->available,PDO::PARAM_INT);
			$sth->execute();
		}
		public function delete($admin_username){
			if($this->deletable!==true)
				throw new TimeUnitNotValid("Please call is_deletable() before deleting!");
			
			global $DB;
			
			if($this->reserved_by!=""){
				// levan foglalva az idosav...
				$res = $this->get_reservation();
				$res->is_freeable(true);
				$res->free($admin_username);
			}
			
			$sql = 'DELETE FROM timeunits WHERE id=:id';
			$sth = $DB->prepare($sql);
			$sth->bindParam(':id',$this->id,PDO::PARAM_STR);
			$sth->execute();
		}
		
		public function is_prereserveable(){
			return (is_null($this->prereserved_expire) || $this->prereserved_expire=="" || time()>strtotime($this->prereserved_expire));
		}
		
		public function prereserve(){
			global $DB;
			
			$expire = date('Y-m-d H:i:s',strtotime('now + '.self::PRERESERVE_LIMIT.' minute'));
			
			$this->prereserved_expire = $expire;
			
			$sth = $DB->prepare('UPDATE timeunits SET prereserved_expire=:prereserved_expire WHERE id=:id');
			$sth->bindParam(':prereserved_expire',$this->prereserved_expire,PDO::PARAM_STR);
			$sth->bindParam(':id',$this->id,PDO::PARAM_STR);
			$sth->execute();
		}
		
		public function free_prereserve(){
			global $DB;
			
			$sth = $DB->prepare('UPDATE timeunits SET prereserved_expire=NULL WHERE id=:id');
			$sth->bindParam(':id',$this->id,PDO::PARAM_STR);
			$sth->execute();
		}
		
		public function set_data_for_change($data){
			if(!isset($data['available']))
				$data['available']="0";
			foreach($data as $key => $value){
				if($key!="id")
					$this->props[$key] = $value;
			}
		}
		
		public function get_tos(){
			$ret = array();
			
			$minutes = self::convert_to_minutes($this->from);
			for($i=$minutes+self::TIMEUNIT_MINUNIT;$i<=24*60;$i+=self::TIMEUNIT_MINUNIT){
				$ret[]=self::convert_from_minutes($i);
			}
			
			return $ret;
		}
		
		public function in_array_of_times(){
			$ret = array();
			
			$from_minutes = self::convert_to_minutes($this->from);
			$to_minutes = self::convert_to_minutes($this->to);
			for($i=$from_minutes;$i<$to_minutes;$i+=self::TIMEUNIT_MINUNIT){
				$ret[self::convert_from_minutes($i)]=$this;
			}
			
			return $ret;
			
		}
		
		public function get_blocks_num(){
			$ret = array();
			
			$from_minutes = self::convert_to_minutes($this->from);
			$to_minutes = self::convert_to_minutes($this->to);
			
			return (($to_minutes-$from_minutes)/self::TIMEUNIT_MINUNIT);
			
		}
		
		public function get_status(){
			if($this->reserved_by!="")
				return "reserved";
			elseif($this->available==1){
				if($this->is_prereserveable())
					return "reservable";
				else
					return "prereserved";
			}elseif($this->available==0)
				return "unavailable";
		}
		
		public function get_timetd_class($time){
			$ret = array();
			if($this->reserved_by!="")
				$ret[] = "booked";
			elseif($this->available==1)
				$ret[] = "available";
			elseif($this->available==0)
				$ret[] = "unavailable";
			
			$tmp = array_keys($this->in_array_of_times());
			$last_time = array_pop($tmp);
			if($time==$last_time)
				$ret[] = "end";

			return implode(' ',$ret);
		}
		
		public function get_array(){
			$ret = array();
			
			$ret['interval'] = $this->from.' - '.$this->to; 
			$ret['intervalBlocks'] = $this->get_blocks_num();
			$ret['reservationId'] = $this->id;
			$ret['date'] = $this->date;
			$ret['status'] = $this->get_status();
			if(!is_null($this->user))
				$discount = $this->user->discount;
			else
				$discount = 0;
			$ret['price'] = round($this->price*((100-$discount)/100));
			$ret['orig_price'] = $this->price;
			$ret['reservedBy'] = $this->reserved_by;
			$ret['prereservedExpire'] = $this->prereserved_expire;
			$ret['message'] = $this->comment;
			if(!is_null($this->court))
				$ret['court'] = $this->court->get_array();
			
			return $ret;
		}
		
		public static function get_by_id($id){
			global $DB;
			
			$sth = $DB->prepare('SELECT * FROM timeunits WHERE id=:id');
			$sth->bindParam(':id',$id,PDO::PARAM_STR);
			$sth->execute();
			
			$props = $sth->fetch();
			if($props!==false){
				return new TimeUnit($props);
			}else
				throw new TimeUnitNotFound();
		}
		
		public static function convert_to_minutes($time){
			$parts = explode(':',$time);
			if(count($parts)>3 || count($parts)<2)
				throw new InvalidTimeString();
			
			$hour = $parts[0];
			$minute = $parts[1];
			
			$ret = ($hour*60+$minute);
			return $ret;
		}
		
		public static function convert_from_minutes($time){
			if(!is_numeric($time))
				throw new InvalidTimeString();
			
			$ret = str_pad(floor($time/60),2,"0",STR_PAD_LEFT).":".str_pad(($time%60),2,"0",STR_PAD_LEFT);
			return $ret;
		}
		
		public static function cut_to_minute($time){
			return implode(':',array_slice(explode(':',$time),0,2));
		}
		
		public static function get_day_timeunits($date,$user=null){
			global $DB;
			
			$courts = Court::get_all();
			
			$sql = '
				SELECT
					*
				FROM timeunits
				WHERE
					date=:date
				ORDER BY `date`, `from`
			';
			$sth = $DB->prepare($sql);
			$sth->bindParam(':date',$date,PDO::PARAM_STR);
			$sth->execute();
			
			$timeunits = array();
			foreach($sth->fetchAll() as $tmp){
				$timeunit = new TimeUnit($tmp,false);
				$timeunit->user=$user;
				
				if(!is_array($timeunits[$timeunit->court_id]))
					$timeunits[$timeunit->court_id] = array();
				
				$timeunits[$timeunit->court_id][] = $timeunit;
			}
			
			$ret = array();
			$ret['date'] = $date;
			$ret['fields'] = array();
			$i=0;
			foreach($courts as $court){
				$j=0;
				$ret['fields'][$i] = array();
				$ret['fields'][$i]['id'] = $court->id;
				$ret['fields'][$i]['title'] = $court->name;
				$ret['fields'][$i]['subtitle'] = $court->subtitle;
				$ret['fields'][$i]['intervals'] = array();
				if(is_array($timeunits[$court->id])){
					foreach($timeunits[$court->id] as $timeunit){
						$ret['fields'][$i]['intervals'][$j] = $timeunit->get_array();
						$j++;
					}
				}
				$i++;
			}
			
			return $ret;
		}

		public static function get_day_timeunits_admin($date){
			global $DB;
			
			$sql = '
				SELECT
					*
				FROM timeunits
				WHERE
					date=:date
			';
			$sth = $DB->prepare($sql);
			$sth->bindParam(':date',$date,PDO::PARAM_STR);
			$sth->execute();
			
			$ret = array();
			
			foreach($sth->fetchAll() as $tmp){
				$timeunit = new TimeUnit($tmp,false);
				
				if(!is_array($ret[$timeunit->court_id]))
					$ret[$timeunit->court_id] = array();
				
				$ret[$timeunit->court_id] += $timeunit->in_array_of_times();
			}
			
			return $ret;
		}
		
		public static function get_by_date_and_from($date,$from,$court_id){
			global $DB;
			
			$sql = '
				SELECT
					*
				FROM timeunits
				WHERE
					`date`=:date AND
					`from`=:from AND
					`court_id`=:court_id
			';
			$sth = $DB->prepare($sql);
			$sth->bindParam(':date',$date,PDO::PARAM_STR);
			$sth->bindParam(':from',$from,PDO::PARAM_STR);
			$sth->bindParam(':court_id',$court_id,PDO::PARAM_STR);
			$sth->execute();
			$tmp = $sth->fetch();
			$timeunit = new TimeUnit($tmp,false);
			
			return $timeunit;
		}
		
		public static function deleteday($courts,$base_date){
			global $DB;
			
			if(!is_array($courts) || count($courts)==0)
				return 'Legalább egy pályát meg kell adnod a klónozáshoz!';
			$sql = '
				SELECT
					COUNT(*) as c
				FROM timeunits
				WHERE
					date=:date AND
					court_id IN ('.implode(',',$courts).') AND
					((reserved_by IS NOT NULL AND reserved_by!="") OR
					(prereserved_expire IS NOT NULL AND NOW()<prereserved_expire))
			';
			$sth = $DB->prepare($sql);
			$sth->bindParam(':date',$base_date,PDO::PARAM_STR);
			$sth->execute();
			if($sth->fetchColumn()>0)
				return 'A kijelölt napon már van lefoglalt idősáv, így nem törölhető!';
			
			$sth = $DB->prepare('DELETE FROM timeunits WHERE date=:date AND court_id IN ('.implode(',',$courts).')');
			$sth->bindParam(':date',$base_date,PDO::PARAM_STR);
			$sth->execute();
			
			return 'ok';
		}
		
		public static function myclone($courts,$from,$to,$base_date){
			global $DB;
			
			$is_valid = true;
			$from_date = strtotime($from);
			$to_date = strtotime($to);
			if(!is_array($courts) || count($courts)==0)
				return 'Legalább egy pályát meg kell adnod a klónozáshoz!';
			elseif($from_date===false)
				return 'Hibás dátum (tól)';
			elseif($to_date===false)
				return 'Hibás dátum (ig)';
			else{
				$from_date_str = date('Y-m-d',$from_date);
				$to_date_str = date('Y-m-d',$to_date);
				$sql = '
					SELECT
						COUNT(*) as c,
						courts.*,
						timeunits.date
					FROM timeunits
					LEFT JOIN courts ON (timeunits.court_id=courts.id)
					WHERE
						`date` BETWEEN :from AND :to AND
						court_id IN ('.implode(',',$courts).')
					GROUP BY court_id
				';
				$sth = $DB->prepare($sql);
				$sth->bindParam(':from',$from_date_str,PDO::PARAM_STR);
				$sth->bindParam(':to',$to_date_str,PDO::PARAM_STR);
				$sth->execute();
				
				$tmps = $sth->fetchAll(PDO::FETCH_ASSOC);
				$ret = array();
				foreach($tmps as $tmp){
					if($tmp['c']>0){
						$ret[] = 'A '.$tmp['name'].' ('.$tmp['subtitle'].') pályán a kiválasztott intervallum egyik napján ('.$tmp['date'].') már van idősáv megadva, ezért a klónozás nem lehetséges.';
					}
				}
				if(count($ret)>0)
					return implode("\n",$ret);
			}
			
			for($i=$from_date;$i<=$to_date;$i+=60*60*24){
				$date = date('Y-m-d',$i);
				$sql = '
					INSERT INTO timeunits
					(`court_id`,`date`,`from`,`to`,`price`,`comment`,`available`,`add_date`)
					SELECT
						`court_id`,
						:date,
						`from`,
						`to`,
						`price`,
						`comment`,
						`available`,
						NOW()
					FROM timeunits
					WHERE 
						timeunits.court_id IN ('.implode(',',$courts).') AND
						timeunits.date=:base_date
				';
				$sth = $DB->prepare($sql);
				$sth->bindParam(':date',$date,PDO::PARAM_STR);
				$sth->bindParam(':base_date',$base_date,PDO::PARAM_STR);
				$sth->execute();
			}
			
			return 'Sikeres klónozás!';
		}
		
		public static function competition_create($courts,$name,$from,$to,$fromt,$tot,$reservations_delete=false,$admin_username){
			global $DB;
			
			$ret = array();
			if(!is_array($courts) || count($courts)==0){
				$ret['success'] = false;
				$ret['error'] = 'Nincs kiválasztva pálya!';
				$ret['error_type'] = 1;
			}elseif($name==""){
				$ret['success'] = false;
				$ret['error'] = 'A rendezvény nevének megadása kötelező!';
				$ret['error_type'] = 2;
			}elseif(strtotime($to)<strtotime($from)){
				$ret['success'] = false;
				$ret['error'] = 'A dátum intervallum hibás!';
				$ret['error_type'] = 3;
			}elseif(TimeUnit::convert_to_minutes($tot)<=TimeUnit::convert_to_minutes($fromt)){
				$ret['success'] = false;
				$ret['error'] = 'Az idő intervallum hibás!';
				$ret['error_type'] = 4;
			}else{
				$where = '
					(timeunits.date BETWEEN :from_date AND :to_date) AND 
					(
						(timeunits.from>=:from AND timeunits.from<:to)
							OR
						(timeunits.to>:from AND timeunits.to<=:to)
					) AND
					timeunits.court_id IN ('.implode(',',$courts).')
				';
				$sql = '
					SELECT
						COUNT(*) as c
					FROM timeunits
					WHERE
						'.$where.' AND
						reserved_by IS NOT NULL AND
						1=1
				';
				$sth = $DB->prepare($sql);
				$sth->bindParam(':from_date',$from,PDO::PARAM_STR);
				$sth->bindParam(':to_date',$to,PDO::PARAM_STR);
				$sth->bindParam(':from',$fromt,PDO::PARAM_STR);
				$sth->bindParam(':to',$tot,PDO::PARAM_STR);
				$sth->execute();
				$c = $sth->fetchColumn();
				if($c>0 && $reservations_delete===false){
					$ret['success'] = false;
					$ret['error'] = 'A kijelölt időszakban van lefoglalt idősáv. A foglalások törléséhez pipálja be a "foglalások törlése" jelölőnégyzetet.';
					$ret['error_type'] = 5;
				}else{
					if($reservations_delete===true){
						// foglalások törlése
						$sql = '
							SELECT
								reservations.*
							FROM reservations
							LEFT JOIN timeunits ON (reservations.timeunit_id=timeunits.id)
							WHERE
								'.$where.' AND
								reserved_by IS NOT NULL AND
								1=1
						';
						$sth = $DB->prepare($sql);
						$sth->bindParam(':from_date',$from,PDO::PARAM_STR);
						$sth->bindParam(':to_date',$to,PDO::PARAM_STR);
						$sth->bindParam(':from',$fromt,PDO::PARAM_STR);
						$sth->bindParam(':to',$tot,PDO::PARAM_STR);
						$sth->execute();
						foreach($sth->fetchAll() as $tmp){
							$res = new Reservation($tmp);
							$res->load_timeunit();
							$res->load_user();
							$res->is_freeable(true);
							$res->free($admin_username);
						}
					}
					$sql = '
						UPDATE timeunits
						SET
							comment=:name,
							available=0
						WHERE
							'.$where.'
					';
					$sth = $DB->prepare($sql);
					$sth->bindParam(':name',$name,PDO::PARAM_STR);
					$sth->bindParam(':from_date',$from,PDO::PARAM_STR);
					$sth->bindParam(':to_date',$to,PDO::PARAM_STR);
					$sth->bindParam(':from',$fromt,PDO::PARAM_STR);
					$sth->bindParam(':to',$tot,PDO::PARAM_STR);
					$sth->execute();
					$ret['success'] = true;
				}
			}
			
			return $ret;
		}
	}
?>