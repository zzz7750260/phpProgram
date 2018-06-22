<?php
session_start();
include('../system.util.php');
class theMember{
	//注册提交或者添加用户
	function registerAdd(){
		$theUsername = $_POST['username'];
		$thePassword = $_POST['password'];
		$theSex = $_POST['sex'];		
		$theEmail = $_POST['email'];
		$theTel = $_POST['tel'];
		$theHead = $_POST['head'];
		$theCity = $_POST['city'];
		$theIntroduction = $_POST['introduction'];
		$theSelect = $_POST['select'];//选择新增或者编辑的方式
		$theOldUserName = $_POST['oldUserName'];
		
		$theBaseImg = $_POST['baseImg'];
		
		$theUtil = new util();
		
		
		//前面为time(),后4位为随机生成数的id
		$registerTime = date(YmdHis);
		
		$randNum = array(0,1,2,3,4,5,6,7,8,9);
		$randNumLength = count($randNum);
		
		for($i=0; $i<4; $i++){
			$str .= $randNum[rand(0,$randNumLength-1)]; 			
		}
		
		$registerId = $registerTime . $str;
		//echo $registerId;		
		
		//生成session_token
		
		$theSessionNum = $theUtil->setSessionToken(8);
		
		//将信息存储到session中
		$_SESSION['username'] = $theUsername;
		$_SESSION['password'] = $thePassword;
		$_SESSION['session_token'] = $theSessionNum;	


		//生成加入时间
		$joinTime = date("Y-m-d H:i:s");
		
		if($theSelect == 'add'){
			$registerAddSql = "insert into member (iid, username, password, sex, email, tel, join_time, user_head, city,  user_introduction, session_token) values ('$registerId', '$theUsername', '$thePassword','$theSex', '$theEmail', '$theTel', '$joinTime', '$theHead', '$theCity', '$theIntroduction', '$theSessionNum')";
		}
		if($theSelect == 'edit'){
			$registerAddSql = "update member set username = '$theUsername', password = '$thePassword', sex = '$theSex', email = '$theEmail', tel = '$theTel', user_head = '$theHead', city = '$theCity', user_introduction = '$theIntroduction' where username = '$theOldUserName'";
		}			
	
		
		$registerAddSql_db = mysql_query($registerAddSql);
		
		if($registerAddSql_db){
			//在插入数据成功后，上传图片
			//获取文件的路径
			$rootPath = $_SERVER['DOCUMENT_ROOT'];
			//echo $rootPath;
			
			$thePath = $rootPath .'/program/admin/static/h-ui.admin/img/head/';
			
			//图片上传base64引入
			$imgArray = $theUtil->fileUpload($thePath,$theHead,$theBaseImg);
			//print_r($imgArray); 			
			
			//组装返回注册信息数组
			$returnRegisterArray = array(
				status => 200,
				msg => "注册成功",
				img => $imgArray,
				result => ''
			);		
		}
		else{
			//组装返回注册信息数组
			$returnRegisterArray = array(
				status => 400,
				msg => "注册失败",
				result => ''
			);									
		}
		//将返回数组转换成json返回给前端
		$returnRegisterJson = json_encode($returnRegisterArray);
		print_r($returnRegisterJson);		
	}
	
	//获取用户列表
	function memberList(){
		$listSql = "select * from member where 1 = 1";
		$listSql_db = mysql_query($listSql);
		$listArray = array();
		
		while($listSql_db_array = mysql_fetch_assoc($listSql_db)){
			$listArray[] = 	$listSql_db_array;	
		}
		
		//组装数组
		$returnMemberListArray = array(
			status => 200,
			msg => '用户数组数据返回成功',
			result => $listArray
		);
		
		//将数据转换为json返回给前端
		$returnMemberListJson = json_encode($returnMemberListArray);		
		print_r($returnMemberListJson);	
	}
	
	//删除用户
	function memberDel(){
		$theMemberId = $_GET['memberId'];
		$delMemberSql = "delete from member where iid = '$theMemberId'";
		$delMemberSql_db = mysql_query($delMemberSql);
		
		if($delMemberSql_db){
			//返回组成数组
			$delMemberArray = array(
				status => 200,
				msg => '用户删除成功',
				result => '删除用户'.$theMemberId.''
			);
			
		}
		else{
			$delMemberArray = array(
				status => 400,
				msg => '用户删除失败',
				result => ''
			);						
		}
		//转成json返回给前端
		$delMemberJson = json_encode($delMemberArray);
		print_r($delMemberJson);
	}
	
	function theReturn($turl){
		if($turl == 'registerAdd'){
			$this->registerAdd();
		}
		if($turl == 'memberList'){
			$this->memberList();			
		}
		if($turl == 'memberDel'){
			$this->memberDel();		
		}
	}	
}