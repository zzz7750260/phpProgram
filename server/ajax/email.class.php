<?php
	include('../system.util.php');
	include('../system.mysql.int.php');
	include_once("../system.email.php");	
	class theEmail{
		function addSendMail(){		
				
			//调用email的设置							
			$theUserEmail = $_POST['the-email'];
			$theUser = $_POST['the-user'];
			$theTitle = "邮箱验证";
			//设置验证地址
			$theUtil = new util();					
			$theUrl = $theUtil->webPath('/server/ajax/theemail.php?turl=checkEmail&theuser-name='.$theUser);
			$theBody = '<html><body><h3>亲爱的用户：'.$theUser.'</h3><p>您好，您需要点击一下链接，进行注册邮箱验'.$theUserEmail.'证才能对网站进行相关的操作。验证请点击以下地址<a href = "'.$theUrl.'">'.$theUrl.'</a></p></body></html>';
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
		
		//验证邮箱
		function checkEmail(){
			//获取用户名
			$getUserName = $_GET['theuser-name'];
			$theUtil = new util();		
			//获取链接是否已经验证
			$checkHaveUserSql = "select * from member where username = '$getUserName'";
			$checkHaveUserSql_db = mysql_query($checkHaveUserSql);
			$checkHaveUserSql_db_array = mysql_fetch_assoc($checkHaveUserSql_db);
			if($checkHaveUserSql_db_array['role'] != 'visitor'){
				echo "该链接已经验证过了";
				$theUrl = $theUtil->webPath();	
				echo $theUrl;
			}
			else{
				//验证用户名是否存在,更改用户角色
				$checkEmailUserSql = "update member set role = 'edit' where username = '$getUserName'";
				$checkEmailUserSql_db = mysql_query($checkEmailUserSql);
				if($checkEmailUserSql_db){
					echo "验证成功";
					//设置跳转页面的路径
					$theUrl = $theUtil->webPath('/admin/');
					//header('Refresh:3;url="'.$theUrl.'"'); 		
					//$theUrl = '../../admin/';
				}
				else{
					echo "验证失败";
					$theUrl = $theUtil->webPath();	
					//$theUrl = '../../';	
				}
				//echo $theUrl;						
			}
						
			echo '<script>setTimeout(function(){window.location.href="'.$theUrl.'"},3000)</script>';
		}
		
		function theReturn($turl){
			if($turl == 'addSendMail'){
				$this->addSendMail();
			}		
			if($turl == 'checkEmail'){
				$this->checkEmail();
			}
		}
				
	}
?>