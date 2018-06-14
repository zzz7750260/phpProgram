<?php
include('../system.mysql.int.php');
include('member.class.php');

$theUrl = $_GET['turl'];

if(!$theUrl){
	$theUrl = $_POST['turl'];	
}

$memberUtil = new theMember;

$memberUtil->theReturn($theUrl);