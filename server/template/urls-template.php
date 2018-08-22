<?php
	//echo php_uname('s'); //获取服务器版本
	//echo $_SERVER['PROCESSOR_IDENTIFIER'];
	//echo PHP_EOL;  根据不同环境变量的换行符，在windows下会是/r/n，在linux下是/n，在mac下是/r
	
	//链接数据库
		$conn = mysqli_connect("localhost","root","","mysql");
	if($conn){
		
		//echo "数据库服务器连接成功";
		//选择数据库
		$conn_db = mysqli_select_db($conn,"myprogram");
		if($conn_db){
		//echo "数据库连接成功";
			mysqli_query($conn,"set names utf8");	
			
		}
		
	}
			
	
	//获取当前更新的时间
	$theDate = date("Y-m-d");
	//echo $theDate;

	//获取当前网站根目录
	//封装返回路径
	function returnPath($thePath = ''){
		$theRootPath = $_SERVER['HTTP_HOST'];
		//echo $theRootPath;
		
		$theReturnPath = "http://" . $theRootPath;
		if($thePath){
			$theReturnPath = $theReturnPath . $thePath; 			
		}
		return $theReturnPath;
	}
	
	//组装首页连接
	//需要用到PHP_EOL进行换行
	echo $indexUrl = returnPath() . PHP_EOL; 
	
	//组装分类列表链接
	$categoryArraySql = "select * from category where cpid = 0";
	$categoryArraySql_db = mysqli_query($conn,$categoryArraySql);
	$categoryArray = array();
	while($categoryArraySql_db_array = mysqli_fetch_assoc($categoryArraySql_db)){
		$categoryArray[] = $categoryArraySql_db_array;		
	}
	//print_r($categoryArray);
	
	//遍历数组循环输出父分类链接
	foreach($categoryArray as $categoryArrayKey => $categoryArrayValue){
		//组成链接后缀
		$categoryPathAfter = '/article/'.$categoryArrayValue['categoryyw'].'/' .$categoryArrayValue['categoryyw'].'list-1.html';
		
		$categoryPath .= returnPath($categoryPathAfter) .PHP_EOL;
			
	}
	echo $categoryPath;
	
?>
