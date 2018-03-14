<?php 
header("Content-Type:text/html; charset=UTF-8");
include('../system.mysql.int.php');
include('category.class.php');


$getCategory = new theCategory();

//获取get或者post传进来的链接,根据链接调用不同的方法
$theUrl = $_GET['turl'];
if(!$theUrl){
	$theUrl = $_POST['turl'];	
}

$getCategory->theReturn($theUrl);
