<?php 
include('../system.mysql.int.php');
include('index.class.php');

//获取传递过来的参数
$turl = $_GET['turl'];

if(!$turl){
	$turl = $_POST['turl'];
}

$theIndex = new theIndex();

$theIndex->returnIndex($turl);