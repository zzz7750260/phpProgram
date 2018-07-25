<?php
include('email.class.php');

$theEmail = new theEmail();

//获取调用的参数

$turl = $_GET['turl'];
if(!$turl){
	$turl = $_POST['turl'];	
}

$theEmail->theReturn($turl)

?>