<?php
	include('../system.util.php');
	include_once("../system.email.php");	
	class theEmail{
		function addSendMail(){		
			//设置验证地址
			
			//调用email的设置							
			$theUserEmail = $_POST['the-email'];
			$theUser = $_POST['the-user'];
			$theTitle = "邮箱验证";
			$theBody = '<html><body><h3>亲爱的用户：'.$theUser.'</h3><p>您好，您需要点击一下链接，进行注册邮箱验'.$theUserEmail.'证才能对网站进行相关的操作。验证请点击以下地址<a href = ""></p></body></html>';
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