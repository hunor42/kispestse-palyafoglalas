<?php
	abstract class Collection {
		protected $items=array();
		protected $user=null;

		public function get_items(){
			return $this->items;
		}

		public function has_item($item_list){
			$has_item = false;
			foreach($this->items as $item){
				if(in_array($item->item->id,$item_list)){
					$has_item=$item;
					break;
				}
			}
			return $has_item;
		}

		public function count_item($item_list){
			$count_item = 0;
			foreach($this->items as $item){
				if(in_array($item->item->id,$item_list)){
					$count_item+=$item->piece;
				}
			}
			return $count_item;
		}

		public function count(){
			return count($this->items);
		}

		public function add_item($item){
			$this->items[]=$item;
		}
		public function clear_item($index){
			unset ($this->items[$index]);
		}
		public function get_item($index){
			return $this->items[$index];
		}

		public function clear_items(){
			$this->items=array();
		}
	}

	class BasketItem{
		public $item;
		public $price;
		public $user;
		public $orig_price;

		public function __construct($item,$user=null){
			if(!is_null($user)){
				$this->user = $user;
			}
			
			$this->item=$item;
			
			$this->update_price();
		}
		
		public function update_price(){
			if(!is_null($this->user))
				$discount = $this->user->discount;
			else
				$discount = 0;
			
			$this->price=round($this->item->price*((100-$discount)/100));
			$this->orig_price=$this->item->price;
		}
		
		public function set_user($user){
			if(!is_null($user)){
				$this->user = $user;
			}
			
			$this->update_price();
		}
	}

	class Basket extends Collection{
		public function __construct(){
		}

		public function clear(){
			$this->clear_items();
		}

		public function add($timeunit){
			$timeunit->prereserve();
			
			$basket_item = new BasketItem($timeunit,$this->user);
			$this->add_item($basket_item);
		}

		public function remove($timeunit){
			foreach($this->items as $key => $item){
				if($item->item->id==$timeunit->id){
					$this->clear_item($key);
				}
			}
		}
		
		public function get_basket_value(){
			$ret = 0;
			foreach($this->items as $item){
				$ret+=$item->price;
			}
			return $ret;
		}

		public function get_basket(){
			$ret = array();
			$ret['basketValue'] = $this->get_basket_value();
			$ret['basketItems'] = $this->count();
			$ret['basketItemsDetails'] = array();
			foreach($this->items as $item){
				$tmp = $item->item->get_array();
				$tmp['price'] = $item->price;
				$tmp['orig_price'] = $item->orig_price;
				$tmp['date'] = $item->item->date;
				$tmp['court'] = array("title"=>$item->item->court->name, "subtitle"=>$item->item->court->subtitle);

				$ret['basketItemsDetails'][] = $tmp;
			}

			return $ret;
		}
		
		public function set_user($user){
			$this->user = $user;
			
			foreach($this->items as $item){
				$item->set_user($this->user);
			}
		}
		
	}
?>