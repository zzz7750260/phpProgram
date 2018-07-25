<?php
include('../system.mysql.int.php');
include('friendlink.class.php');

//获取调用的方法
$turl = $_GET['turl'];

if(!$turl){
	$turl = $_POST['turl'];	
}

//调用类
$theFriendLink = new friendLink();

$theFriendLink->theReturn($turl);