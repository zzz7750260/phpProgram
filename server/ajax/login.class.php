<?php 
class theLogin{	
	//获取的参数(构造函数)
	//function __construct(){				
	//}
	
	function loginUsernameAjax(){
		//echo $theUserName;
		//echo $thePassWord;
		$theUserName = $_GET['username'];
		$thePassWord = $_GET['password'];
		//echo $theUserName;
		$sql = "select * from member where username = '$theUserName'";
		$sql_db = mysql_query($sql);

		$num = mysql_num_rows($sql_db);

		echo $num;				
		return $num;	
	}
	
	function loginYz(){
		$sql = "select * from member where username = '$theUserName' and password = '$thePassWord'";
		$sql_db = mysql_query($sql);

		$num = mysql_num_rows($sql_db);
		
		echo $num;
		return $num;
		/*
		$hostUrl = $_SERVER['HTTP_HOST'];

		echo $num."<br/>";
		echo $hostUrl;

		$theURL = "http://".$hostUrl."/program/";
		
		if($num == 0){
			echo "登录密码错误!";
			echo '<script>setTimeout("window.location.href="'.$theURL .'loginYz.php",3000)';
		}
		else{
			echo "登录成功";
			echo '<script>setTimeout(function(){window.location.href="'.$theURL .'/admin/"},6000)</script>';
		}
		*/		
	}
	
	//获取用户的信息
	function getUserInfo(){
		$theUserNames = $_SESSION['username'];
		$thePassWords = $_SESSION['password'];
		$userInfoArray = array();
		//echo $theUserNames;
		$user_sql = "select * from member where username = '$theUserNames' and password = '$thePassWords'";
		$user_sql_db = mysql_query($user_sql);
		while($user_sql_db_array = mysql_fetch_assoc($user_sql_db)){
			$userInfoArray = $user_sql_db_array;	
		}
		//print_r($userInfoArray);
		//echo "<br/>";
		//转为json数据
		$userJson = json_encode($userInfoArray);
		print_r($userJson);
	}
	
	//根据传进来的权限放回对应的菜单数据
	function getMenu($therole,$thepid){		
		//$menuList = array();
		//$menuListChild = array();
		$menuRoleSql = "select * from menu where menurole like '%$therole%' and mpid = '$thepid'";
		$menuRoleSql_db = mysql_query($menuRoleSql);
		$i = 0;
		while($menuRoleSql_db_arr = mysql_fetch_assoc($menuRoleSql_db)){
			$menuList[$i] = $menuRoleSql_db_arr;
			$trole = $menuList[$i]['menurole'];
			$tpid = $menuList[$i]['mid'];
			//echo "角色:".$trole."<br>";
			//echo "id:".$tpid."<br>";;					
			$menuList[$i]['child'] = $this->getMenu($trole,$tpid);;
			$i++;
		}
		//print_r($menuList);
		return $menuList;
		
	}
		
	//根据传进来的页面参数调用
	function theReturn($theUrl){
		if($theUrl =='loginusername'){
			$this->loginUsernameAjax();//$this 非常重要，$this 相当于当前的class
		}
		if($theUrl =='themenu'){
			$therole = $_GET['getrole'];
			$thepid = $_GET['getpid'];
			//echo $therole;
			//echo $thepid;
			//print_r($this-> getMenu($therole,$thepid));					
			//返回json数据
			//echo "<br/>";
			$menujson = json_encode($this-> getMenu($therole,$thepid));
			print_r($menujson);
		}
		if($theUrl == 'theUser'){
			$this->getUserInfo();		
		}
	}
}

