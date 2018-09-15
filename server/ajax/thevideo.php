<?php 
include("../system.mysql.int.php");
include("video.class.php");

$theVideo = new theVideo();

//获取传递过来的调用方法

$theUrl = $_GET['turl'];
if(!$theUrl){
	$theUrl = $_POST['turl'];		
}

$theVideo->theReturn($theUrl);

?>