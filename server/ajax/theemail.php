<?php
include('email.class.php');

$theEmail = new theEmail();

//��ȡ���õĲ���

$turl = $_GET['turl'];
if(!$turl){
	$turl = $_POST['turl'];	
}

$theEmail->theReturn($turl)

?>