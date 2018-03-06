<?php
session_start(); 
header("Content-Type:text/html; charset=UTF-8");
include('../system.mysql.int.php');

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
	
	echo '<script>setTimeout(function(){window.location.href="'.$theURL .'admin/"},6000)</script>';
}