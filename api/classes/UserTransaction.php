<?php
	class UserTransactionNotValid extends Exception{};
	
	class UserTransaction extends Properties{
		public static $TYPES = array(0=>'Befizetés jóváírás',1=>'Pálya foglalás',2=>'Pálya lemondás');
		
		public function __construct($props=array()){
			global $DB;
			
			$this->props=$props;
		}
		
		public function get_props(){
			return $this->props;
		}
		
		public function is_valid(){
			global $DB;
			
			$errors = array();
			
			if(!is_object($this->user))
				$errors[] = "A tranzakció kedvezményezettjének megadása kötelező!";
			if(!is_numeric($this->value))
				$errors[] = "Az érték megadása kötelező és csak szám lehet!";
			if(!in_array($this->type,array_keys(self::$TYPES)))
				$errors[] = "A tranzakció típusa hibásan lett megadva!";
			
			if(count($errors)>0)
				return $errors;
			
			$this->valid = true;
			
			return true;
		}
		
		public function save(){
			if($this->valid!==true)
				throw new UserTransactionNotValid("Please call is_valid() before saving!");
			
			global $DB;
			
			$sql = '
				INSERT INTO users_transactions
				(`username`,`value`,`type`,`object_id`,`admin_username`,`add_date`)
				VALUES(:username,:value,:type,:object_id,:admin_username,NOW())
			';
			$sth = $DB->prepare($sql);
			$sth->bindParam(':username',$this->user->username,PDO::PARAM_STR);
			$sth->bindParam(':value',$this->value,PDO::PARAM_INT);
			$sth->bindParam(':type',$this->type,PDO::PARAM_INT);
			$sth->bindParam(':object_id',$this->object_id,PDO::PARAM_INT);
			$sth->bindParam(':admin_username',$this->admin_username,PDO::PARAM_STR);
			$sth->execute();
		}
		
		public function get_type_display(){
			return self::$TYPES[$this->type];
		}
	}
?>