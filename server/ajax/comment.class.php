<?php 
	class theComment{
		//插入对应的评论
		function insertComment($theType = 'add'){
			//获取对应的数据
			$theCPid = $_POST['theCPid'];//评论的父类id
			$articleId = $_POST['articleId'];
			$theName = $_POST['theName'];
			$theEmail = $_POST['theEmail'];
			$theWeb = $_POST['theWeb'];
			$theComment = $_POST['theComment'];					
			//echo $theName;
			
			//获取编辑的类型
			$getCommentType = $_POST['theCommentType'];
			
			//获取需要编辑的评论id
			$theCommentId = $_POST['theCommentId'];
										
			if(!$getCommentType){
				$getCommentType = $theType;
			}
						
			//获取评论的时间
			$theCmDate = date("Y-m-d h:i:sa");
			
			if($getCommentType == 'add'){
				//设置sql
				$insertSql = "insert into comment(cmpid, cmtid, cm_name, cm_email, cm_web, cm_comment, cm_time)values('$theCPid','$articleId','$theName','$theEmail','$theWeb','$theComment', '$theCmDate')";			
			}
			if($getCommentType == 'edit'){
				//执行编辑的sql
				$insertSql = "update comment set cm_name = '$theName', cm_email = '$theEmail', cm_web = '$theWeb',cm_comment = '$theComment' where cmid = '$theCommentId'";
				
			}
				
			$insertSql_db = mysql_query($insertSql);
			
			if($insertSql_db){
				if($getCommentType == 'add'){
					$returnCommentArray = array(
						status => 200,
						msg => "评论插入成功",
						result => ''
					);										
				}
				if($getCommentType == 'edit'){
					$returnCommentArray = array(
						status => 200,
						msg => "评论编辑成功",
						result => ''
					);								
				}
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
			$listComnetSql = "select * from comment where cmtid = '$theArticleId' and cmpid = 0 order by cmid ASC limit $theCommentLimitNum, $theCommentLimit";						
			$listComnetSql_db = mysql_query($listComnetSql);
			$listComnetSqlArray = array();
			$i = 0;
			while($listComnetSql_db_array = mysql_fetch_assoc($listComnetSql_db)){
				$listComnetSqlArray[$i] = $listComnetSql_db_array;			
				$theArray = $this->listChildComment($listComnetSql_db_array['cmid']);
				$listComnetSqlArray[$i]['childComment'] = $theArray['commentChildList'];				
				$listComnetSqlArray[$i]['childCommentNum'] = $theArray['commentChildNum'];
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
			$listChildSql = "select * from comment where cmpid = '$cpid' order by cmid DESC limit 0 , 3";
			$listChildSql_db = mysql_query($listChildSql);
			$listChildArray = array();
			while($listChildSql_db_array = mysql_fetch_assoc($listChildSql_db)){
				$listChildArrayList[] = $listChildSql_db_array;		
			}
			
			$listChildNumSql = "select * from comment where cmpid = '$cpid'";
			$listChildNumSql_db = mysql_query($listChildNumSql);
			$listChildSql_db_num = mysql_num_rows($listChildNumSql_db);
			
			$listChildArray = array(
				commentChildList => $listChildArrayList,
				commentChildNum => $listChildSql_db_num,
			);
			
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
		
		//根据评论id获取对应的评论信息
		function getCommentInfo(){
			$commentId = $_GET['theCommentId'];
			//echo $commentId;
			$selectComment = "select * from comment where cmid = '$commentId'";
			$selectComment_db = mysql_query($selectComment);
			$selectCommentArray = array();
			if($selectComment_db){
				$selectComment_db_array = mysql_fetch_assoc($selectComment_db);
				$returnCommentArray = array(
					status => 200,
					msg => '选择评论成功',
					result => $selectComment_db_array
				);
			}
			else{
				$returnCommentArray = array(
					status => 400,
					msg => '没有该评论',
					result => ''
				);								
			}
			$returnCommentJson = json_encode($returnCommentArray);
			print_r($returnCommentJson);
		}
		
		//根据评论id获取该评论下的所有评论
		function getIdCommentTree($cid=''){
			if(!$cid){
				//获取传递过来的对应评论id
				$commentId = $_GET['comment-id'];			
			}
			else{
				$commentId = $cid;
			}
			
			//获取对应的评论
			$getCommentSql = "select * from comment where cmid = '$commentId'";
			$getCommentSql_db = mysql_query($getCommentSql);
			$getCommentSql_db_array = mysql_fetch_assoc($getCommentSql_db);
			
			$commentTreeArray[] = $getCommentSql_db_array;	
			//$commentTreeArray[] = $this->getIdCommentTreeChild($commentId,$commentTreeArray);
			$commentTreeArray[] = $this->getIdCommentTreeChild($commentId);
			$commentJson = json_encode($commentTreeArray);
			print_r($commentJson);
		}
		
		//结合上面的功能
		//获取所有子分类
		function getIdCommentTreeChild($cpid,&$childArray = array()){
			$getChildCommentSql = "select * from comment where cmpid = '$cpid'";
			$getChildCommentSql_db = mysql_query($getChildCommentSql);
			//$childArray = array();
			while($getChildCommentSql_db_array = mysql_fetch_assoc($getChildCommentSql_db)){
				
				$childArray[] = $getChildCommentSql_db_array;
				//$childArray[] = $this->getIdCommentTreeChild($getChildCommentSql_db_array['cmid'],$childArray);
				$this->getIdCommentTreeChild($getChildCommentSql_db_array['cmid'],$childArray);
			}
			return $childArray;
		}
		
		//后端对应的列表
		function showCommentList(){
			$theUser = $_GET['the-user'];
			$commentType = $_GET['comment-type'];
						
			if($theUser == 'admin'){
				if($commentType == 'mine'){
					$getCommentListSql = "select a.title,b.* from article as a right join comment as b on a.aid = b.cmtid where cm_name = 'admin'";
				}
				if($commentType == 'all'){
					$getCommentListSql = "select a.title,b.* from article as a right join comment as b on a.aid = b.cmtid";
				}
			}
			else{
				if($commentType == 'all'){
					$getCommentListSql = "select a.title,b.* from article as a right join comment as b on a.aid = b.cmtid where a.article_author = '$theUser'";	
				}
				if($commentType == 'mine'){
					$getCommentListSql = "select a.title,b.* from article as a right join comment as b on a.aid = b.cmtid where cm_name = '$theUser'";
				}
				
			}
			$getCommentListSql_db = mysql_query($getCommentListSql);
			if($getCommentListSql_db){
				$getCommentListArray = array();
				while($getCommentListSql_db_array = mysql_fetch_assoc($getCommentListSql_db)){
					$getCommentListArray[] = $getCommentListSql_db_array;
					$returnCommentArray = array(
						status => 200,
						msg => '返回对应的评论信息成功',
						result => $getCommentListArray,
					);
				}				
			}
			else{
				$returnCommentArray = array(
					status => 400,
					msg => '没有对应的评论信息',
					result => $getCommentListArray,
				);				
			}
			
			$returnCommentJson = json_encode($returnCommentArray);
			print_r($returnCommentJson);
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
			if($turl == 'getIdCommentTree'){
				$this->getIdCommentTree();
			}
			if($turl == 'showCommentList'){
				$this->showCommentList();				
			}
			if($turl == 'getCommentInfo'){
				$this->getCommentInfo();			
			}
		}
	}