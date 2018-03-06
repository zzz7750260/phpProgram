<?php 
header("Content-Type:text/html; charset=UTF-8");
include('../system.mysql.int.php');
include('login.class.php');

session_start();
//$theUserName = $_GET['username'];
//$thePassWord = $_GET['password'];


$theUrl = $_GET['turl'];

$getLogin = new theLogin();

$getLogin->theReturn($theUrl);
