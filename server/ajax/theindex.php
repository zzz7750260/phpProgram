<?php 
include('../system.mysql.int.php');
include('index.class.php');

//��ȡ���ݹ����Ĳ���
$turl = $_GET['turl'];

if(!$turl){
	$turl = $_POST['turl'];
}

$theIndex = new theIndex();

$theIndex->returnIndex($turl);