<?php
	include("../system.mysql.int.php");
	include("fm.class.php");

	//获取传递过来的参数
	$theUrl = $_GET['turl'];
	if(!$theUrl){
		$theUrl = $_POST['turl'];
	}	
	$theFm = new theFm;
	
	$theFm->theReturn($theUrl);