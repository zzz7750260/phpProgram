<?php 
	include("../system.mysql.int.php");
	include("wx.class.php");
	
	$theWeixin = new theWeixin();		
	$turl =  $_GET['turl'];
	
	if(!$turl){
		$turl =  $_POST['turl'];
	}
	
	//根据请求过来的参数传递不同的方法
	$theWeixin->theReturn($turl);	
?>