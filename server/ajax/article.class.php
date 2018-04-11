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
	
	//调用功能类
	function theReturn($turl){
		if($turl == "addArticle"){
			$this->addArticle();
		}		
	}
	
}