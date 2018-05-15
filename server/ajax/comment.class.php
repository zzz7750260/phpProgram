<?php 
	class theComment{
		//插入对应的评论
		function insertComment(){
			//获取对应的数据
			$theCPid = $_POST['theCPid'];
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
			
			
		}		
		
		function returnComment($turl){
			if($turl == "insertComment"){
				$this->insertComment();				
			}			
		}
	}