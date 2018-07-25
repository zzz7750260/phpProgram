<?php
	class theEmail{
		function addSendMail(){		
			//调用email的设置
			include_once("../system.email.php");						
			$theUserEmail = $_POST['the-email'];
			$theUser = $_POST['the-user'];
			$theTitle = "邮箱验证";
			$theBody = '<html><body><h3>亲爱的用户：'.$theUser.'</h3><p>您好，您需要点击一下链接，进行邮箱验'.$theUserEmail.'证才能对网站进行相关的操作。</p></body></html>';
			$isReturn = sendMail($theUserEmail,$theTitle,$theBody);
			if($isReturn){
				$emailReturnArray = array(
					status => 200,
					msg => '邮箱发送成功',
					result => $isReturn,
				);				
			}
			else{
				$emailReturnArray = array(
					status => 400,
					msg => '邮箱发送失败',
					result => $isReturn,
				);					
			}
			
			//转换为json数据返回给前端
			$emailReturnJson = json_encode($emailReturnArray);
			print_r($emailReturnJson);
		}
		
		function theReturn($turl){
			if($turl == 'addSendMail'){
				$this->addSendMail();
			}			
		}
				
	}
?>