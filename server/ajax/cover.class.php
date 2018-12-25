<?php
include('../system.util.php');
ob_start();
class theCover{
	//public $theUtil = new util();
	
	function addCover(){
		$theUtil = new util();
		$theTitle = $_POST['cover-title'];
		$theTitleYw = $_POST['cover-yw'];
		$theImg = $_POST['cover-pic'];
		$theShort = $_POST['cover-short'];
		$theAuthor = $_POST['cover-author'];
		$theBaseImg = $_POST['baseImg'];//获取base地址链接
		$theType = $_POST['set-type'];
		$theCoverEditId = $_POST['editId'];
		$theCoverCategory = $_POST['cover-category'];
		
		
		//获取当前时间
		$theTime = date('Y-m-d H:i:s');
		
		//获取根目录上传相关的图片信息
		
		
		//根据$theType来识别是添加还是为编辑
		if($theType == 'add'){
			$coverSql = "insert into page (ptitle, title_yw, author, cover_img, cover_introduction, cover_time, cover_category) values ('$theTitle', '$theTitleYw', '$theAuthor', '$theImg', '$theShort', '$theTime', '$theCoverCategory')";					
		}
		if($theType == 'edit'){
			$coverSql = "update page set ptitle = '$theTitle', author = '$theAuthor', cover_img = '$theImg' , cover_introduction = '$theShort', cover_category = '$theCoverCategory' where pid = '$theCoverEditId'";
		}
		
		$coverSql_db = mysql_query($coverSql);
		
		if($coverSql_db){
			//检测是否有base，如果有传递过来的图片信息就存储到文件夹中
			if($theBaseImg){
				//获取根目录
				$theRoot = $_SERVER['DOCUMENT_ROOT'];
				//echo $theRoot;
				
				//详细地址
				$thePath = $theRoot."/upload/user_cover/";
				
				
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
		if($theUsername =="admin"){
			$theCoverSql = 	"select * from page where 1 = 1";	
		}
		else{
			$theCoverSql = "select * from page where author = '$theUsername'";
		}
		
		$theCoverSql_db = mysql_query($theCoverSql);
		$coverArray = array();
		while($theCoverSql_db_array = mysql_fetch_assoc($theCoverSql_db)){
			$coverArray[] = $theCoverSql_db_array;
		}
		if(count($coverArray)>0){
			//组装数组返
			$returnCoverArray = array(
				status => 200,
				msg => '封面返回成功',
				result => $coverArray,
				num => count($coverArray),
			);
			
		}
		else{
			//组装数组返
			$returnCoverArray = array(
				status => 300,
				msg => '该用户还没传建封面',
				result => '',
				num => count($coverArray),
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
	
	//用户页面静态化
	function userPageOb(){
		
		$theOb = $_GET['getOb']; //静态化标签
		
		//查找出所有用户
		$findUserSql = "select * from member where 1 = 1";
		$findUserSql_db = mysql_query($findUserSql);
		$findUserArray = array();
		while($findUserSql_db_array = mysql_fetch_assoc($findUserSql_db)){
			$findUserArray[] =$findUserSql_db_array;	
		}
		
		//遍历数组
		foreach($findUserArray as $key =>$value){
			//引入模板
			$theUsername = $value['username'];
			include("../template/user-index.php");
			//静态化
			if($theOb == "ob"){
				//获取根目录
				$theRoot = $_SERVER['DOCUMENT_ROOT'];
				//存储路径
				$thePath = $theRoot.'/article/user-page/'.$theUsername.'.html';
				
				//将缓存内容存储到对应的文件夹中
				file_put_contents($thePath,ob_get_contents());
				
				//清除上一次缓存(不关闭缓存区)
				ob_clean();
				
			}
						
		}
				
	}
	
	//封面页面静态化
	function coverPageOb(){
		//获取是否静态化的标识
		$coverOb = $_GET['coverOb'];
		//获取所有的封面列表
		$theCoverListSql = "select * from page where 1 = 1";
		$theCoverListSql_db = mysql_query($theCoverListSql);
		$theCoverListArray = array();
		while($theCoverListSql_db_array = mysql_fetch_assoc($theCoverListSql_db)){
			$theCoverListArray[] = 	$theCoverListSql_db_array;
		}
		//print_r($theCoverListArray);
		//遍历封面数组
		foreach($theCoverListArray as $theCoverListKey => $theCoverListValue){
			//引入封面模板
			$theCover = $theCoverListValue['ptitle'];
			include('../template/cover-index.php');		
			//封面页面静态化
			if($coverOb == 'ob'){
				//设置存储的根目录
				$rootPath = $_SERVER['DOCUMENT_ROOT'];
				$theCoverPath = $rootPath . '/article/cover-page/'.$theCoverListValue['pid'].'.html';
				//静态化封面页面
				file_put_contents($theCoverPath,ob_get_contents());
				//清除上一次缓存（防止接下来保存的页面会有上个页面的记录）
				ob_clean();
			}
		}
	}	
	
	//封面列表静态化
	//$thePageNum：每页信息的数量
	function coverListOb(){
		$thePageNum = $_GET['thePageNum'];
		//echo $thePageNum;
		//获取所有的封面
		$coverListSql = "select * from page where 1 = 1 order by pid DESC";
		$coverListSql_db = mysql_query($coverListSql);
		//获取总条数
		$coverListSql_db_num = mysql_num_rows($coverListSql_db);
		
		//获取页数(舍去小数点)
		$pageNum = floor($coverListSql_db_num / $thePageNum);
		$pageNumYs = $coverListSql_db_num % $thePageNum;
		
		//echo '余数'. $pageNumYs;
		//如果存在余数，页数加一
		if($pageNumYs>0){
			$pageNum = $pageNum +1;
		}
		//echo '页数'. $pageNum;
	
		$coverListArray = array();
		while($coverListSql_db_array = mysql_fetch_assoc($coverListSql_db)){
			$coverListArray[] = $coverListSql_db_array;		
		}
		//定义在模板内的函数，因为需要重复引用模板会造成重复定义，因而需要定义在模板外
		//根据封面的名称获取对应的文章
		function getArticleArrayHtml($coverName){
			$commonUtil = new util();
			//echo $coverName;
			$getCoverSql = "select a.*,b.* from article as a join category as b on a.category_id = b.cid where a.article_cover = '$coverName' order by aid DESC limit 0,4";
			$getCoverSql_db = mysql_query($getCoverSql);
			$getCoverArray = array();
			while($getCoverSql_db_array = mysql_fetch_assoc($getCoverSql_db)){
				$getCoverArray[] = $getCoverSql_db_array;
			}
			
			//组装对应的html
			foreach($getCoverArray as $key => $value){
				$coverArrayHtml .= '<li class="col-md-3"><a href="'.$commonUtil->isHttpsCheckSelect().'//'.$_SERVER['HTTP_HOST'].'/article/'.$value['categoryyw'].'/show-'.$value['aid'].'.html"><div class="cover-array-img"><img class="img-responsive" src="../../upload/cover/'.$value['article_img'].'"><div class="cover-array-img-title"><h5>'.$value['title'].'</h5></div></div></a></li>';						
			}
			return $coverArrayHtml;
		};
		
		//获取分页信息
		for($i = 0; $i < $pageNum; $i++){
			//获取每个分页组
			//页数从1开始，因而需要加1
			$p = $i+1;
			$thePageArray = array();
			for($j = $i*$thePageNum; $j<$i*$thePageNum+$thePageNum; $j++){
				$thePageArray[] = $coverListArray[$j];
			}	
			//引入列表模板
			include('../template/cover-list.php');
			
			//是否静态化
			$theOb = $_GET['theOb'];
			if($theOb == 'ob'){
				//获取根目录
				$rootPath = $_SERVER['DOCUMENT_ROOT'];
				//组合存储路径
				$thePath = $rootPath . '/article/cover-page/cover-list-' . $p . '.html';
				//利用file_put_contents 和 ob_get_contents将缓存内容存储到文件中
				file_put_contents($thePath,ob_get_contents());
				//清除缓存避免重复
				ob_clean();
			}		
		}
		
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
		if($turl == "userPageOb"){
			$this->userPageOb();
		}
		if($turl == "coverPageOb"){
			$this->coverPageOb();			
		}
		if($turl == "coverListOb"){
			$this->coverListOb();
		}
	}	
}