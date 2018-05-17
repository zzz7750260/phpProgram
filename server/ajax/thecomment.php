<?php 
include('../system.mysql.int.php');

//获取传递过来的参数
$turl = $_GET['turl']; 


if(!$turl){
	$turl = $_POST['turl'];	
}
//echo $turl;
//引入comment的模块
include('comment.class.php');

$theComment = new theComment();

$theComment ->returnComment($turl);