<?php
	class UserNotValid extends Exception{};
	
	class User extends Properties{
		const PAGER = 50;
		
		public function __construct($props){
			global $DB;
			
			$this->props=$props;
		}
		
		public function get_props(){
			return $this->props;
		}
		
		public function is_valid($reg=false){
			global $DB;
			
			$errors = array();
			
			if($this->born!="")$this->birthday = $this->born;
			$this->birthday = str_replace('.','-',$this->birthday);
			$this->birthday = trim($this->birthday,'-');
			
			if($this->full_name=="")
				$errors['full_name'][] = "A teljes név megadása kötelező!";
			elseif(strlen($this->full_name)>100)
				$errors['full_name'][] = "A teljes név maximum 100 karakter hosszú lehet!";
			if($this->username=="")
				$errors['username'][] = "A felhasználónév megadása kötelező!";
			elseif(strlen($this->username)>50)
				$errors['username'][] = "A felhasználónév maximum 50 karakter hosszú lehet!";
			elseif($reg===true){
				$sth = $DB->prepare('SELECT COUNT(*) FROM users WHERE username=:username');
				$sth->bindParam(':username',$this->username,PDO::PARAM_STR);
				$sth->execute();
				if($sth->fetchColumn()>0)
					$errors['username'][] = "Ez a felhasználónév már foglalt!";
			}
			if($reg===true && $this->password=="")
				$errors['password'][] = "A jelszó megadása kötelező!";
			elseif(strlen($this->password)>50)
				$errors['password'][] = "A jelszó maximum 50 karakter hosszú lehet!";
			elseif($this->password!="" && $this->password!=$this->password2)
				$errors['password'][] = "Nem egyezik meg a két jelszó!";
			if($this->email=="")
				$errors['email'][] = "Az email cím megadása kötelező!";
			elseif(@!STRING::validEmail($this->email))
				$errors['email'][] = "Hibás email cím!";
			elseif(strlen($this->email)>200)
				$errors['email'][] = "Az email cím maximum 200 karakter hosszú lehet!";
			elseif($reg===true){
				$sth = $DB->prepare('SELECT COUNT(*) FROM users WHERE email=:email');
				$sth->bindParam(':email',$this->email,PDO::PARAM_STR);
				$sth->execute();
				if($sth->fetchColumn()>0)
					$errors['email'][] = "Ezzel az email címmel már van regisztált felhasználónk!";
			}
			if($this->phone=="")
				$errors['phone'][] = "A telefonszám megadása kötelező!";
			elseif(!is_numeric($this->phone))
				$errors['phone'][] = "A telefonszám csak számokat tartalmazhat!";
			elseif(strlen($this->phone)>20)
				$errors['phone'][] = "A telefonszám maximum 20 karakter hosszú lehet!";
			if($this->birthday!="" && strtotime($this->birthday)===false)
				$errors['born'][] = "A születésnapot ÉÉÉÉ-HH-NN formátumban kell megadni!";
			if($this->discount!="" && !is_numeric($this->discount))
				$errors['discount'][] = "A kedvezmény mértéke csak szám lehet!";
			if($reg===true && $this->tos!="true")
				$errors['tos'][] = "Az ÁSZF-et kötelező elfogadnod!";
			
			if(count($errors)>0)
				return $errors;
			
			$this->valid = true;
			
			return true;
		}
		
		public function save(){
			if($this->valid!==true)
				throw new UserNotValid("Please call is_valid() before saving!");
			
			global $DB;
			
			if($this->reg_date==""){
				$this->activation_key = @STRING::Random(8);
			}
			
			$sql = '
				INSERT INTO users
				(`username`,`password`,`full_name`,`email`,`phone`,`birthday`,`discount`,`status`,`activation_key`,`reg_date`)
				VALUES(:username,MD5(:password),:full_name,:email,:phone,:birthday,0,0,:activation_key,NOW())
				ON DUPLICATE KEY UPDATE full_name=:full_name, phone=:phone, birthday=:birthday, discount=:discount
			';
			$sth = $DB->prepare($sql);
			$sth->bindParam(':username',$this->username,PDO::PARAM_STR);
			$sth->bindParam(':password',$this->password,PDO::PARAM_STR);
			$sth->bindParam(':full_name',$this->full_name,PDO::PARAM_STR);
			$sth->bindParam(':email',$this->email,PDO::PARAM_STR);
			$sth->bindParam(':phone',$this->phone,PDO::PARAM_STR);
			$sth->bindParam(':birthday',$this->birthday,PDO::PARAM_STR);
			$sth->bindParam(':discount',$this->discount,PDO::PARAM_STR);
			$sth->bindParam(':activation_key',$this->activation_key,PDO::PARAM_STR);
			$sth->execute();
		}
		public function migrate(){
			if($this->valid!==true)
				throw new UserNotValid("Please call is_valid() before saving!");
			
			global $DB;
			
			$sql = '
				INSERT INTO users
				(`username`,`password`,`full_name`,`email`,`phone`,`status`,`activation_key`,`reg_date`)
				VALUES(:username,:password,:full_name,:email,:phone,1,"",NOW())
			';
			$sth = $DB->prepare($sql);
			$sth->bindParam(':username',$this->username,PDO::PARAM_STR);
			$sth->bindParam(':password',$this->password,PDO::PARAM_STR);
			$sth->bindParam(':full_name',$this->full_name,PDO::PARAM_STR);
			$sth->bindParam(':email',$this->email,PDO::PARAM_STR);
			$sth->bindParam(':phone',$this->phone,PDO::PARAM_STR);
			$sth->execute();
		}
		public function change_password(){
			if($this->valid!==true)
				throw new UserNotValid("Please call is_valid() before saving!");
			
			global $DB;
			
			$sth = $DB->prepare('UPDATE users SET password=MD5(:password) WHERE username=:username');
			$sth->bindParam(':username',$this->username,PDO::PARAM_STR);
			$sth->bindParam(':password',$this->password,PDO::PARAM_STR);
			$sth->execute();
		}
		
		public function activate(){
			global $DB;
			
			$this->status = 1;
			
			$sth = $DB->prepare('UPDATE users SET status=1 WHERE username=:username');
			$sth->bindParam(':username',$this->username,PDO::PARAM_STR);
			$sth->execute();
		}
		
		public function is_activated(){
			return ($this->status>0);
		}
		
		public function generate_password(){
			global $DB;
			$pass = @STRING::RandomPass(8);
			
			$sth = $DB->prepare('UPDATE users SET password=MD5(:password) WHERE username=:username');
			$sth->bindParam(':password',$pass,PDO::PARAM_STR);
			$sth->bindParam(':username',$this->username,PDO::PARAM_STR);
			$sth->execute();
			
			return $pass;
		}
		
		public function set_data_for_change($data){
			foreach($data as $key => $value){
				if($key!="username")
					$this->props[$key] = $value;
			}
		}
		
		public function set_data_for_changepassword($data){
			$this->password = $data['password'];
			$this->password2 = $data['password2'];
		}
		
		public function get_transactions(){
			if(is_null($this->transactions)){
				global $DB;
				$sth = $DB->prepare('SELECT * FROM users_transactions WHERE username=:username ORDER BY add_date DESC');
				$sth->bindParam(':username',$this->username,PDO::PARAM_STR);
				$sth->execute();
				
				$tmps = $sth->fetchAll();
				$ret = array();
				foreach($tmps as $tmp){
					$ret[] = new UserTransaction($tmp);
				}
				
				$this->transactions = $ret;
			}
			
			return $this->transactions;
		}
		
		public function get_reservations(){
			global $DB;
			$sql = '
				SELECT
					reservations.*,
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
				LEFT JOIN timeunits ON (reservations.timeunit_id=timeunits.id)
				LEFT JOIN courts ON (timeunits.court_id=courts.id)
				WHERE
					reservations.username=:username
				ORDER BY timeunits.date DESC, timeunits.from ASC
			';
			$sth = $DB->prepare($sql);
			$sth->bindParam(':username',$this->username,PDO::PARAM_STR);
			$sth->execute();
			
			$tmps = $sth->fetchAll(PDO::FETCH_ASSOC);
			$ret = array();
			foreach($tmps as $tmp){
				try{
					$res = new Reservation($tmp);
					
					$ret[] = $res->get_array();
				}catch(Exception $ex){
					// nem lehet letrehozni a Reservationt... pl. TimeUnit nincs hozza, torolve lett. Ritkan, de elofordulhat ilyen.
					// Ebben az esetben a Reservation fel lett oldva (user visszakapta a penzt)
				}
			}
			
			return $ret;
		}
		
		public function get_balance(){
			global $DB;
			
			$sth = $DB->prepare('SELECT SUM(value) FROM users_transactions WHERE username=:username');
			$sth->bindParam(':username',$this->username,PDO::PARAM_STR);
			$sth->execute();
			
			$ret = $sth->fetchColumn();
			
			return (int)$ret;
		}
		
		public function get_array(){
			$ret = $this->props;
			$ret['balance'] = (int)$this->get_balance();
			
			return $ret;
		}
		
		public function set_phone_migrate($phone){
			$phone = str_replace('-','',$phone);
			$phone = str_replace('_','',$phone);
			$phone = str_replace('/','',$phone);
			$phone = str_replace('+','',$phone);
			$phone = str_replace(' ','',$phone);
			$phone = str_replace('vagy','',$phone);
			$phone = str_replace('(','',$phone);
			$phone = str_replace(')','',$phone);
			$phone = str_replace('nincs','',$phone);
			
			$this->phone = $phone;
		}
		
		public static function get_by_activation_key($key){
			global $DB;
			
			$sth = $DB->prepare('SELECT * FROM users WHERE status=0 AND activation_key=:key');
			$sth->bindParam(':key',$key,PDO::PARAM_STR);
			$sth->execute();
			
			$props = $sth->fetch();
			$props['password']='';
			if($props!==false){
				return new User($props);
			}
			
			return false;
		}
		public static function get_by_username($username){
			global $DB;
			
			$sth = $DB->prepare('SELECT * FROM users WHERE username=:username');
			$sth->bindParam(':username',$username,PDO::PARAM_STR);
			$sth->execute();
			
			$props = $sth->fetch();
			$props['password']='';
			if($props!==false){
				return new User($props);
			}
			
			return false;
		}
		
		public static function get_all($page,$q=NULL){
			global $DB;
			
			$from = ($page-1)*User::PAGER;
			$to = User::PAGER;
			
			if(!is_null($q)){
				$qsql = ' AND (username LIKE :q OR email LIKE :q OR full_name LIKE :q)';
				$q2 = '%'.$q.'%';
			}
			
			$sth = $DB->prepare('SELECT * FROM users WHERE 1=1'.$qsql.' ORDER BY username LIMIT :from,:to');
			$sth->bindParam(':from',$from,PDO::PARAM_INT);
			$sth->bindParam(':to',$to,PDO::PARAM_INT);
			if(!is_null($q)){
				$sth->bindParam(':q',$q2,PDO::PARAM_STR);
			}
			$sth->execute();
			
			$tmps = $sth->fetchAll();
			
			$ret = array();
			foreach($tmps as $tmp){
				$ret[] = new User($tmp);
			}
			
			return $ret;
		}
		
		public static function get_all_count($q=NULL){
			global $DB;
			
			if(!is_null($q)){
				$qsql = ' AND (username LIKE :q OR email LIKE :q OR full_name LIKE :q)';
				$q2 = '%'.$q.'%';
			}
			
			$sth = $DB->prepare('SELECT COUNT(*) FROM users WHERE 1=1'.$qsql.'');
			if(!is_null($q)){
				$sth->bindParam(':q',$q2,PDO::PARAM_STR);
			}
			$sth->execute();
			
			$ret = $sth->fetchColumn();
			
			return $ret;
		}
	}
?>