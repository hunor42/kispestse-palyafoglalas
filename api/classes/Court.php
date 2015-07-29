<?php
	class CourtNotValid extends Exception{};
	class CourtNotFound extends Exception{};
	class CourtInvalidParam extends Exception{};
	
	class Court extends Properties{
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
			
			if($this->name=="")
				$errors[] = "A pálya név megadása kötelező!";
			if(!is_numeric($this->price))
				$errors[] = "A pálya alapértelmezett árának megadása kötelező és csak szám lehet!";
			
			if(count($errors)>0)
				return $errors;
			
			$this->valid = true;
			
			return true;
		}
		
		public function save(){
			if($this->valid!==true)
				throw new CourtNotValid("Please call is_valid() before saving!");
			
			global $DB;
			
			$sql = '
				INSERT INTO courts
				(`id`,`name`,`subtitle`,`price`,`add_date`)
				VALUES(:id,:name,:subtitle,:price,NOW())
				ON DUPLICATE KEY UPDATE name=:name, subtitle=:subtitle, price=:price
			';
			$sth = $DB->prepare($sql);
			$sth->bindParam(':id',$this->id,PDO::PARAM_STR);
			$sth->bindParam(':name',$this->name,PDO::PARAM_STR);
			$sth->bindParam(':subtitle',$this->subtitle,PDO::PARAM_STR);
			$sth->bindParam(':price',$this->price,PDO::PARAM_INT);
			$sth->execute();
		}
		
		public function get_array(){
			$ret = array();
			$ret['id'] = $this->id; 
			$ret['title'] = $this->name;
			$ret['subtitle'] = $this->subtitle;
			
			return $ret;
		}
		
		public function set_data_for_change($data){
			foreach($data as $key => $value){
				if($key!="id")
					$this->props[$key] = $value;
			}
		}
		
		public static function get_by_id($id){
			global $DB;
			
			$sth = $DB->prepare('SELECT * FROM courts WHERE id=:id');
			$sth->bindParam(':id',$id,PDO::PARAM_STR);
			$sth->execute();
			
			$props = $sth->fetch(PDO::FETCH_ASSOC);
			if($props!==false){
				return new Court($props);
			}else
				throw new CourtNotFound();
		}
		
		public static function get_all(){
			global $DB;
			
			$sth = $DB->prepare('SELECT * FROM courts ORDER BY id');
			$sth->execute();
			
			$ret = array();
			foreach($sth->fetchAll() as $tmp){
				$ret[] = new Court($tmp);
			}
			
			return $ret;
		}
		
		public static function get_names($ids){
			global $DB;
			
			if(array_filter($ids,'is_numeric')===false)
				throw new CourtInvalidParam();
			
			$sth = $DB->prepare('SELECT * FROM courts WHERE id IN ('.implode(',',$ids).') ORDER BY id');
			$sth->execute();
			
			$ret = array();
			foreach($sth->fetchAll() as $tmp){
				$ret[] = $tmp['name'];
			}
			
			return $ret;
		}
	}
?>