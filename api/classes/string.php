<?php
	class STRING{
		function Random($num){
			$y=48;
			while($y<57){
				$y++;
				$chars[]=$y;
			}
			$y=96;
			while($y<122){
				$y++;
				$chars[]=$y;
			}
			$eredm = "";
			for ($i = 0; $i < $num; $i++) {
				$eredm .= chr($chars[mt_rand(0,34)]);
			}
			return $eredm;
		}
		function RandomPass($num){
			$y=47;
			while($y<57){
				$y++;
				$nums[]=$y;
			}
			$y=64;
			while($y<90){
				$y++;
				$bigchars[]=$y;
			}
			$y=96;
			while($y<122){
				$y++;
				$chars[]=$y;
			}
			$eredm = "";
			for ($i = 0; $i < $num; $i++) {
				$type=mt_rand(0,2);
				if($i<=2)$type=$i;
				switch($type){
					case 0:
						$eredm .= chr($chars[mt_rand(0,count($chars)-1)]);
						break;
					case 1:
						$eredm .= chr($bigchars[mt_rand(0,count($bigchars)-1)]);
						break;
					case 2:
						$eredm .= chr($nums[mt_rand(0,count($nums)-1)]);
						break;
				}
			}
			return $eredm;
		}
		function HunToUtf($string) {
			$string=iconv('ISO-8859-2','utf-8',$string);
			return $string;
		}
		function UtfToHun($string) {
			$newstring=iconv('utf-8','ISO-8859-2',$string);
			if(mb_strlen($string,'utf-8')!==strlen($newstring)){
				// valami baj tortent, mivel nem ugyanaz lett a string length
				// ilyenkor megpróbáljuk iso-8859-1-re konvolni
				$newstring=iconv('utf-8','ISO-8859-1',$string);
			}
			return $newstring;
		}
		function UtfToHunExtra($string){
			$newstring=iconv('utf-8','ISO-8859-2//TRANSLIT',$string);
/*			if(mb_strlen($string,'utf-8')!==strlen($newstring)){
				// valami baj tortent, mivel nem ugyanaz lett a string length
				// ilyenkor megpróbáljuk iso-8859-1-re konvolni
				$newstring=iconv('utf-8','ISO-8859-1',$string);
			}*/
			return $newstring;
		
		}
		function Date($dateStr){
			if($dateStr=='0000-00-00 00:00:00' || $dateStr=='')
				return '';
			
			$Y=substr($dateStr,0,4);
			$m=substr($dateStr,5,2);
			$d=substr($dateStr,8,2);
			$H=substr($dateStr,11,2);
			$i=substr($dateStr,14,2);
			$s=substr($dateStr,17,2);
			
			return STRING::DateDate($Y.'-'.$m.'-'.$d).' '.$H.':'.$i;
		}
		
		function DateDate($dateStr){
			$Y=substr($dateStr,0,4);
			$m=substr($dateStr,5,2);
			$d=substr($dateStr,8,2);
			
			return $Y.'. '.STRING::getMonthName($m-1).' '.$d.'.';
		}
		
		function DateDateShort($dateStr){
			$Y=substr($dateStr,2,2);
			$m=substr($dateStr,5,2);
			$d=substr($dateStr,8,2);
			
			return $Y.'-'.$m.'-'.$d.'';
		}
		
		function getMonthName($i,$bool=false){
			$months=array('január','február','március','április','május','június','július','augusztus','szeptember','október','november','december');
			if($bool===true) return $months;
			return $months[$i];
		}
		
		function getExtFromFilename($filename){
			return substr($filename,strrpos($filename,'.'));
		}
		
		function getExtFromPic($filename){
			$type2=getimagesize($filename);
			switch($type2[2]){
				case "1":
					$ext='.gif';
					break;
				case "2":
					$ext='.jpg';
					break;
				case "3":
					$ext='.png';
					break;
			}
			return $ext;
		}
		function wWrap($str){
			$breakChars=array(' ','	');
			$szohossz=0;
			$maxSzoLen=40;
			$strLen=strlen($str);
			$inTag=false;
			for($i=0;$i<=$strLen;$i++){
				$aktChr=$str[$i];
				if($aktChr=='<')$inTag=true;
				if(!$inTag){
					if(!in_array($aktChr,$breakChars))
						$szohossz++;
					else $szohossz=0;
					if($szohossz>=$maxSzoLen){
						$aktChr.='<br />';
						$szohossz=0;
					}
				}
				if($aktChr=='>')$inTag=false;
				$retStr.=$aktChr;
			}
			return $retStr;
		}
		function TrimStr($str)
		{
			$str = trim($str,' ');
			for($i=0;$i < strlen($str);$i++)
			{
		
				if(substr($str, $i, 1) != " ")
				{
		
					$ret_str .= trim(substr($str, $i, 1),' ');
		
				}
				else
				{
					while(substr($str,$i,1) == " ")
				
					{
						$i++;
					}
					$ret_str.= " ";
					$i--; // ***
				}
			}
			return $ret_str;
		}
		function showBegin($str,$from,$fromChar){
			$str=STRING::TrimStr($str);
			$str=html_entity_decode($str,ENT_COMPAT,'utf-8');
			
			for($i=0;$i<mb_strlen($str);$i++){
				$aktChr = mb_substr($str,$i,1,'utf-8');
				if($aktChr==$fromChar){
					$lastPos=$i;
				}
				if($i>=$from){
					break;
				}
			}
			$i=$lastPos;
			if($i==0)$i=$from;
			
			return mb_substr($str,0,$i+1,'utf-8');
		}
		function EkezetTelenit($str){
			$ekezetek=array(
				'á',
				'é',
				'ű',
				'ő',
				'ú',
				'ü',
				'ó',
				'ö',
				'í',
				'Á',
				'É',
				'Ű',
				'Ő',
				'Ú',
				'Ö',
				'Ü',
				'Ó',
				'Í',
				'ä',
				'ō',
			);
			$ekezetek2=array(
				'a',
				'e',
				'u',
				'o',
				'u',
				'u',
				'o',
				'o',
				'i',
				'A',
				'E',
				'U',
				'O',
				'U',
				'O',
				'U',
				'O',
				'I',
				'a',
				'o'
			);
			return str_replace($ekezetek,$ekezetek2,$str);
		}
		
		function slug($str){
			$str = STRING::EkezetTelenit($str);
			$str = strtolower(trim($str));
			$str = preg_replace('/[^a-z0-9-]/', '-', $str);
			$str = preg_replace('/-+/', "-", $str);
			return $str;
		}
		
		function isAlphaNum($str){
			return ereg('^[a-zA-Z0-9_űáéúőóüöíŰÁÉÚŐÓÜÖÍ]*$',$str);
		}
		function isAlphaNumSpace($str){
			return ereg('^[a-zA-Z0-9_űáéúőóüöíŰÁÉÚŐÓÜÖÍ ]*$',$str);
		}
		
		function moneyString($s){
			$len=strlen($s);
			$len2=$len+floor($len/3);
			if(($len%3)==0)$len2-=3;
			for($i=3;$i<=$len2;$i+=4){
				$len=strlen($s);
				$s=substr($s,0,$len-$i).' '.substr($s,$len-$i,$i);
			}
			return $s;
		}
		
		function floatMoneyString($s){
			$parts = explode(',',$s);
			if(count($parts)==2){
				$money = STRING::moneyString($parts[0]);
				return $money.','.$parts[1];
			}else{
				return STRING::moneyString($parts[0]);
			}
		}
		
		function validEmail($email){
			$pattern='/^[a-zA-Z0-9]?[\w\.\-\+]*[a-zA-Z0-9]@[a-zA-Z0-9][\w\.-]*[a-zA-Z0-9]\.[a-zA-Z][a-zA-Z\.]*[a-zA-Z]$/';
			if(preg_match_all($pattern,$email,$matchreg)!==1)
				return false;
			return true;
		}
		
		function password_strength($pwd){
			if( strlen($pwd) < 8 ) {
				return "short";
			}
			
			$i = 0;
			if( preg_match("#[0-9]+#", $pwd) )
				$i++;
			if( preg_match("#[a-z]+#", $pwd) )
				$i++;
			if( preg_match("#[A-Z]+#", $pwd) )
				$i++;
			if( preg_match("#\W+#", $pwd) )
				$i++;
			
			if($i<3)
				return "symbol";
			
			return true;
		}

	}
?>