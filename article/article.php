<?php

include('../server/system.mysql.int.php');
include('../server/system.util.php');
ob_start();

$theAid = $_GET['article_id'];
//数据库查询
$articleSql = "select a.*,b.* from article as a left join category as b on a.category_id = b.cid where a.aid = '$theAid'";
$articleSql_db = mysql_query($articleSql);

$articleArray = array();

while($articleSql_db_array = mysql_fetch_assoc($articleSql_db)){
	$articleArray = $articleSql_db_array;	
}

print_r($articleArray);

/*
//定义路径
define('APP_PATH', dirname(dirname(__FILE__)));
//echo APP_PATH;

echo "文件名1：".$articleArray[0]['categoryyw']."<br/>";
echo "文件名2：".$articleArray['categoryyw'];
//创建文件夹
if(!file_exists($articleArray[0]['categoryyw'])){
	mkdir($articleArray['categoryyw'],0777,true);
	echo "文件夹传建成功";
}
else{
	echo "该文件夹已经存在";
	
}

*/

//调用传建的文件夹
$theUtil = new util();
$theUtil->mkdirWj($articleArray['categoryyw']);

//获取当前的路径
define('APP_PATH',dirname(__FILE__));
echo "当前目录下的文件路径".APP_PATH;

//文件静态化
//读取缓存区的内容
$out1 = ob_get_clean();

echo $out1;

//判断是否存在参数，存在参数就实现页面静态化
$theOb = $_GET['getOb'];

if($theOb == "ob")
{
	//将内容静态化输出
	if(file_put_contents(APP_PATH.'/article/'.$articleArray['categoryyw'].'/'.$articleArray['aid'].'.html',$out1)){
		echo "输出成功";
	}
	else{
		echo "输出失败";
	}
}



