<?php 
ob_start();
include('../system.util.php');
class theArticleClass{
	function addArticle(){
		$theTitle = $_POST['article-title'];
		$articleShortTitle = $_POST['article-short-title'];
		$articleAuthor = $_POST['article-author'];
		$articleKeyword = $_POST['article-keyword'];
		$articlePic = $_POST['article-pic'];
		$articlePl = $_POST['article-pl'];
		$articleShort = $_POST['article-short'];
		$articleSource = $_POST['article-source'];
		$categorySelect = $_POST['category_select'];
		$plDataEnd = $_POST['pl-data-end'];
		$plDataStart = $_POST['pl-data-start'];
		$theArticle = $_POST['the-article'];
		$articleStatus = $_POST['article-status'];
		
		//$theCommit = $_POST[''];
		
		$addSql = "insert into article (title, short_title, category_id, article_key, article_short, article_author, article_source, commit_start, commit_end, article_img, article_container, article_status, commit_status) values ('$theTitle', '$articleShortTitle', '$categorySelect', '$articleKeyword', '$articleShort', '$articleAuthor', '$articleSource', '$plDataStart', '$plDataEnd', '$articlePic', '$theArticle', '$articleStatus', '$articlePl')";
		
		$addSql_db = mysql_query($addSql);			
		if($addSql_db){
			$theResult = "数据插入成功";
			//返回数组
			$resArray = array(
				"status" =>200,
				"msg" =>'ok',
				"result" =>$theResult
			);
			
		}
		else{
			$theResult = "数据插入失败";	
			//返回数组
			$resArray = array(
				"status" =>400,
				"msg" =>'err',
				"result" =>$theResult
			);			
		}
		
		//将数组转为json
		$resJson = json_encode($resArray);	
		print_r($resJson);
				
	}
	
	//后端展示文章列表
	function articleList(){
		//获取文章是否查询的文章状态，是查询草稿还是查询公开的，或者是查询所有
		$theStatus = $_GET['status']; 
		$theAuthor = $_GET['author'];
		//$articleListSql = "select a.*,b.* from article as a left outer join category as b on a.category_id = b.cid where 1 = 1 and if('$theStatus' is NULL, 0 = 0, a.article_status like CONCAT('%$theStatus%'))";
		
		//根据用户名来查询结果
		$articleListSql = "select a.*,b.* from article as a left outer join category as b on a.category_id = b.cid where a.article_author = '$theAuthor' and if('$theStatus' is NULL, 0 = 0, a.article_status like CONCAT('%$theStatus%'))";
		
		$articleListSql_db = mysql_query($articleListSql);
		$articleListSqlArray = array();
		while($articleListSql_db_array = mysql_fetch_assoc($articleListSql_db)){
			$articleListSqlArray[] = $articleListSql_db_array;
		}
		//print_r($articleListSqlArray);		
		
		//组装返回数组
		$returnArray = array(
			"status" => 200,
			"ms" => "后台文章列表返回成功",
			"result" => $articleListSqlArray
		);
		$atheJson = $this->returnJson($returnArray);
		print_r($atheJson);
	}
	
	//查看文章接口
	function checkArticle(){
		$theArticleId = $_GET['article_Id'];
		$articleSql = "select * from article where aid = '$theArticleId'";
		$articleSql_db = mysql_query($articleSql);
		$articleArray = array();
		while($articleSql_db_array = mysql_fetch_assoc($articleSql_db)){
			$articleArray = $articleSql_db_array;
		}
		
		//组装返回文章结果的值
		$returnArticleArray = array(
			"status" => 200,
			"msg" => "文章返回成功",
			"result" => $articleArray
		);
		
		//将array转换为json
		$returnArticleJson = $this->returnJson($returnArticleArray);
		print_r($returnArticleJson);
	}
	
	//组装返回json数组给前端
	function returnJson($arr){
		//将获取到的数组转成json并进行返回
		$theJson = json_encode($arr);
		return $theJson;
	}
	
	//单文章静态化
	function oBarticle(){
		$theAid = $_GET['article_id'];
		//数据库查询
		$articleSql = "select a.*,b.* from article as a left join category as b on a.category_id = b.cid where a.aid = '$theAid'";
		$articleSql_db = mysql_query($articleSql);

		$articleArray = array();
		
		while($articleSql_db_array = mysql_fetch_assoc($articleSql_db)){
			$articleArray = $articleSql_db_array;	
		}

		print_r($articleArray);
		$value = $articleArray;
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
		
		/*引入文章页模板*/
		include("../template/article.php");
		
		//调用传建的文件夹
		$theUtil = new util();
		$theUtil->mkdirWj($articleArray['categoryyw']);

		//获取当前的路径
		define('APP_PATH',dirname(dirname(__FILE__)));
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
	}
	
	function theObMoreArticle(){
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
		define("APP_PATH2",dirname(dirname(dirname(__FILE__))));
		echo "当前路径为".APP_PATH2;
		//检测article对应的category文件夹是否存在,并创建出对应的文件夹
		$theUtilFile = new util();

		//判断是否有静态化请求，如果有，需对文件进行相关的输出
		$theOb = $_GET['getOb'];
		if($theOb == 'obMore'){
			//循环数组，并输出静态文件
			foreach($theCategoryArray as $key => $value){
				//生成文章对于分类的文件夹
				$theUtilFile->mkdirWj($value['categoryyw']);
				//文章页静态化输出
				print_r($value);
				$moreOut = ob_get_contents();
				//echo $moreOut;
				if(file_put_contents(APP_PATH2.'/article/'.$value['categoryyw'].'/'.$value['aid'].'.html',$moreOut)){
					echo $value['aid']."输出成功";		
					
					//清除缓冲区,防止内容重复输出;
					ob_clean();
				}
				else{
					echo $value['aid']."输出失败";
				}
			}		
		}
	
	}
	
	//前端返回的文章列表
	function frontArticleList(){
		//获取当前的分类
		$theCategory = $_GET['category_id'];
		//获取当前页数
		$thePage = $_GET['page_num'];
		//获取查询文章数量
		$theLimit = $_GET['limit_num'];
		
		//将页数和查询文章数转换为数字
		$thePageNum = intval($thePage);
		$theLimitNum = intval($theLimit);
		echo $theCategory;
		
		//返回的文章数据查询
		$theArticleSql = "select a.*,b.* from article as a left join category as b on a.category_id = b.cid where b.categoryyw = '$theCategory' limit $thePageNum,$theLimitNum";
		
		$theArticleSql_db = mysql_query($theArticleSql);
		
		$theArticleArray = array();
		
		while($theArticleSql_db_array = mysql_fetch_assoc($theArticleSql_db)){
			$theArticleArray[] = $theArticleSql_db_array;
		}
		print_r($theArticleArray);		
	}

			
	//调用功能类
	function theReturn($turl){
		if($turl == "addArticle"){
			$this->addArticle();
		}
		if($turl == 'articleList'){
			$this->articleList();			
		}
		if($turl == 'checkArticle'){
			$this->checkArticle();
		}
		if($turl == 'oBarticle'){
			$this->oBarticle();
		}
		if($turl == 'theObMoreArticle'){
			$this-> theObMoreArticle();	
		}	
		if($turl == 'frontArticleList'){
			$this->frontArticleList();
		}
	}
}