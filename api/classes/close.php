<?php
	if(!is_object($basket))
		$basket = new Basket();
	
	$_SESSION['basket'] = serialize($basket);
?>