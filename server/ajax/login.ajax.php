<?php 
header("Content-Type:text/html; charset=UTF-8");
include('../system.mysql.int.php');



$theUserName = $_GET['username'];
//$thePassWord = $_GET['password'];

//echo $theUserName;

//echo $thePassWord;

$sql = "select * from member where username = '$theUserName'";
$sql_db = mysql_query($sql);

$num = mysql_num_rows($sql_db);

echo $num;
