<?php 
	include("../system.mysql.int.php");
	include("spider.class.php");
	
	$spiderUtil = new spiderUtil();
	//获取url
	$turl = $_GET["turl"];
	if(!$turl){
		$turl = $_POST["turl"];
	}
	
	$spiderUtil->returnResult($turl);
?>