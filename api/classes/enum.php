<?php
	class enum{
		public $fields;
		
		public function __construct(){
		}
		
		public function isValidValue($value){
			if(is_array($this->fields))
				return in_array($value,array_keys($this->fields));
			else
				return false;
		}
	}
?>