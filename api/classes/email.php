<?php
	class Email{
		private $email;
		private $subject;
		private $body;
		private $attachment;
		private $contentType = 'text/plain';
		
		function __construct($email,$subject,$body,$attachment=null){
			$this->email = $email;
			$this->subject = $subject;
			$this->body = $body;
			$this->attachment = $attachment;
		}
		
		function sendHtml($from="",$fromname="",$sender=false){
			$this->contentType='text/html';
			$this->send($from,$fromname,$sender);
			$this->contentType='text/plain';
		}
		
		function send($from="",$fromname="",$sender=false){
//		function send($from="",$fromname=""){
			if($this->email!=""){
				$contentType=$this->contentType;
				$email = $this->email;
				$subject = $this->subject;
				$body = $this->body;
				
				$mail = new PHPMailer();
				if($from!="")
					$mail->From     = $from;
				else
					$mail->From     = CFG::FROM;
				if($fromname!="")
					$mail->FromName = $fromname;
				else
					$mail->FromName = CFG::FROMNAME;
				$mail->ContentType = $contentType;
				$mail->CharSet = 'UTF-8';
				if($sender===false)
					$sender = $mail->From;
				$mail->Sender = $sender;

				$mail->Subject = $subject;

				$mail->Body    = $body;
				if($altBody<>'')
					$mail->AltBody    = $altbody;
				$mail->clearAddresses();
				if(is_array($email)){
					foreach($email as $em){
						$mail->addAddress($em);
					}
				}else{
					if(strpos($email,',')!==false){
						$adds = explode(',',$email);
						foreach($adds as $em){
							$mail->addAddress($em);
						}
					}else{
						$mail->addAddress($email);
					}
				}
				
				if(!is_null($this->attachment)){
					$tmp = $mail->AddAttachment($this->attachment);
				}
				
				$mail->Send();
			}
		}
	}
?>