<?php
session_start(); 
header("Content-Type:text/html; charset=UTF-8");
include('../system.mysql.int.php');
include("../system.util.php");


$theUserName = $_GET['username'];
$thePassWord = $_GET['password'];

$sql = "select * from member where username = '$theUserName' and password = '$thePassWord'";
$sql_db = mysql_query($sql);

$num = mysql_num_rows($sql_db);

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
	//setcookie('username',$theUserName,time()+3600);
	$_SESSION['username'] = $theUserName;
	$_SESSION['password'] = $thePassWord;
	
	//同时生成一个session_token 存到对应用户的数据库中
	//获取随机数
	$theUtil = new util();
	$theRandNum = $theUtil->setSessionToken(8);
	echo $theRandNum;
	
	//将session_token存入$_SESSION中
	$_SESSION['session_token'] = $theRandNum;
	
	//将随机码存入数据库以及以及存入cookie中
	$loginSessionSql = "update member set session_token = '$theRandNum' where username = '$theUserName' and password = '$thePassWord'";
	
	$loginSessionSql_db = mysql_query($loginSessionSql);
	
	echo "状态为：".$loginSessionSql_db;
	
	//根据用户的权限登录到不同的页面
	$sqlArray = array(); 
	while($sql_db_array = mysql_fetch_assoc($sql_db)){
		$sqlArray = $sql_db_array;
	}
	if($sqlArray['role'] == 'admin'){
		echo '<script>setTimeout(function(){window.location.href="'.$theURL .'admin/"},6000)</script>';
	}
	else{
		echo '<script>setTimeout(function(){window.location.href="'.$theURL .'admin/user-index.html"},6000)</script>';		
	}
		
	//设置请求头
	//header('session-token:'.$theRandNum.'');

	//获取请求头
	//print_r(getallheaders());
	
	
	//用js设置cookie
	//过期时间
	//$endTime = time()+7200;
	//echo '<script> document.cookie = "session_token ='.$theRandNum.'; expires= '.$endTime.'.toGMTString();";</script>';
	
	//同时将session_token的值存到cookiet中,因为之前已经设置了header，所以直接用setcookie会引起错误
	//setcookie("sessionToken",$theRandNum,time()+7200);
	//header("Set-Cookie:sessionToken=".$theRandNum.";  expires=".gmstrftime("%A,%d-%b-%Y %H:%M:%S GMT",time()+9600));
	
	//echo '<script>setTimeout(function(){window.location.href="'.$theURL .'admin/"},6000)</script>';
}