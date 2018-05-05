<?php
include('../system.mysql.int.php');

$theAid = $_GET['article_id'];
//数据库查询
$articleSql = "select a.*,b.* from article as a left join category as b on a.category_id = b.cid where a.aid = '$theAid'";
$articleSql_db = mysql_query($articleSql);

$articleArray = array();

while($articleSql_db_array = mysql_fetch_assoc($articleSql_db)){
	$articleArray = $articleSql_db_array;	
}

print_r($articleArray);

//定义路径
define('APP_PATH', dirname(dirname(__FILE__)));
echo APP_PATH;

//创建文件夹
if(!file_exists(APP_PATH.'/article')){
	mkdir('article',0777,true);
	echo "文件夹传建成功";
}
else{
	echo "该文件夹已经存在";
	
}
