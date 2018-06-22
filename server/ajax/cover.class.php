<?php
include('../system.util.php');
class theCover{
	//public $theUtil = new util();
	
	function addCover(){
		$theUtil = new util();
		$theTitle = $_POST['cover-title'];
		$theImg = $_POST['cover-pic'];
		$theShort = $_POST['cover-short'];
		$theAuthor = $_POST['cover-author'];
		$theBaseImg = $_POST['baseImg'];//获取base地址链接
		$theType = $_POST['set-type'];
		$theCoverEditId = $_POST['editId'];
		
		
		//获取当前时间
		$theTime = date('Y-m-d H:i:s');
		
		//获取根目录上传相关的图片信息
		
		
		//根据$theType来识别是添加还是为编辑
		if($theType == 'add'){
			$coverSql = "insert into page (title, author, cover_img, cover_introduction, cover_time) values ('$theTitle', '$theAuthor', '$theImg', '$theShort', '$theTime')";					
		}
		if($theType == 'edit'){
			$coverSql = "update page set title = '$theTitle', author = '$theAuthor', cover_img = '$theImg' , cover_introduction = '$theShort' where pid = '$theCoverEditId'";
		}
		
		$coverSql_db = mysql_query($coverSql);
		
		if($coverSql_db){
			//检测是否有base，如果有传递过来的图片信息就存储到文件夹中
			if($theBaseImg){
				//获取根目录
				$theRoot = $_SERVER['DOCUMENT_ROOT'];
				//echo $theRoot;
				
				//详细地址
				$thePath = $theRoot."/program/admin/static/h-ui.admin/img/cover/";
				
				
				$returnPicArray = $theUtil->fileUpload($thePath,$theImg,$theBaseImg);				
				
			}

			
			//组织返回数组
			$returnCoverArray = array(
				status => 200,
				msg => '封面存储成功',
				result => $returnPicArray,
			);		
		}
		else{
			//组织返回数组
			$returnCoverArray = array(
				status => 400,
				msg => '封面存储失败',
				result => '',				
			);						
		}
		
		$returnCoverJson = json_encode($returnCoverArray);
		print_r($returnCoverJson);
	}
	
	//根据用户获取对应的封面信息
	function coverList(){
		$theUsername = $_GET['username'];
		$theCoverSql = "select * from page where author = '$theUsername'";
		$theCoverSql_db = mysql_query($theCoverSql);
		$coverArray = array();
		while($theCoverSql_db_array = mysql_fetch_assoc($theCoverSql_db)){
			$coverArray[] = $theCoverSql_db_array;
		}
		if(count(coverArray)>0){
			//组装数组返
			$returnCoverArray = array(
				status => 200,
				msg => '封面返回成功',
				result => $coverArray
			);
			
		}
		else{
			//组装数组返
			$returnCoverArray = array(
				status => 300,
				msg => '该用户还没传建封面',
				result => ''
			);		
		}
		//转换为json返回给前端数组
		$returnCoverJson = json_encode($returnCoverArray);
		print_r($returnCoverJson);
	}
	
	
	//删除用户的封面
	function delCover(){
		$theUtil = new util;
		$theCoverId = $_GET['coverId'];
		//echo $theCoverId;
		$coverDelSql = "delete from page where pid = '$theCoverId'";
		$coverDelSql_db = mysql_query($coverDelSql);
		if($coverDelSql_db){
			$returnCoverJson = $theUtil->ajaxJson('200','封面删除成功','');		
		}
		else{
			$returnCoverJson = $theUtil->ajaxJson('400','封面删除失败','');	
		}
		print_r($returnCoverJson);
	}
	
	//根据封面id获取对应的信息
	function getTheCoverInfo(){
		$theUtil = new util;
		$theCoverIdNum	= $_GET['CoverIdNum'];
		$theCoverInfoSql = "select * from page where pid = $theCoverIdNum";
		$theCoverInfoSql_db = mysql_query($theCoverInfoSql);
		if($theCoverInfoSql_db){
			$theCoverArray = array();
			while($theCoverInfoSql_db_array = mysql_fetch_assoc($theCoverInfoSql_db)){
				$theCoverArray = $theCoverInfoSql_db_array;
			}
			$returnCoverJson = $theUtil->ajaxJson('200','封面详情返回成功',$theCoverArray);
			
		}
		else{
			$returnCoverJson = $theUtil->ajaxJson('400','封面详情返回失败','');
			
		}
		//将json信息返回给前端
		print_r($returnCoverJson);
	}
	
	function theReturn($turl){
		if($turl == "addCover"){
			$this->addCover();			
		}
		if($turl == "coverList"){
			$this->coverList();
		}
		if($turl == "delCover"){
			$this->delCover();
		}
		if($turl == "getTheCoverInfo"){
			$this->getTheCoverInfo();
		}
	}	
}