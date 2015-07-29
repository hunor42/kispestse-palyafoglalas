<?php
	require_once('classes/_loader.php');
	require_once('classes/init.php');
	
	$ret = array();
	$ret['redirectToLogin'] = false;
	
	$items = $basket->get_items();
	if(count($items)==0){
		$ret['success'] = false;
		$ret['errors'][] = 'A kosár üres!';
	}else{
		$login = new Login('users');
		$doLogin=$login->doLogin();
		if($doLogin===1){
			$logined_user = $login->obj;
			
			if($basket->get_basket_value()>$logined_user->get_balance()){
				$ret['success'] = false;
				$ret['errors'][] = 'A felhasználónak nincs elég egyenlege a foglalások véglegesítéséhez!';
			}else{
				foreach($items as $item){
					$res = new Reservation();
					$res->user = $logined_user;
					$res->timeunit_id = $item->item->id;
					$res->timeunit = $item->item;
					$res->price = $item->price;
					if($res->is_valid()===true){
						$res->reserve();
						
						$res->timeunit->load_court();
					}
				}
				
				$basket->clear();
				$ret['success'] = true;
			}
		}else{
			$ret['success'] = false;
			$ret['redirectToLogin'] = true;
			$ret['errors'][] = "Nincs bejelentkezve felhasználó!";
		}
	}
	
	echo json_encode($ret);
	
	require_once('classes/close.php');
?>