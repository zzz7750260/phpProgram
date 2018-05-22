<?php 
	include('../system.mysql.int.php');
	include('curl.class.php');
			
	//获取相关参数
	$turl = $_GET['turl'];
	if(!$turl){
		$turl = $_POST['turl'];	
	}
	
	//类调用
	$theCurl = new theCurl;
	$theCurl -> curlReturn($turl);	
?>