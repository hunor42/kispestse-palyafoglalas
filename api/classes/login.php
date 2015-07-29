<?php
	class Login{
		public $logined = false;
		public $username = '';
		public $email = '';
		public $password = '';
		public $identd = '';
		private $table = '';
		public $shops = array();
		public $privileges = array();
		public $props = array();
		
		function __construct($identd){
			// konstruktorban ellenőrizzük, hogy van-e már elő session (belépett-e már korábban)
			$this->identd=$identd;
			if($_SESSION['login_'.$this->identd.'_logined']=='true'){
				// ha igen, akkor beállítjuk a privát változókat.
				$this->logined=true;
				
				$this->username=$_SESSION['login_'.$this->identd.'_username'];
				$this->email=$_SESSION['login_'.$this->identd.'_email'];
				$this->password=$_SESSION['login_'.$this->identd.'_password'];
				$this->props=unserialize($_SESSION['login_'.$this->identd.'_props']);
				$this->obj=unserialize($_SESSION['login_'.$this->identd.'_obj']);
			}else{
				// egyébként false-re állítjuk a logint.
				$this->logined = false;
			}
			
			if($this->identd!='users')
				$this->table = $this->identd.'_users';
			else
				$this->table = 'users';
		}

		function isLogined(){
			// visszaadja a belépett felhasználónevet, ha már belépett, egyébként (bool)false-t ad vissza.
			return ($this->logined!==false ? $this->username : false);
		}

		function logout(){
			// Kilépteti a felhasználót
			unset($_SESSION['login_'.$this->identd.'_logined']);
			unset($_SESSION['login_'.$this->identd.'_username']);
			unset($_SESSION['login_'.$this->identd.'_email']);
			unset($_SESSION['login_'.$this->identd.'_password']);
			unset($_SESSION['login_'.$this->identd.'_props']);
			unset($_SESSION['login_'.$this->identd.'_obj']);
			$this->logined=false;
			$this->username='';
			$this->email='';
			$this->password='';
			$this->props=array();
			$this->obj=NULL;
		}
		
		function loggingin($username,$password){
			global $DB;

			// SELECT SUBSTR(password,1,LOCATE(':',password)-1), SUBSTR(password,LOCATE(':',password)+1,2) FROM `users` WHERE 1
			$sql = 'SELECT COUNT(*) FROM '.$this->table.' WHERE username=:username AND password=MD5(:password) AND status=1';
			$sth = $DB->prepare($sql);
			$sth->bindParam(':username', $username, PDO::PARAM_STR);
			$sth->bindParam(':password', $password, PDO::PARAM_STR);
			$sth->execute();
			
			if($sth->fetchColumn()>0){
				// helyes volt a user/pass és beléptetjük
				
				$sth = $DB->prepare('UPDATE '.$this->table.' SET last_login=NOW() WHERE username=:username AND password=MD5(:password)');
				$sth->bindParam(':username', $username, PDO::PARAM_STR);
				$sth->bindParam(':password', $password, PDO::PARAM_STR);
				$sth->execute();
				
				$sth = $DB->prepare('SELECT * FROM '.$this->table.' WHERE username=:username AND password=MD5(:password)');
				$sth->bindParam(':username', $username, PDO::PARAM_STR);
				$sth->bindParam(':password', $password, PDO::PARAM_STR);
				$sth->execute();
				$userProps=$sth->fetch(PDO::FETCH_ASSOC);
				$userProps['password']="";
				
				if($this->table=='users'){
					$userObj = new User($userProps);
				}
				
				// privileges lekerdeze, de adminrol van szo (van role)
				
				$this->logined=true;
				$_SESSION['login_'.$this->identd.'_logined']='true';
				$_SESSION['login_'.$this->identd.'_username']=$username;
				$_SESSION['login_'.$this->identd.'_email']=$email;
				$_SESSION['login_'.$this->identd.'_password']=$password;
				$_SESSION['login_'.$this->identd.'_props']=serialize($userProps);
				$_SESSION['login_'.$this->identd.'_obj']=serialize($userObj);
				
				$this->username = $_SESSION['login_'.$this->identd.'_username'];
				$this->email = $_SESSION['login_'.$this->identd.'_email'];
				$this->password = $_SESSION['login_'.$this->identd.'_password'];
				$this->props = unserialize($_SESSION['login_'.$this->identd.'_props']);
				$this->obj = unserialize($_SESSION['login_'.$this->identd.'_obj']);
				return 1;
			}else{
				$sth = $DB->prepare('SELECT status FROM '.$this->table.' WHERE username=:username');
				$sth->bindParam(':username', $username, PDO::PARAM_STR);
				$sth->execute();
				$row=$sth->fetch();
				if($row!==false){
					if($row['status']!="1")
						return 3; // nem aktivált felhasználó
					else
						return 2; // bad u/p
				}else{
					return 2; // bad u/p
				}
			}
		}
		
		function doLogin(){
			// SESSION be van regisztrálva, a felhasználó korábban sikeresen belépett
			if($this->logined===true){
				return 1;
			// Bejelentkező űrlapot küldtek
			}elseif($_POST['username']!=''){
				// Leellenőrizzük a kapott adatokat
				return $this->loggingin($_POST['username'],$_POST['password']);
			}else{
				// Nem lépett még be, és nincs küldött űrlap
				return 0;
			}
		}
		
		function save_user_obj($userObj){
			$_SESSION['login_'.$this->identd.'_props']=serialize($userObj->props);
			$_SESSION['login_'.$this->identd.'_obj']=serialize($userObj);
		}
		
		function privilegePoint($type){
			return 1;
		}
	}
?>