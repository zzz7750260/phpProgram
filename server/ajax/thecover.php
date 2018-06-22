<?php
include('../system.mysql.int.php');
include('cover.class.php');

$theCover = new theCover();

$theUrl = $_GET['turl'];

if(!$theUrl){
	$theUrl = $_POST['turl'];	
}

$theCover->theReturn($theUrl);