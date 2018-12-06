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
			
			//获取评论的时间
			$theCmDate = date("Y-m-d h:i:sa");
			
			//设置sql
			$insertSql = "insert into comment(cmpid, cmtid, cm_name, cm_email, cm_web, cm_comment, cm_time)values('$theCPid','$articleId','$theName','$theEmail','$theWeb','$theComment', '$theCmDate')";
			
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
		
		//列出父类评论
		function listComment(){
			//获取传递过来的文章参数
			//echo "获取传递过来的文章参数";
			$theArticleId  = $_GET['getArticleId'];
			$theCommentPage = $_GET['getCommentPage'];
			$theCommentLimit = $_GET['getCommentLimit'];		
			$theCommentLimitNum = $theCommentPage*$theCommentLimit;	
			//echo $theArticleId;
			$listComnetSql = "select * from comment where cmtid = '$theArticleId' and cmpid = 0 limit $theCommentLimitNum, $theCommentLimit";						
			$listComnetSql_db = mysql_query($listComnetSql);
			$listComnetSqlArray = array();
			$i = 0;
			while($listComnetSql_db_array = mysql_fetch_assoc($listComnetSql_db)){
				$listComnetSqlArray[$i] = $listComnetSql_db_array;
				$listComnetSqlArray[$i]['childComment'] = $this->listChildComment($listComnetSql_db_array['cmid']);
				$i++;				
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
		
		//列出子评论
		function listChildComment($cpid){
			$listChildSql = "select * from comment where cmpid = '$cpid' limit 0 , 3";
			$listChildSql_db = mysql_query($listChildSql);
			$listChildArray = array();
			while($listChildSql_db_array = mysql_fetch_assoc($listChildSql_db)){
				$listChildArray[] = $listChildSql_db_array;		
			}
			return $listChildArray;
		}
		
		//删除评论
		function delComment(){
			//获取comment的id
			$theCommentId = $_GET['comment-id'];
			$delCommentSql = "delete from comment where cmid = '$theCommentId'";
			$delCommentSql_db = mysql_query($delCommentSql);
			//组装返回数组
			if($delCommentSql_db){
				$returnDelCommentArray = array(
					status => 200,
					msg => '评论删除成功',
					result => ''
				);			
			}
			else{
				$returnDelCommentArray = array(
					status => 400,
					msg => '评论删除失败',
					result => ''
				);					
			}
			
			//将数组转为json返回给前端
			$returnDelCommentJson = json_encode($returnDelCommentArray);			
			print_r($returnDelCommentJson);
		}
		
		function returnComment($turl){
			if($turl == "insertComment"){
				$this->insertComment();				
			}
			if($turl == "listComment"){
				$this->listComment();
			}
			if($turl == 'delComment'){
				$this->delComment();
			}
		}
	}