<?php 
session_start();
header("Content-Type:text/html; charset=UTF-8");
//引入数据库
include('../system.mysql.int.php');
//引入功能类
include('article.class.php');

$turl = $_GET['turl'];

if(!$turl){
	$turl = $_POST['turl'];	
}

$theArticleArray = new theArticleClass();

$theArticleArray->theReturn($turl);