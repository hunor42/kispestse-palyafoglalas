<?php
	class Portal{
		function Portal(){
			// biztonsag
			function safepostdata(&$item,$key){
				$keyid = substr($key,0,14);
				if(get_magic_quotes_gpc())
					$item=stripslashes($item);
				
				if($keyid=='wysiwyg_editor' || $key=="wysiwyg_editor" || $key=='body' || $key=='desc' || $key=='desc2' || $key=='cTable' || $key=='desc3' || $key=='desc4' || $key=='fb_desc' || $key=='fb_desc2' || $key=='advert_text' || $key=='benefits' || $key=='name' || $key=='userhistory_text'){
					//
				}else{
					$item=htmlspecialchars(trim($item));
				}
			}
			array_walk_recursive($_POST, 'safepostdata');
			array_walk_recursive($_GET, 'safepostdata');
		}
		
		function debug($str){
			if(CFG::DEBUG)
				echo '[DEBUG] '.$str.'<br />';
		}
	}
?>