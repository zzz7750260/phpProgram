<?php 
include('../server/system.mysql.int.php');
include('../server/system.util.php');
ob_start();

$theCategory = $_GET['categoryNum'];
//查询数据库

$theCategorySql = "select a.*,b.* from article as a left join category as b on a.category_id = b.cid where 1 = 1 and IF('$theCategory' = 0,0 = 0, a.category_id = '$theCategory')";
$theCategorySql_db = mysql_query($theCategorySql);

$theCategoryArray = array();

while($theCategorySql_db_array = mysql_fetch_assoc($theCategorySql_db)){
	$theCategoryArray[] = $theCategorySql_db_array;
	
}

//封装返回数组
$theReturnArray = array(
	"status" => 200,
	"msg" => "返回数组成功",
	"result" => $theCategoryArray
);

//将返回的数组转换为json

$theReturnJson = json_encode($theReturnArray);

print_r($theReturnJson);

//获取当前文章的位置


//检测article对应的category文件夹是否存在,并创建出对应的文件夹
