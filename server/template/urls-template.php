<?php
	include_once('../system.util.php');
	//$commonUtil = new util();
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
		$commonUtil = new util();
		$theReturnPath = $commonUtil->isHttpsCheckSelect().'//' . $theRootPath;
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
		$categoryPathAfter = '/article/'.$categoryArrayValue['categoryyw'].'/' .$categoryArrayValue['categoryyw'].'-list-1.html';
		
		$categoryPath .= returnPath($categoryPathAfter) .PHP_EOL;
			
	}
	echo $categoryPath;
	
?>
<?php 
	//输出子分类
	//获取子分类
	$childCategoryArraySql = "select * from category where cpid != 0";
	$childCategoryArraySql_db = mysqli_query($conn,$childCategoryArraySql);
	$childCategoryArray = array();
	while($childCategoryArraySql_db_array = mysqli_fetch_assoc($childCategoryArraySql_db)){
		$childCategoryArray[] = $childCategoryArraySql_db_array;		
	}

	//mysqli_free_result($childCategoryArraySql_db);
	
	//print_r($childCategoryArray);
	
	//遍历输出子分类
	foreach($childCategoryArray as $childCategoryKey => $childCategoryValue){
		//组装分类的链接
		$locXmlChildCategoryUrlPath = '/article/'.$childCategoryValue['categoryyw'].'/'.$childCategoryValue['categoryyw'].'-1.html';
		$locXmlChildCategoryUrl = returnPath($locXmlChildCategoryUrlPath);
		$locXmlChildCategory .= $locXmlChildCategoryUrl .PHP_EOL;
	}
	echo $locXmlChildCategory;	

?>
<?php
	//输出封面列表
	$theCoverListPathAfter = "/article/cover-page/cover-list-1.html";
	$theCoverListPath = returnPath($theCoverListPathAfter);
	$locXmltheCoverList = $theCoverListPath.PHP_EOL;
	echo $locXmltheCoverList;
?>



<?php 
	//输出所有封面信息
	$coverPageArraySql = "select * from page where 1 = 1";
	$coverPageArraySql_db = mysqli_query($conn,$coverPageArraySql);
	$coverPageArray = array();
	while($userPageArraySql_db_array = mysqli_fetch_assoc($coverPageArraySql_db)){
		$coverPageArray[] = $userPageArraySql_db_array;
	}
	//print_r($coverPageArray);

	//遍历输出对应的信息
	foreach($coverPageArray as $coverPageKey => $coverPageValue){
		//设置路径
		$theCoverPathAfter = '/article/cover-page/' . $coverPageValue['pid'] . '.html';
		$theCoverPath = returnPath($theCoverPathAfter);
		$locXmltheCover .= $theCoverPath .PHP_EOL;
	}
	echo $locXmltheCover;
	
?>


<?php
	//输出所有用户页面
	$userPageArraySql = "select * from member where 1 = 1";
	$userPageArraySql_db = mysqli_query($conn,$userPageArraySql);
	$userPageArray = array();
	while($userPageArraySql_db_array = mysqli_fetch_assoc($userPageArraySql_db)){
		$userPageArray[] = $userPageArraySql_db_array;
	}
	//print_r($userPageArray);

	//遍历输出对应的信息
	foreach($userPageArray as $userPageKey => $userPageValue){
		//设置路径
		$theUserPathAfter = '/article/user-page/' . $userPageValue['username'] . '.html';
		$theUserPath = returnPath($theUserPathAfter);
		$locXmltheUser .= $theUserPath .PHP_EOL;
	}
	echo $locXmltheUser;
?>


<?php 
	//输出所有文章
	$articleArraySql = 'select a.*,b.* from article as a join category as b on a.category_id = b.cid where 1 = 1 order by aid DESC';
	$articleArraySql_db = mysqli_query($conn,$articleArraySql);
	$articleArray = array();
	while($articleArraySql_db_array = mysqli_fetch_assoc($articleArraySql_db)){
		$articleArray[] = $articleArraySql_db_array;
	}
	//print_r($articleArray);
	//遍历数组输出对应的文章节点
	
	foreach($articleArray as $articleKey => $articleValue){
		//组装文章链接
		$locXmlArticleUrlPath = '/article/'.$articleValue['categoryyw'].'/'.$articleValue['aid'].'.html';
		
		$locXmlArticleUrl = returnPath($locXmlArticleUrlPath);		
		
		$locXmlArticle .= $locXmlArticleUrl	.PHP_EOL;
	}
	echo $locXmlArticle;
	
?>
