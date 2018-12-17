<?php
include("../system.util.php");
ob_start(); //开启缓冲区功能
class theIndex{		
	//设置文件的路径
	function getTheParh(){
		define('THEPART', dirname(dirname(dirname(__FILE__))));
		//echo THEPART;
		return THEPART;
	}
	
	//设置网站信息后端接口
	function setIndexInfo(){
		//获取传递过来的用户信息
		$theIndexMain = $_POST['index-main'];
		$theIndexTitle = $_POST['index-title'];
		$theIndexKeyword = $_POST['index-keyword'];
		$theIndexShort = $_POST['index-short'];
		$theIndexAuthor = $_POST['index-author'];
		$theIndexRecord = $_POST['index-record'];
		//echo $theIndexMain;
		
		//查询网站设置的表格中是否有值，如果有的话，数据库为修改，如果没有的话，为插入
		$haveSql = "select * from web_info where 1 = 1";
		$haveSql_db = mysql_query($haveSql);
		$haveSql_db_num = mysql_num_rows($haveSql_db);
		//echo $haveSql_db_num;
		
		if($haveSql_db_num == 0){
			//插入网站信息
			$setIndexSql = "insert web_info (web_name, web_title, web_keyword, web_short, web_author, web_record) values ('$theIndexMain', '$theIndexTitle', '$theIndexKeyword', '$theIndexShort', '$theIndexAuthor', '$theIndexRecord')";
		}
		else{
			//修改网站信息
			$setIndexSql = "update web_info set web_name = '$theIndexMain' , web_title = '$theIndexTitle' , web_keyword = '$theIndexKeyword', web_short = '$theIndexShort', web_author = '$theIndexAuthor', web_record = '$theIndexRecord' where 1 = 1";			
		}
		$setIndexSql_db = mysql_query($setIndexSql);
		
		if($setIndexSql_db){
			//组装返回数据
			$returnIndexArray = array(
				'status' => 200,
				'msg' => '网站信息设置插入成功',
				'result' =>''
			);			
		}
		else{
			$returnIndexArray = array(
				'status' => 400,
				'msg' => '网站信息设置失败',
				'result' =>''
			);				
		}
		$returnIndexJson = json_encode($returnIndexArray);	
		print_r($returnIndexJson);
	}
	
	//获取网站的设置信息
	function getIndexInfo(){
		$theType = $_GET['theType'];
				
		$getInfSql = "select * from web_info where 1 = 1";
		$getInfSql_db = mysql_query($getInfSql);
		$getInfSqlArray = array();
		while($getInfSql_db_array = mysql_fetch_assoc($getInfSql_db)){
			$getInfSqlArray[] = $getInfSql_db_array;
		}
		
		//检测数组是否为空数组
		$result = empty($getInfSqlArray);
		if($result == true){
			//组装返回信息
			$returnGetInfArray = array(
				'status' => 400,
				'msg' => '网站信息还没设置',
				'result' => ''
			);
		}
		else{
			$returnGetInfArray = array(
				'status' => 200,
				'msg' => '网站信息返回成功',
				'result' => $getInfSqlArray
			);			
		}	
		if($theType  = 'json'){
			$returnGetInfJson = json_encode($returnGetInfArray);
			print_r($returnGetInfJson);
		}
		else{
			print_r($returnGetInfArray);
		}
	}
	
	//
	
	//主页静态化请求
	function obHtml(){
		//开启缓冲区功能
		ob_start();
		//引入主页的模板
		include('../template/index-template.php');
		//获取文件存放的路径
		$thePath = $this->getTheParh();
		echo "地址为：" . $thePath;
		//是否进行静态化处理
		$theOb = $_GET['getob'];
		if($theOb == 'ob'){
			//将数据存入文件
			if(file_put_contents($thePath.'/index.html',ob_get_clean())){
				//组建返回的数据
				$obHtmlReturnArray = array(
					status => 200,
					msg => "首页静态化成功",
					result => ''
				);
				
				//返回给前端的数据
				$obHtmlReturnJson = json_encode($obHtmlReturnArray); 
				print_r($obHtmlReturnJson);
			}
		}	

	}
	
	//生成sitemap.xml文件
	function obSitemap(){
		
		//引入sitemap的模板
		include("../template/sitemap-template.php");
		
		//获取是否为静态化
		$theOb = $_GET['getOb'];
		if($theOb == 'ob'){		
			//获取存储路径
			$theRootPathAfter = '/sitemap.xml';
			
			$theUtil = new util();
			$theRootPath = $theUtil->physicalPath($theRootPathAfter);
			//$thePath = 
			//echo $theRootPath; 
			if(file_put_contents($theRootPath,ob_get_clean())){
				//组装返回前端数据
				$returnSitemapArray = array(
					status => 200,
					msg => 'sitemap.xml文件生成成功',
					result => ''
				);
				
			}
			else{
				$returnSitemapArray = array(
					status => 400,
					msg => 'sitemap.xml文件生成失败',
					result => ''
				);
			}
			
			$returnSitemapJson = json_encode($returnSitemapArray);
			print_r($returnSitemapJson);
		}	
	}
	
	//生成urls.txt文件
	function obUrlsTxt(){
		//引入urls-template的模板
		include("../template/urls-template.php");
		
		//选择是否生成静态化文件
		$theOb = $_GET['getOb'];
		
		//获取存储的路径
		$theFilePathAfter = '/urls.txt';
		
		//获取存储的物理地址
		$theUtil = new util();
		$thePathSave = $theUtil->physicalPath($theFilePathAfter);
	
		
		if($theOb == 'ob'){
			//ob_start();
			if(file_put_contents($thePathSave,ob_get_clean())){
				//组装返回前端数据
				$returnUrlsArray = array(
					status => 200,
					msg => 'urls.txt文件生成成功',
					result => ''
				);
				
			}
			else{
				$returnUrlsArray = array(
					status => 400,
					msg => 'urls.txt文件生成失败',
					result => ''
				);
			}
			
			$returnUrlsJson = json_encode($returnUrlsArray);
			print_r($returnUrlsJson);				
			
		}			
	}
	
	
	
	function returnIndex($turl){
		if($turl == 'setIndexInfo'){
			$this->setIndexInfo();
		}		
		if($turl == 'getIndexInfo'){
			$this->getIndexInfo();
		}
		if($turl =='obHtml'){
			$this->obHtml();
		}
		if($turl =='obSitemap'){
			$this->obSitemap();
		}
		if($turl == "obUrlsTxt"){
			$this ->obUrlsTxt();
		}
	}	
}

//测试
//$a = new theIndex();
//$a->getTheParh();