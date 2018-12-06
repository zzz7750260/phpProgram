<?php 
include_once(dirname(dirname(__FILE__))."/system.qiniu.php");
include_once(dirname(dirname(__FILE__))."/system.util.php");

class theFm{
	function addFmArticle(){
		$fmAuther = $_POST['FM-author'];
		$fmKeyword = $_POST['FM-keyword'];
		$fmShort = $_POST['FM-short'];
		$fmShortTitle = $_POST['FM-short-title'];
		$fmTitle = $_POST['FM-title'];
		$articleFm = $_POST['article-FM'];
		$articleFmFile = $_POST['article-FM-file'];
		$articlePic = $_POST['article-pic'];
		$articlePicFile = $_POST['article-pic-file'];
		$categorySelect = $_POST['category_select'];
		$fmArticle = $_POST['fm-article'];
		
		$theUtil = new util();
		
		//生成日期
		$theDate = date("Y-m-d h:i:s");
		//插入数据库
		//echo $theDate;
		$insertFmArticleSql = "insert into fm (fm_category, f_title, f_keyword, f_img, f_admin, f_short, f_time, f_container, f_fm) values ('$categorySelect', '$fmTitle', '$fmKeyword', '$articlePic', '$fmAuther', '$fmShort', '$theDate', '$fmArticle', '$articleFm')";	
		$insertFmArticleSql_db = mysql_query($insertFmArticleSql);
		if($insertFmArticleSql_db){
			//当数据插入成功时，将图片base64转为图片
			if($articlePicFile){
				//设置上传路径
				$setImgPath = "/upload/fm/";
				$thePath = $theUtil->physicalPath($setImgPath);
				$returnImgArray = $theUtil->fileUpload($thePath,$articlePic,$articlePicFile);
			}
			
			//组装返回前端数据									
			$returnArray = array(
				status => 200,
				msg => "fm插入成功",
				result => array(
					img =>$returnImgArray
				)
			);
			
		}
		else{
			//组装返回前端数据									
			$returnArray = array(
				status => 400,
				msg => "fm插入失败",
				result => ""
			);
		}	

		//将数组转为json返回给前端
		$returnJson = json_encode($returnArray);
		print($returnJson);
	}
	
	//获取fm文件的上传
	function getFmFile(){
		$f = $_FILES['file'];
		//var_dump($f);
		$theUtil = new util();		
		
		if ($_FILES["file"]["error"] > 0){
			//echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
			$returnFmArray = array(
				status => 404,
				msg => "上传出现错误",
				result => "",
			);	
			
		}
		else{
			//echo "Upload: " . $_FILES["file"]["name"] . "<br />";
			//echo "Type: " . $_FILES["file"]["type"] . "<br />";
			//echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
			//echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";

			$setFmPath = "/mp3/".$_FILES["file"]["name"];
			$fmPath = $theUtil->physicalPath($setFmPath);
			
			
			if (file_exists($fmPath)){
				//echo $_FILES["file"]["name"] . " already exists. ";
				
				//设置返回前端数组
				$returnFmArray = array(
					status => 400,
					msg => "该歌曲已存在",
					result =>"",
				);
			}
			else{
				if(move_uploaded_file($_FILES["file"]["tmp_name"],$fmPath)){
					//当上传成功后，将文件上传到七牛
					$qiniuUtil = new qiniuUtil();						
					$returnQiniuValue = $qiniuUtil->uploadFile($setFmPath);
					
					//设置七牛cdn
					$qiniuUtil->setQiniuUse('qiniu');
					//设置网络返回地址
					//$webPath = $theUtil->webPath($setFmPath);
					
					$webPath = $qiniuUtil->useQiniuCdnWeb();
					$webFmPath = $webPath . $setFmPath;
					
					//设置返回前端数组
					$returnFmArray = array(
						status => 200,
						msg => "歌曲上传成功",
						result =>array(
							info => $_FILES["file"],
							url => $webFmPath,
							qiniu => $returnQiniuValue,
						),
					);					
				}
				else{
					$returnFmArray = array(
						status => 500,
						msg => "歌曲上传出现错误",
						result => "",
					);	
				}
				
			}
			
		}
		//将数组转为json返回给前端
		$returnFmJson = json_encode($returnFmArray);
		print($returnFmJson);
	}
	
	//插入fm弹幕
	function insertFmDm(){
		$theFmArticle = $_POST['the-fm-article'];
		//$theFmCategory = $_POST['the-fm-category'];
		$theDmKey = $_POST['the-dm-key'];
		$theDmValue = $_POST['the-dm-value'];
		//echo $theDmKey."=========";
		//echo $theFmArticle."=========";		
		//echo $theDmValue;
		//插入数据库
		$insertFmSql = "insert into fm_dm (d_article, d_key, d_text) values($theFmArticle, '$theDmKey', '$theDmValue')";
		$insertFmSql_db = mysql_query($insertFmSql);
		if($insertFmSql_db){
			//组装fm返回前端数组
			$returnFmDmArray = array(
				status => 200,
				msg => "FM数据插入成功",
				result => '',
			);
		}
		else{
			//组装fm返回前端数组
			$returnFmDmArray = array(
				status => 400,
				msg => "FM数据插入失败",
				result => '',
			);
		}
		//转换为json返回给前端
		$returnFmDmJson = json_encode($returnFmDmArray);
		print($returnFmDmJson);
	}
	
	//获取对应的弹幕组(get)
	function getFmDmArray(){
		$theFmArticleId = $_GET['fm-editid'];
		$getDmArraySql = "select * from fm_dm where d_article = $theFmArticleId";
		$getDmArraySql_db = mysql_query($getDmArraySql);
		$getDmArray = array();
		while($getDmArraySql_db_array = mysql_fetch_assoc($getDmArraySql_db)){
			$getDmArray[] = $getDmArraySql_db_array;
		}
		if($getDmArray){
			//组装返回前端数组
			$returnFmDmArray = array(
				status => 200,
				msg => '返回fm弹幕成功',
				result => $getDmArray,
			);
		}
		else{
			$returnFmDmArray = array(
				status => 400,
				msg => '返回fm弹幕失败',
				result => '',
			);			
		}
		
		//将数组转换为json返回给前端
		$returnFmDmJson = json_encode($returnFmDmArray);
		print($returnFmDmJson);
	}
	
	//根据id来返回对于的fm文章的信息
	function getFmArticle(){
		$theFmArticleId = $_GET['the-fm-id'];
		$selectFmArticleSql = "select * from fm where fid = '$theFmArticleId'";
		$selectFmArticleSql_db = mysql_query($selectFmArticleSql);
		if($selectFmArticleSql_db){
			$selectFmArticleSql_db_array = mysql_fetch_assoc($selectFmArticleSql_db);
			//设置返回fm为七牛的cdn
			$qiniuUtil = new qiniuUtil();
			$qiniuUtil->setQiniuUse('qiniu');		
			$theRootPath = $qiniuUtil->useQiniuCdnWeb();		
			$selectFmArticleSql_db_array['mpurl'] = $theRootPath . '/mp3/' . $selectFmArticleSql_db_array['f_fm'];
			//组装返回前端的fm信息
			$returnFmInfoArray = array(
				status => 200,
				msg => '返回fm文章信息成功',
				result => $selectFmArticleSql_db_array,
			);
			
		}
		else{
			$returnFmInfoArray = array(
				status => 400,
				msg => '返回fm文章信息失败',
				result => '',
			);
		}
		$returnFmInfoJson = json_encode($returnFmInfoArray);
		print_r($returnFmInfoJson);
	}
	
	//更新fm的article
	function editFmArticle(){
		//获取fm的对于id
		$theFmId = $_POST['fm-editid'];
		//获取传递过来的fm信息
		$fmAuther = $_POST['FM-author'];
		$fmKeyword = $_POST['FM-keyword'];
		$fmShort = $_POST['FM-short'];
		$fmShortTitle = $_POST['FM-short-title'];
		$fmTitle = $_POST['FM-title'];
		$articleFm = $_POST['article-FM'];
		$articleFmFile = $_POST['article-FM-file'];
		$articlePic = $_POST['article-pic'];
		$articlePicFile = $_POST['article-pic-file'];
		$categorySelect = $_POST['category_select'];
		$fmArticle = $_POST['fm-article'];
		
		$theUtil = new util();
		
		//生成日期
		$theDate = date("Y-m-d h:i:s");		
		//获取对应的fm信息
		$getFmArticleSql = "select * from fm where fid = '$theFmId'";
		$getFmArticleSql_db = mysql_query($getFmArticleSql);
		$getFmArticleSql_db_array = mysql_fetch_assoc($getFmArticleSql_db);
		
		if($articleFm == $getFmArticleSql_db_array['f_fm']){
			//如果fm不更新，就只更新一下数据
			$updataFmArticleSql = "update fm set fm_category = '$categorySelect', f_title = '$fmTitle', f_keyword = '$fmKeyword', f_img = '$articlePic', f_admin = '$fmAuther', f_short = '$fmShort', f_time = '$theDate', f_container = '$fmArticle' where fid = '$theFmId'";
			$updataFmArticleSql_db = mysql_query($updataFmArticleSql); 
			if($updataFmArticleSql_db){
				//如果有新的图片base传递过来，就存储图片
				if($articlePicFile){
					//设置存储图片路径
					$setImgPath = "/upload/fm/";
					$thePath = $theUtil->physicalPath($setImgPath);
					$returnImgInfo = $theUtil->fileUpload($thePath,$articlePic,$articlePicFile);				
				}	
				//组装返回前端信息
				$returnFmUpdateArray = array(
					status => 250,
					msg => 'fm更新成功',
					result => '',
					imgresult => $returnImgInfo, 
				);
			}
			else{
				//组装返回前端信息
				$returnFmUpdateArray = array(
					status => 400,
					msg => 'fm更新失败',
					result => '',
					imgresult => $returnImgInfo, 
				);				
				
			}
			
		}
		else{
			//fm不同时，需要重新上传fm到七牛中
			$updataFmArticleSql = "update fm set fm_category = '$categorySelect', f_title = '$fmTitle', f_keyword = '$fmKeyword', f_img = '$articlePic', f_admin = '$fmAuther', f_short = '$fmShort', f_time = '$theDate', f_container = '$fmArticle', f_fm = '$articleFm' where fid = '$theFmId'";		
			$updataFmArticleSql_db = mysql_query($updataFmArticleSql);
			if($updataFmArticleSql_db){
				//如果有新的图片base传递过来，就存储图片
				if($articlePicFile){
					//设置存储图片路径
					$setImgPath = "/upload/fm/";
					$thePath = $theUtil->physicalPath($setImgPath);
					$returnImgInfo = $theUtil->fileUpload($thePath,$articlePic,$articlePicFile);				
				}	
				//组装返回前端信息
				$returnFmUpdateArray = array(
					status => 200,
					msg => 'fm更新成功',
					result => '',
					imgresult => $returnImgInfo, 
				);				
			}
			else{
				//组装返回前端信息
				$returnFmUpdateArray = array(
					status => 400,
					msg => 'fm更新失败',
					result => '',
					imgresult => $returnImgInfo, 
				);						
			}
		}
		//将数组转换为json，返回给前端
		$returnFmUpdateJson = json_encode($returnFmUpdateArray);
		print_r($returnFmUpdateJson);
	}	
	
	//根据fm的id来获取对应的返回fm的路径
	function getFmUrl(){
		$theFmId = $_GET['the-fm-id'];
		$getFmInfoArraySql = "select * from fm where fid = $theFmId";
		$getFmInfoArraySql_db = mysql_query($getFmInfoArraySql);
		$getFmInfoArray = array();
		while($getFmInfoArraySql_db_array = mysql_fetch_assoc($getFmInfoArraySql_db)){
			$getFmInfoArray = $getFmInfoArraySql_db_array;	
		}
		if($getFmInfoArray){
			//组装返回的url
			$qiniuUtil = new qiniuUtil();
			//设置七牛cdn
			$qiniuUtil->setQiniuUse('qiniu');
			$theRootPath = $qiniuUtil->useQiniuCdnWeb();		
			$fmPath = $theRootPath . '/mp3/' . $getFmInfoArray['f_fm'];
			
			
			$returnFmInfoArray = array(
				status => 200,
				msg =>'fm返回成功',
				result =>$fmPath
			);
		}
		else{
			$returnFmInfoArray = array(
				status => 400,
				msg =>'fm返回失败',
				result =>'',
			);						
		}
		
		//将数组转换为json返回给前端
		$returnFmInfoJson = json_encode($returnFmInfoArray);
		print($returnFmInfoJson);
	}		
	
	//根据id删除对于的fm
	function delFm(){
		$theFmId = $_GET['the-fm-id'];
		$delFmSql = "delete from fm where fid = $theFmId";
		$delFmSql_db = mysql_query($delFmSql);
		if($delFmSql_db){
			$returnFmDelArray = array(
				status => 200,
				msg => "fm删除成功",
				result => ''
			);
		}
		else{
			$returnFmDelArray = array(
				status => 400,
				msg => "fm删除失败",
				result => ''
			);					
		}
		$returnFmDelJson = json_encode($returnFmDelArray);
		printf($returnFmDelJson);
	}
			
	//fm文章页静态化
	function getFmArticleOb(){
		//获取需要静态化的分类	
		$theFmCategory = $_GET['fm-article-category'];
		$getOb = $_GET['get-ob'];  //获取是否需要静态化
		
		$selectCategorySql = "select a.*,b.*from fm as a left join category as b on a.fm_category = b.cid where if($theFmCategory = 0,1 = 1, fm_category = $theFmCategory)";
		$selectCategorySql_db = mysql_query($selectCategorySql);
		$selectCategoryArray = array();
		while($selectCategorySql_db_array = mysql_fetch_assoc($selectCategorySql_db)){
			$selectCategoryArray[] = $selectCategorySql_db_array;
		}
		
		$theUtil = new util();	
		
		//开取静态化
		ob_start();
		$n = 0;
		
		//var_dump($selectCategoryArray);
		//遍历数组，进行静态化
		foreach($selectCategoryArray as $fmArticleKey => $fmArticleValue){
			$getFmArticleArray = $fmArticleValue;
			include("../template/fm-article-template-v.php");			
			
			//当需要静态化时
			if($getOb == 'ob'){
				//设置路径，生成对应文件夹
				$thePath = $theUtil->physicalPath('/fm/');
				$theUtil->createFile($thePath,$fmArticleValue['categoryyw']);
				$fmPath = $thePath . $fmArticleValue['categoryyw'] . '/fm-' . $fmArticleValue['fid'] . '.html';
				
				//文件静态化
				if(file_put_contents($fmPath,ob_get_contents())){
					$n = $n+1;
					ob_clean();   
					
				}
			}	
		}
		
		//组装返回前端数组
		if($n>0){
			$returnFmResult = array(
				status => 200,
				msg => 'fm文章页面静态化成功',
				result => $n
			);			
		}
		else{
			$returnFmResult = array(
				status => 400,
				msg => 'fm文章页面静态化失败',
				result => ''
			);			
		}
		
		//将数组转换为json返回给前端
		$returnFmJson = json_encode($returnFmResult);
		print($returnFmJson);
	}
	
	//fm汇总列表静态化
	function fmCategoryCollectList(){
		//echo '获取';
		//获取分类的汇总信息
		//获取是否进行静态化
		$theOb = $_GET['getOb'];
		$theUtil = new util();
				
		$selectCategoryCollectSql = "select * from category where cpid = 0 and categorytype = 'fm'";
		$selectCategoryCollectSql_db = mysql_query($selectCategoryCollectSql);
		$selectCategoryCollectArray = array();
		while($selectCategoryCollectSql_db_array = mysql_fetch_assoc($selectCategoryCollectSql_db)){
			$selectCategoryCollectArray[] =	$selectCategoryCollectSql_db_array;	
		}	
		
		//var_dump($selectCategoryCollectArray);
		$n = 0;
		$rootPath = $_SERVER['DOCUMENT_ROOT'];
		ob_start();		
		
		//遍历数组，静态化fm列表汇总
		foreach($selectCategoryCollectArray as $selectCategoryCollectKey => $selectCategoryCollectValue){
			$theCategoryFatherArray = $selectCategoryCollectValue;
			include('../template/fm-category-collect-template.php');	
			if($theOb == 'ob'){		
				//生成对应的文件夹
				$afterPath = $_SERVER['DOCUMENT_ROOT'] .'/fm/';
				$theUtil->createFile($afterPath,$selectCategoryCollectValue['categoryyw']);
				$listPath = $afterPath . $selectCategoryCollectValue['categoryyw'] . '/' . $selectCategoryCollectValue['categoryyw'] . '-list.html';
				if(file_put_contents($listPath,ob_get_contents())){		
					$n = $n+1;
					ob_clean();
					
				}
			}
		}
		
		if($n>0){
			//设置返回前端数组
			$returnArray = array(
				status => 200,
				msg => 'fm汇总列表静态成功',
				result => $n
			);
		}
		else{
			$returnArray = array(
				status => 400,
				msg => 'fm汇总列表静态失败',
				result => $n
			);			
		}
		//将数组转换为json返回给前端
		$returnJson = json_encode($returnArray);
		print($returnJson);		
	}
		
	//静态化fm列表
	function fmCategoryList($pageNum =5){
		//获取分类选项
		$theCategoryId = $_GET['get-category-id'];
		if(!$pageNum){
			$pageNum = $_GET['get-page-num'];		
		}
		$theOb = $_GET['getOb'];
		ob_start();
		$theUtil = new util();
		
		
		//获取总条数
		$selectCategoryArticleNumSql = "select * from fm where fm_category = '$theCategoryId'";
		$selectCategoryArticleNumSql_db = mysql_query($selectCategoryArticleNumSql);
		$selectCategoryArticleNumSql_num = mysql_num_rows($selectCategoryArticleNumSql_db);
		
		//echo $selectCategoryArticleNumSql_num. "=======================";
		//$selectCategoryArticleNumSql_num 大于0时存在文章，进行文章分页
		if($selectCategoryArticleNumSql_num > 0){
			$pages = floor($selectCategoryArticleNumSql_num / $pageNum);
			$pagez = $selectCategoryArticleNumSql_num % $pageNum; // 大于0说明不整除，页数需要加1
			if($pagez>0){
				$pages = $pages+1;
			}
			
			//echo $pages . "页数";
			
			//获取分类的详细信息
			$theCategoryInfoSql = "select * from category where cid = '$theCategoryId'";
			$theCategoryInfoSql_db = mysql_query($theCategoryInfoSql);
			$theCategoryInfoSql_db_array = mysql_fetch_assoc($theCategoryInfoSql_db);
			//var_dump($theCategoryInfoSql_db_array); 
						
			//循环输出对应的文章页面
			for($i = 0; $i < $pages; $i++){
				$setPage = $i+1;
				$setPageNum = $i*$pageNum + $pageNum;	
				$setBegin = $i*$pageNum;
				//获取该id下的对应的文章
				$selectCategoryArticleSql = "select a.*,b.* from fm as a left join category as b on a.fm_category = b.cid where a.fm_category = '$theCategoryId' order by a.fid DESC limit $setBegin,$setPageNum";
				$selectCategoryArticleSql_db = mysql_query($selectCategoryArticleSql);
				$selectCategoryArticleArray = array();
				while($selectCategoryArticleSql_db_array = mysql_fetch_assoc($selectCategoryArticleSql_db)){
					$selectCategoryArticleArray[] = $selectCategoryArticleSql_db_array;
				}
				//var_dump($selectCategoryArticleArray);
				//引入分类列表模板
				include('../template/fm-category-list-template.php');
				
				//静态化控制
				if($theOb == 'ob'){
					//设置静态化路径
					$rootFmPathSet = $_SERVER['DOCUMENT_ROOT'] .'/fm/';
					//生成对应的文件夹
					$theUtil->createFile($rootFmPathSet,$theCategoryInfoSql_db_array['categoryyw']);
					//设置生成的页面路径
					$saveFmArticlePath = $rootFmPathSet . $theCategoryInfoSql_db_array['categoryyw'] . '/' . $theCategoryInfoSql_db_array['categoryyw'] .'-'. $setPage.'.html';
					
					if(file_put_contents($saveFmArticlePath,ob_get_contents())){
						ob_clean();
					}
				}
				
				
			}
			
			//组装数组返回给前端
			$returnFmListObArray = array(
				status => 200,
				msg => 'fm列表页静态化成功',
				result => $i
			);												
			
		} 
		else{
			//组装数组返回给前端
			$returnFmListObArray = array(
				status => 400,
				msg => 'fm列表页静态化失败',
				result => $i
			);				
			
		}
		//将数组转换为json返回给前端
		$returnFmListObJson = json_encode($returnFmListObArray);

		print($returnFmListObJson);
	}	
	
	function theReturn($turl){
		if($turl == 'addFmArticle'){
			$this->addFmArticle();
		}
		if($turl == 'editFmArticle'){
			$this->editFmArticle();		
		}
		if($turl == 'getFmFile'){
			$this->getFmFile();
		}
		if($turl == 'insertFmDm'){
			$this->insertFmDm();
		}
		if($turl == 'getFmArticle'){
			$this->getFmArticle();
		}
		if($turl == 'getFmDmArray'){
			$this->getFmDmArray();
		}
		if($turl == 'delFm'){
			$this->delFm();
		}
		if($turl == 'getFmArticleOb'){
			$this->getFmArticleOb();
		}
		if($turl == 'getFmUrl'){
			$this->getFmUrl();
		}
		if($turl == 'fmCategoryCollectList'){
			$this->fmCategoryCollectList();
		}
		if($turl == 'fmCategoryList'){
			$this->fmCategoryList();
		}
	}	
}