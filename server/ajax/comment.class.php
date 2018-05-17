<?php 
	class theComment{
		//插入对应的评论
		function insertComment(){
			//获取对应的数据
			$theCPid = $_POST['theCPid'];//评论的父类id
			$articleId = $_POST['articleId'];
			$theName = $_POST['theName'];
			$theEmail = $_POST['theEmail'];
			$theWeb = $_POST['theWeb'];
			$theComment = $_POST['theComment'];					
			//echo $theName;
			
			//设置sql
			$insertSql = "insert into comment(cmpid, cmtid, cm_name, cm_email, cm_web, cm_comment)values('$theCPid','$articleId','$theName','$theEmail','$theWeb','$theComment')";
			
			$insertSql_db = mysql_query($insertSql);
			
			if($insertSql_db){
				$returnCommentArray = array(
					status => 200,
					msg => "评论插入成功",
					result => ''
				);
			}
			else{
				$returnCommentArray = array(
					status => 400,
					msg => "评论插入失败",
					result => ''
				);				
			}
			
			$returnCommentJson = json_encode($returnCommentArray);			
			print_r($returnCommentJson);
		}
		
		//列出评论
		function listComment(){
			//获取传递过来的文章参数
			//echo "获取传递过来的文章参数";
			$theArticleId  = $_GET['getArticleId'];
			$theCommentPage = $_GET['getCommentPage'];
			$theCommentLimit = $_GET['getCommentLimit'];			
			$theCommentLimitNum = $theCommentPage*$theCommentLimit;	
			//echo $theArticleId;
			$listComnetSql = "select * from comment where cmtid = '$theArticleId' limit $theCommentLimitNum, $theCommentLimit";						
			$listComnetSql_db = mysql_query($listComnetSql);
			$listComnetSqlArray = array();
			while($listComnetSql_db_array = mysql_fetch_assoc($listComnetSql_db)){
				$listComnetSqlArray[] = $listComnetSql_db_array;	
			}
			//组织返回数据
			//判断是否有数据
			if(empty($listComnetSqlArray)){
				$returnListComnetArray = array(
					status => 300,
					msg => '暂无评论',
					result => ''
				);				
			}
			else{
				$returnListComnetArray = array(
					status => 200,
					msg => '评论返回成功',
					result => $listComnetSqlArray
				);
			}
			//返回数据给前端
			$returnListComnetJson = json_encode($returnListComnetArray);
			print_r($returnListComnetJson);
			
		}		
		
		function returnComment($turl){
			if($turl == "insertComment"){
				$this->insertComment();				
			}
			if($turl == "listComment"){
				$this->listComment();
			}
		}
	}