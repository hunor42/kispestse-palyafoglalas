<?php
	class ReservationNotValid extends Exception{};
	class ReservationNotFound extends Exception{};
	class ReservationInvalidParam extends Exception{};
	
	class Reservation extends Properties{
		const FREE_TIME = 24; // a foglalás ennyi órával a kezdési időpont előtt mondható le
		
		public function __construct($props=array()){
			global $DB;
			
			$this->props=$props;
			
			if(is_null($this->timeunit)){
				$timeunit_props = $this->get_connected_object_props('timeunit');
				$this->timeunit = new TimeUnit($timeunit_props);
				
				$user_props = $this->get_connected_object_props('user');
				$this->user = new User($user_props);
			}
		}
		
		public function get_props(){
			return $this->props;
		}
		
		public function load_timeunit(){
			global $DB;
			
			$sth = $DB->prepare('SELECT * FROM timeunits WHERE id=:timeunit_id');
			$sth->bindParam(':timeunit_id',$this->props['timeunit_id'],PDO::PARAM_INT);
			$sth->execute();
			$ret = $sth->fetch(PDO::FETCH_ASSOC);
			
			$this->timeunit = new TimeUnit($ret);
		}
		
		public function load_user(){
			global $DB;
			
			$sth = $DB->prepare('SELECT * FROM users WHERE username=:username');
			$sth->bindParam(':username',$this->props['username'],PDO::PARAM_STR);
			$sth->execute();
			$ret = $sth->fetch(PDO::FETCH_ASSOC);
			
			$this->user = new User($ret);
		}
		
		public function is_valid(){
			global $DB;
			
			$errors = array();
			
			if(!is_numeric($this->price))
				$errors[] = "A foglalás árának megadása kötelező és csak szám lehet!";
			if(!is_object($this->user))
				$errors[] = "A felhasználó megadása kötelező!";
			elseif($this->user->get_balance()<$this->price)
				$errors[] = "A felhasználónak nincs elég egyenlege a foglalás rögzítéséhez!";
			
			if(!is_numeric($this->timeunit_id))
				$errors[] = "Az idősáv azonosítójának megadása kötelező és csak szám lehet!";
			
			$sth = $DB->prepare('SELECT COUNT(*) FROM reservations WHERE timeunit_id=:timeunit_id AND status=1');
			$sth->bindParam(':timeunit_id',$this->timeunit_id,PDO::PARAM_INT);
			$sth->execute();
			if($sth->fetchColumn()>0)
				$errors[] = "Az egyik idősáv amit beraktál a kosárba már foglalt!";
			
			if(count($errors)>0)
				return $errors;
			
			$this->valid = true;
			
			return true;
		}
		
		public function is_freeable($admin=false){
			$ret = array();
			
			$start = strtotime($this->timeunit->date.' '.$this->timeunit->from);
			if($this->status!="1")
				$ret[] = 'Ez a foglalás már le lett mondva!';
			if($admin===false && (($start-time()) < 60*60*self::FREE_TIME))
				$ret[] = 'Ez a foglalás már nem mondható le!';
			
			if(count($ret)>0)
				return $ret;
			
			$this->freeable = true;
			
			return true;
		}
		
		public function free($admin_username=null){
			global $DB;
			global $base_path;
			global $smarty_api;
			
			if($this->freeable!==true)
				throw new ReservationNotValid("Please call is_freeable() before free!");
			
			$sql = '
				UPDATE reservations
				SET status=2
				WHERE id=:id
			';
			$sth = $DB->prepare($sql);
			$sth->bindParam(':id',$this->id,PDO::PARAM_INT);
			$sth->execute();
			
			$sql = 'UPDATE timeunits SET reserved_by=NULL, prereserved_expire=NULL WHERE id=:timeunit_id';
			$sth = $DB->prepare($sql);
			$sth->bindParam(':timeunit_id',$this->timeunit_id,PDO::PARAM_INT);
			$sth->execute();
			
			$usertransaction = new UserTransaction();
			$usertransaction->user = User::get_by_username($this->username);
			$usertransaction->type = 2;
			$usertransaction->object_id = $this->id;
			$usertransaction->value = $this->price;
			if(!is_null($admin_username))
				$usertransaction->admin_username = $admin_username;
			
			$errors = array();
			$valid = $usertransaction->is_valid();
			if($valid===true){
				$usertransaction->save();
			}
			
			$smarty_api->assign('user',$this->user);
			$smarty_api->assign('reservation',$this);
			$smarty_api->assign('admin_username',$admin_username);

			$body=$smarty_api->fetch('emails/reservation_free-body.tpl');
			$subject=$smarty_api->fetch('emails/reservation_free-subject.tpl');

			$email = new Email($this->user->email,$subject,$body);
			$email->sendHtml();
			
			if(CFG::ADMIN_RESERVEFREE_EMAIL!=""){
				$body=$smarty_api->fetch('emails/admin-reservation_free-body.tpl');
				$subject=$smarty_api->fetch('emails/admin-reservation_free-subject.tpl');
				
				$email = new Email(CFG::ADMIN_RESERVEFREE_EMAIL,$subject,$body);
				$email->sendHtml();
			}
		}
		
		public function reserve(){
			global $base_path;
			global $smarty_api;
			
			if($this->valid!==true)
				throw new ReservationNotValid("Please call is_valid() before saving!");
			
			global $DB;
			
			$sql = '
				INSERT INTO reservations
				(`timeunit_id`,`username`,`price`,`status`,`add_date`)
				VALUES(:timeunit_id,:username,:price,1,NOW())
			';
			$sth = $DB->prepare($sql);
			$sth->bindParam(':timeunit_id',$this->timeunit_id,PDO::PARAM_INT);
			$sth->bindParam(':username',$this->user->username,PDO::PARAM_STR);
			$sth->bindParam(':price',$this->price,PDO::PARAM_INT);
			$sth->execute();
			
			$reservation_id = $DB->lastInsertId();
			
			$sql = 'UPDATE timeunits SET reserved_by=:reserved_by,prereserved_expire=NULL WHERE id=:timeunit_id';
			$sth = $DB->prepare($sql);
			$sth->bindParam(':timeunit_id',$this->timeunit_id,PDO::PARAM_INT);
			$sth->bindParam(':reserved_by',$this->user->username,PDO::PARAM_STR);
			$sth->execute();
			
			$usertransaction = new UserTransaction();
			$usertransaction->user = $this->user;
			$usertransaction->type = 1;
			$usertransaction->object_id = $reservation_id;
			$usertransaction->value = 0-$this->price;
			
			$valid = $usertransaction->is_valid();
			if($valid===true){
				$usertransaction->save();
			}
			
			$smarty_api->assign('user',$this->user);
			$smarty_api->assign('reservation',$this);

			$body=$smarty_api->fetch('emails/reservation-body.tpl');
			$subject=$smarty_api->fetch('emails/reservation-subject.tpl');
			
			$email = new Email($this->user->email,$subject,$body);
			$email->sendHtml();
			
			if(CFG::ADMIN_RESERVE_EMAIL!=""){
				$body=$smarty_api->fetch('emails/admin-reservation-body.tpl');
				$subject=$smarty_api->fetch('emails/admin-reservation-subject.tpl');
				
				$email = new Email(CFG::ADMIN_RESERVE_EMAIL,$subject,$body);
				$email->sendHtml();
			}
		}
		
		public function get_array(){
			$ret = array();
			$ret['id'] = $this->id;
			$ret['status'] = $this->status;
			$ret['username'] = $this->username;
			$ret['price'] = $this->price;
			$ret['add_date'] = $this->add_date;
			$ret['last_status_update'] = $this->last_status_update;
			$ret['timeunit'] = $this->timeunit->get_array();
			if(!is_null($this->timeunit->court))
				$ret['court'] = $this->timeunit->court->get_array();
			
			return $ret;
		}
		
		
		public static function get_by_id($id){
			global $DB;
			
			$sth = $DB->prepare('SELECT * FROM reservations WHERE id=:id');
			$sth->bindParam(':id',$id,PDO::PARAM_STR);
			$sth->execute();
			
			$props = $sth->fetch();
			if($props!==false){
				return new Reservation($props);
			}else
				throw new ReservationNotFound();
		}
		
		public static function get_all_actual(){
			global $DB;
			
			$sql = '
				SELECT
					reservations.*,
					users.username as user_username,
					users.full_name as user_full_name,
					users.email as user_email,
					users.phone as user_phone,
					users.birthday as user_birthday,
					users.status as user_status,
					users.activation_key as user_activation_key,
					users.last_login as user_last_login,
					users.reg_date as user_reg_date,
					timeunits.id as timeunit_id,
					timeunits.date as timeunit_date,
					timeunits.from as timeunit_from,
					timeunits.to as timeunit_to,
					timeunits.price as timeunit_price,
					timeunits.comment as timeunit_comment,
					timeunits.available as timeunit_available,
					timeunits.add_date as timeunit_add_date,
					courts.id as timeunit_court_id,
					courts.name as timeunit_court_name,
					courts.subtitle as timeunit_court_subtitle,
					courts.add_date as timeunit_court_add_date
				FROM reservations
				LEFT JOIN users ON (reservations.username=users.username)
				LEFT JOIN timeunits ON (reservations.timeunit_id=timeunits.id)
				LEFT JOIN courts ON (timeunits.court_id=courts.id)
				WHERE
					timeunits.date>=CURDATE()
				ORDER BY timeunits.date
			';
			$sth = $DB->prepare($sql);
			$sth->execute();
			$ret = array();
			$tmps = $sth->fetchAll(PDO::FETCH_ASSOC);
			foreach($tmps as $tmp){
				$ret[] = new Reservation($tmp);
			}
			
			return $ret;
		}
		
		public static function get_report($from_date,$to_date,$courts=NULL,$username=NULL){
			global $DB;
			
			if(strtotime($from_date)===false)
				throw new ReservationInvalidParam();
			if(strtotime($to_date)===false)
				throw new ReservationInvalidParam();
			if(is_array($courts) && array_filter($courts,'is_numeric')===false)
				throw new ReservationInvalidParam();
			
			$sql = '
				SELECT
					reservations.*,
					users.username as user_username,
					users.full_name as user_full_name,
					users.email as user_email,
					users.phone as user_phone,
					users.birthday as user_birthday,
					users.status as user_status,
					users.activation_key as user_activation_key,
					users.last_login as user_last_login,
					users.reg_date as user_reg_date,
					timeunits.id as timeunit_id,
					timeunits.date as timeunit_date,
					timeunits.from as timeunit_from,
					timeunits.to as timeunit_to,
					timeunits.price as timeunit_price,
					timeunits.comment as timeunit_comment,
					timeunits.available as timeunit_available,
					timeunits.add_date as timeunit_add_date,
					courts.id as timeunit_court_id,
					courts.name as timeunit_court_name,
					courts.subtitle as timeunit_court_subtitle,
					courts.add_date as timeunit_court_add_date
				FROM reservations
				LEFT JOIN users ON (reservations.username=users.username)
				LEFT JOIN timeunits ON (reservations.timeunit_id=timeunits.id)
				LEFT JOIN courts ON (timeunits.court_id=courts.id)
				WHERE
					reservations.status=1 AND
					timeunits.date>=:from_date AND
					timeunits.date<=:to_date AND
					'.(is_array($courts) && count($courts)>0 ? 'courts.id IN ('.implode(',',$courts).') AND' : '').'
					'.(!is_null($username) && $username!="" ? 'reservations.username LIKE :username AND' : '').'
					1=1
				ORDER BY timeunits.date
			';
			
			$sth = $DB->prepare($sql);
			$sth->bindParam(':from_date',$from_date,PDO::PARAM_STR);
			$sth->bindParam(':to_date',$to_date,PDO::PARAM_STR);
			if(!is_null($username) && $username!=""){
				$username_q = '%'.$username.'%';
				$sth->bindParam(':username',$username_q,PDO::PARAM_STR);
			}
			$sth->execute();
			
			$ret = array();
			$tmps = $sth->fetchAll(PDO::FETCH_ASSOC);
			foreach($tmps as $tmp){
				$ret[] = new Reservation($tmp);
			}
			
			return $ret;
		}
	}
?>