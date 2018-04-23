<?php 
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
	
	//展示文章列表
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
	}
	
}