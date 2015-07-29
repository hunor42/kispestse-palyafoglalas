<?php
	abstract class Properties{
		protected $props = array();
		
		public function __set($name, $value)
		{
			$this->props[$name] = $value;
		}
		
		public function __get($name)
		{
			if (array_key_exists($name, $this->props)) {
				return $this->props[$name];
			}
			
			return null;
		}
		
		public function __isset($name)
		{
			return isset($this->props[$name]);
		}
		
		public function __unset($name)
		{
			unset($this->props[$name]);
		}
		
		public function get_connected_object_props($object_name){
			$object_name .= '_';
			$ret = array();
			foreach($this->props as $key => $value){
				if(substr($key,0,strlen($object_name))==$object_name)
					$ret[str_replace($object_name,'',$key)] = $value;
			}
			return $ret;
		}
		
		public function get_props(){
			return $this->props;
		}
	}
?>