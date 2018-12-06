<?php 
ob_start();
session_start();
include('../system.util.php');
include('../system.article.php');
class theArticleClass{
	
	//获取当前的路径	
	function getAppPath(){
		define("THEAPPPATH",dirname(dirname(dirname(__FILE__))));
		return THEAPPPATH;
	}
	
	function addArticle(){
		$theTitle = $_POST['article-title'];
		$articleShortTitle = $_POST['article-short-title'];
		$articleAuthor = $_POST['article-author'];
		$articleKeyword = $_POST['article-keyword'];
		$articlePic = $_POST['article-pic'];
		$articlePl = $_POST['article-pl'];
		$articleShort = $_POST['article-short'];
		$articleCover = $_POST['article-cover'];
		$articleSource = $_POST['article-source'];
		$categorySelect = $_POST['category_select'];
		$plDataEnd = $_POST['pl-data-end'];
		$plDataStart = $_POST['pl-data-start'];
		$videoPlatform = $_POST['video-platform'];
		$videoSource = $_POST['video-source'];
		$theArticle = $_POST['the-article'];
		$articleStatus = $_POST['article-status'];		
		$articleTag = $_POST['article-tag'];
		
		$articleUploadType = $_POST['article-upload-type'];//获取上传的类型，是添加还是修改
		$articleImgBase = $_POST['article-img-base']; //获取传递过来的图片base64的信息

		//$editArticleId = $_POST['edit-article-id'] //获取传递过来需要编辑的文章id 
		
		$articleTime = date("Y-m-d");  //获取文章生成或者更新的时间
		
		
		
		//需要对传递过来的链接进行转码
		$videoSourceT = urlencode($videoSource);
		
		
		//$theCommit = $_POST[''];
		//echo $articleTime;		
		
		if($articleUploadType == "add"){
			$addSql = "insert into article (title, short_title, category_id, article_key, article_short, article_cover, article_author, article_source, commit_start, commit_end, article_time, article_img, video_platform, video_source, article_container, article_status, commit_status , article_tag) values ('$theTitle', '$articleShortTitle', '$categorySelect', '$articleKeyword', '$articleShort', '$articleCover', '$articleAuthor', '$articleSource', '$plDataStart', '$plDataEnd', '$articleTime', '$articlePic', '$videoPlatform', '$videoSource', '$theArticle', '$articleStatus', '$articlePl', '$articleTag')";
		}
		if($articleUploadType == "edit"){
			//获取传递过来的文章id 
			$editArticleId = $_POST['edit-article-id'];
			$addSql = "update article set title='$theTitle', short_title='$articleShortTitle', category_id='$categorySelect', article_key='$articleKeyword', article_short='$articleShort', article_cover='$articleCover', article_author='$articleAuthor', article_source='$articleSource', commit_start='$plDataStart', commit_end='$plDataEnd', article_time='$articleTime', article_img='$articlePic', video_platform='$videoPlatform', video_source='$videoSource', article_container='$theArticle', article_status='$articleStatus', commit_status='$articlePl', article_tag='$articleTag' where aid =$editArticleId";
		}
		
		$addSql_db = mysql_query($addSql);			
		if($addSql_db){
			$theResult = "数据插入成功";
			
			//公共模块，根据传递过来的base64存储图片
			$theUtil = new util();
			
			//公共模块，获取存储的目录
			$theImgPath = '/upload/cover/';
			
			$theImgSavePath = $theUtil->physicalPath($theImgPath);			
			$returnImgArray = $theUtil->fileUpload($theImgSavePath,$articlePic,$articleImgBase);
			
			
			//返回数组
			//$resArray = array(
			//	"status" =>200,
			//	"msg" =>'ok',
			//	"result" =>$theResult,
			//	"img" => $returnImgArray,
			//);
			
			//在存储成功后返回该文章的id用于存储针对该篇文章的视频
			$checkTheArticleSql = "select * from article where title = '$theTitle'";
			$checkTheArticleSql_db = mysql_query($checkTheArticleSql);
			$checkTheArticleArray = array();
			while($checkTheArticleSql_db_array = mysql_fetch_assoc($checkTheArticleSql_db)){
				$checkTheArticleArray = $checkTheArticleSql_db_array;
			}
			
			//返回数组
			$resArray = array(
				"status" =>200,
				"msg" =>'文章增加或修改成功',
				"result" =>$checkTheArticleArray['aid'],
				"img" => $returnImgArray,
			);		
		}
		else{
			$theResult = "数据插入失败";	
			//返回数组
			$resArray = array(
				"status" =>400,
				"msg" =>'err',
				"result" =>$theResult,
				"img" =>"未插入图片"
			);			
		}
		
		//将数组转为json
		$resJson = json_encode($resArray);	
		print_r($resJson);
				
	}
	
	//接受上传文章封面,并存文件
	function getBaseImgSave(){
		//获取传递过来的图片名字
		$theImgName = $_POST['getImgName'];
		
		//获取传递过来的图片base64
		$theBaseImg = $_POST['getBaseImg'];		
		//设置需要上传的路径
		$uploadUrl = '/upload/cover/';
		//获取根目前根路径
		//echo $this->getAppPath().'<br/>';
		$picRootPath = $this->getAppPath();
		
		//将获取的base64进行图片数据的提取
		//$base64=preg_replace("/^(data:s*image/(w+);base64,)/","",$base64); 
		if(preg_match('/^(data:\s*image\/(\w+);base64,)/', $theBaseImg, $result)){
			//preg_match('/^(data:\s*image\/(\w+);base64,)/', $theBaseImg, $result);
			print_r($result);
			//echo("<br/>=====================<br/>");
			//获取上传图片的类型
			$type = $result[2];	
			//将获取的base64数据转换成图片数据，并用file_put_contents存储到文件夹中
			echo $type;
			//检测是否为图片的常见类型
			if(in_array($type,array('pjpeg','jpeg','jpg','gif','bmp','png'))){
				//将图片进行base64数据转换
				$theImg = base64_decode(str_replace($result[1],'',$theBaseImg));
				//echo $theImg;
				
				//根据日期获取图片文件的名称
				//$picName = date('YmdHis_').'.'.$type;
				
				//组成图片文件的名称
				$picName = $theImgName;
				
				//通过file_put_contents对文件进行存储
				
				//组成需要具体生成的图片
				$thePic = $picRootPath . $uploadUrl . $picName; 
				//echo $thePic;
				//组件返回数据
				if(file_put_contents($thePic,$theImg)){
					$baseArray = array(
						"status" => 200,
						'msg' => "封面上传成功",
						'result' => $thePic,
					);
				}
				else{
					$baseArray = array(
						"status" => 400,
						'msg' => "封面上传失败",
						'result' => '',
					);
				}
				//print_r($baseArray);
			}
		}
		return $baseArray;
	}
	
	//根据id获取编辑文章
	function getArticleInfo(){
		//根据文章id获取对应的文章信息
		$articleId = $_GET['articleId'];
		
		$articleInfoSql ="select a.*, b.*, c.* from article as a join category as b on a.category_id = b.cid join page as c on a.article_cover = c.ptitle where aid = $articleId";
		
		$articleInfoSql_db = mysql_query($articleInfoSql);
		//echo $articleInfoSql_db;
		if($articleInfoSql_db){
			$articleInfoArray = array();
			while($articleInfoSql_db_array = mysql_fetch_assoc($articleInfoSql_db)){
				$articleInfoArray = $articleInfoSql_db_array;
			}
			//print_r($articleInfoArray);
			//组装返回前端的数组
			$returnArticleArray = array(
				status => 200,
				msg => "编号返回成功",
				result=>$articleInfoArray
			);
		}
		else{
			$returnArticleArray = array(
				status => 400,
				msg =>"文章返回失败",
				result=>''
			);
			
		}
		
		//将数组转换为json返回给前端
		$returnArticleJson = json_encode($returnArticleArray);
		print_r($returnArticleJson);	
				
	}
	
	
	//后端展示文章列表
	function articleList(){
		//获取文章是否查询的文章状态，是查询草稿还是查询公开的，或者是查询所有
		$theStatus = $_GET['status'];   
		$theAuthor = $_GET['author'];
		
		//获取用户的权限是否为admin的权限	
		$theRole = $_GET['role'];
		
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
	
	
	//后端展示列表（根据不同用户展示不同结果）
	function userArticleList(){
		$getAuthor = $_GET['theAuthor']; //获取作者
		$getRole = $_GET['theRole'];  //获取权限		
		$getStatus = $_GET['theStatus']; //获取文章状态
		
		$articleListArray = array();
		//当用户为admin时，获取所有的文章
		if($getRole == 'admin'){
			//$articleListArraySql = "select a.*,b.* from article as a left join category as b on a.category_id = b.cid where 1 = 1 and if('$getShenHe' = null,0=0,article_sh = '$getShenHe') order by a.aid DESC";		
			$articleListArraySql = "select a.*,b.* from article as a left join category as b on a.category_id = b.cid where 1 = 1 order by a.aid DESC";	
		}
		else{
			//当权限不是admin时，根据用户名来获取其已经被审核的文章
			$articleListArraySql = "select a.*,b.* from article as a left join category as b on a.category_id = b.cid where a.article_author = '$getAuthor' and if('$getStatus' = '',0 = 0,a.article_status  = '$getStatus') order by a.aid DESC";						
		}
		$articleListArraySql_db = mysql_query($articleListArraySql);
		if($articleListArraySql_db){
			while($articleListArraySql_db_array = mysql_fetch_assoc($articleListArraySql_db)){
				$articleListArray[] = $articleListArraySql_db_array;
			}
			
			//组装返回前端数据
			$returnArticleListArray = array(
				status => 200,
				msg => '文章列表返回成功',
				result => $articleListArray,
			);
		}
		else{
			//组装返回前端数据
			$returnArticleListArray = array(
				status => 400,
				msg => '文章列表返回失败',
				result => '',
			);			
		}	
		//将数组转为json返回给前端
		$returnArticleListJson = json_encode($returnArticleListArray);
		print($returnArticleListJson);
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
	
	//删除文章
	function delArticle(){
		//获取文章的id
		$articleId = $_GET['article-id'];
		$delArticleSql = "delete from article where aid = '$articleId'";
		$delArticleSql_db = mysql_query($delArticleSql);
		if($delArticleSql_db){
			//组装返回前端数组
			$returnArticleDelArray = array(
				status => 200,
				msg => '文章删除成功',
				result => ''
			);
		}
		else{
			$returnArticleDelArray = array(
				status => 400,
				msg => '文章删除失败',
				result => ''
			);			
		}
		//将数组转换为json返回给前端
		$returnArticleDelJson = json_encode($returnArticleDelArray);
		print_r($returnArticleDelJson);
	}
	
	//单文章静态化
	function oBarticle(){
		$theAid = $_GET['article_id'];
		//数据库查询
		$articleSql = "select a.*,b.*,c.* from article as a left join category as b on a.category_id = b.cid left join  page as c on a.article_cover = c.ptitle where a.aid = '$theAid'";
		$articleSql_db = mysql_query($articleSql);

		$articleArray = array();
		
		while($articleSql_db_array = mysql_fetch_assoc($articleSql_db)){
			$articleArray = $articleSql_db_array;	
		}
		//print_r($articleArray);
		$value = $articleArray;
		/*
		//定义路径
		define('APP_PATH', dirname(dirname(__FILE__)));
		//echo APP_PATH;

		echo "文件名1：".$articleArray[0]['categoryyw']."<br/>";
		echo "文件名2：".$articleArray['categoryyw'];
		//创建文件夹
		if(!file_exists($articleArray[0]['categoryyw'])){
			mkdir($articleArray['categoryyw'],0777,true);
			echo "文件夹传建成功";
		}
		else{
			echo "该文件夹已经存在";
			
		}

		*/
		
		/*引入文章页模板*/
		include("../template/article.php");
		
		//调用传建的文件夹
		$theUtil = new util();
		$theUtil->mkdirWj($articleArray['categoryyw']);

		//获取当前的路径
		define('APP_PATH',dirname(dirname(__FILE__)));
		//echo "当前目录下的文件路径".APP_PATH;

		//文件静态化
		//读取缓存区的内容
		$out1 = ob_get_contents();

		//echo $out1;

		//判断是否存在参数，存在参数就实现页面静态化
		$theOb = $_GET['getOb'];

		if($theOb == "ob")
		{
			//将内容静态化输出
			if(file_put_contents(APP_PATH.'/article/'.$articleArray['categoryyw'].'/show-'.$articleArray['aid'].'.html',$out1)){
				//echo "输出成功";
				//组装返回前端数组
				ob_end_clean();
				
				//当静态化成功后，将文章状态改为已审核
				$updateShenheSql = "update article set article_sh = 1 where aid = '$theAid'";
				$updateShenheSql_db = mysql_query($updateShenheSql);
				if($updateShenheSql_db){
					$judgeShenHe = "审核通过";				
				}
				else{
					$judgeShenHe = "审核失败";
				}
				
				$returnObArticle = array(
					status => 200,
					msg => '静态化返回成功',
					result => '',
					shenhe => $judgeShenHe,
				);
			}
			else{
				//echo "输出失败";
				$returnObArticle = array(
					status => 400,
					msg => '静态化返回失败',
					result => '',
					shenhe => '',
				);
			}
			
			$returnObArticleJson = json_encode($returnObArticle);
			print_r($returnObArticleJson);
		}		
	}
	
	function theObMoreArticle(){
		$theCategory = $_GET['categoryNum'];
		//查询数据库

		$theCategorySql = "select a.*,b.*,c.* from article as a join category as b on a.category_id = b.cid join page as c on a.article_cover = c.ptitle where 1 = 1 and IF('$theCategory' = 0,0 = 0, a.category_id = '$theCategory')";
		$theCategorySql_db = mysql_query($theCategorySql);

		$theCategoryArray = array();

		while($theCategorySql_db_array = mysql_fetch_assoc($theCategorySql_db)){
			$theCategoryArray[] = $theCategorySql_db_array;
			
		}

		//封装返回数组
		$theReturnArray = array(
			"status" => 200,
			"msg" => "返回数组成功",
			"result" => $theCategoryArray
		);

		//将返回的数组转换为json

		$theReturnJson = json_encode($theReturnArray);

		//print_r($theReturnJson);

		//获取当前文章的位置
		define("APP_PATH2",dirname(dirname(dirname(__FILE__))));
		echo "当前路径为".APP_PATH2;
		//检测article对应的category文件夹是否存在,并创建出对应的文件夹
		$theUtilFile = new util();

		//判断是否有静态化请求，如果有，需对文件进行相关的输出
		$theOb = $_GET['getOb'];
		if($theOb == 'obMore'){
			//设置已经静态化的数量
			$n = 0; 
			
			//设置已经审核通过的数量
			$s = 0;
			
			//循环数组，并输出静态文件
			foreach($theCategoryArray as $key => $value){
				//生成文章对于分类的文件夹
				$theUtilFile->mkdirWj($value['categoryyw']);
				
				//引入文章模板
				include('../template/article.php');
				//文章页静态化输出
				//print_r($value);
				$moreOut = ob_get_contents();
				//echo $moreOut;
				if(file_put_contents(APP_PATH2.'/article/'.$value['categoryyw'].'/show-'.$value['aid'].'.html',$moreOut)){
					echo $value['aid']."输出成功";							
					//清除缓冲区,防止内容重复输出;
					$n++;
					$theAid = $value['aid'];
					$updateShenheSql = "update article set article_sh = 1 where aid = '$theAid'";
					$updateShenheSql_db = mysql_query($updateShenheSql);
					if($updateShenheSql_db){
						$s++;				
					}					
					ob_clean();
				}
				else{
					echo $value['aid']."输出失败";
				}
			}

			//组装返回前端数据
			$returnObMoreArray = array(
				status => 200,
				msg => "文章静态化返回成功",
				result => $n,
				shenhe => $s,
			);
			
			//将数组转成json返回前端
			$returnObMoreJson = json_encode($returnObMoreArray);
			print_r($returnObMoreJson);
		}
	
	}
	
	//前端返回的文章列表
	function frontArticleList(){
		//获取当前的分类
		$theCategory = $_GET['category_id'];
		//获取当前页数
		$thePage = $_GET['page_num'];
		//获取查询文章数量
		$theLimit = $_GET['limit_num'];
		
		//将页数和查询文章数转换为数字
		$thePageNum = intval($thePage);
		$theLimitNum = intval($theLimit);
		echo $theCategory;
		
		//返回的文章数据查询
		$theArticleSql = "select a.*,b.* from article as a left join category as b on a.category_id = b.cid where b.categoryyw = '$theCategory' limit $thePageNum,$theLimitNum";
		
		$theArticleSql_db = mysql_query($theArticleSql);
		
		$theArticleArray = array();
		
		while($theArticleSql_db_array = mysql_fetch_assoc($theArticleSql_db)){
			$theArticleArray[] = $theArticleSql_db_array;
		}
		print_r($theArticleArray);	
		//引入列表模板
		include('../template/list.php');
		
		
		//获取当前路径
		//define("APP_PATH2",dirname(dirname(dirname(__FILE__))));
		//echo APP_PATH2;
		
		//静态化
		//$theOb = $_GET['getOb'];
		//if($theOb == 'ob'){
			
		//}
	}
	
	//前端查询文章列表数组返回（用于拖动异步加载）
	function ajaxLoadArticle(){
		$thePage = $_GET['thePage'];
		$theLimitNum = $_GET['theLimitNum'];
		//$theAuthor = $_GET['theAuthor'];
		//$theCover = $_GET['theCover'];
		$thePageType = $_GET['thePageType'];  //获取传递过来的页面类型 
		$thePageValue = $_GET['thePageValue']; //获取传递过来的页面值
		
		$pageStart = ($thePage-1)*$theLimitNum;
		
		//echo $thePageType;
		
		if($thePageType == 'user'){
			$articleListSql = "select * from article where 1=1 and if('$thePageValue' = '', 0 = 0, article_author = '$thePageValue') order by aid DESC limit $pageStart,$theLimitNum";
		}
		
		else if($thePageType == 'cover'){
			$articleListSql = "select a.*,b.* from article as a join page as b on a.article_cover = b.title where 1 = 1 and if('$thePageValue' = '', 0 = 0 , b.pid = '$thePageValue') order by aid DESC limit $pageStart,$theLimitNum";		
		}
		else{
			$articleListSql = "select * from article where 1=1 order by aid limit $pageStart,$theLimitNum";
			
		}
		
	
		$aritcleListSql_db = mysql_query($articleListSql);
		$aritcleListArray = array();
		while($aritcleListSql_db_array = mysql_fetch_assoc($aritcleListSql_db)){
			$aritcleListArray[] = $aritcleListSql_db_array;
		}
		//引入公共模块，将数据转为json返回给前端
		$theUtil = new util();
		$aritcleListJson = $theUtil->ajaxJson('200','文章返回成功',$aritcleListArray);
		print_r($aritcleListJson);
	}
	
	//前端文章列表静态化
	function frontArticleListOb(){
		//获取分类
		$theCategoryObId = $_GET['getCategoryId'];
		//获取展示的文章数量
		$theLimitOb = $_GET['getLimit'];
		
		//页码类型分类
		$typePage = "list";
		
		$categoryArticleListSql = "select a.*,b.* from article as a left join category as b on a.category_id = b.cid where 1 = 1 and IF('$theCategoryObId' = 0, 0 = 0, category_id = '$theCategoryObId')";
		
		$categoryArticleListSql_db = mysql_query($categoryArticleListSql);
		
		$categoryArticleListSql_db_num = mysql_num_rows($categoryArticleListSql_db);
		//echo $categoryArticleListSql_db_num;
		//页数（如果存在余数，页数就加1）
		$pageNum = $categoryArticleListSql_db_num/$theLimitOb;
		//取pageNum的整数
		$pageNumZ =  floor($pageNum); 
		//echo '$pageNum'.$pageNum;
		//echo '$pageNumZ'.$pageNumZ;
		//检测是否存在余数
		$pageNumYs = $categoryArticleListSql_db_num%$theLimitOb;		
		//echo '$pageNumYs'.$pageNumYs;
		if($pageNumYs>0){
			$pageNumZ = $pageNumZ+1;
			
		}
		//定义当前位置
		define("APP_PATH3",dirname(dirname(dirname(__FILE__))));
		//echo APP_PATH3;
		

		//列表页静态化
		$theOb = $_GET['getOb'];
		if($theOb == 'ob'){
			//循环查询列表,并静态化输出列表页面
			for($i=0;$i<$pageNumZ;$i++){
				//起始位置为页面乘以每页面的数量
				
				$qsNum = $i * $theLimitOb;
				$listSql = "select a.*,b.*,c.* from article as a left join category as b on a.category_id = b.cid left join page as c on a.article_cover = c.ptitle where 1 = 1 and IF('$theCategoryObId' = 0, 0 = 0, category_id = '$theCategoryObId') limit $qsNum,$theLimitOb";		
				$listSql_db = mysql_query($listSql);
				$theArticleArray = array();
				while($listSql_db_array = mysql_fetch_assoc($listSql_db)){
					$theArticleArray[] = $listSql_db_array;
				}
				//print_r($theArticleArray);
				
				//传建对应的文件夹	
				$theUtil = new util();
				$theUtil->mkdirWj($theArticleArray[0]['categoryyw']);	
				
				//将得到的文章列表值赋给模板中的值
				$categoryNumArray = $theArticleArray;
				$theCategoryId = $theCategoryObId;
				
				//因为页面一般都是从第一页开始的，所以生成的页面要加一
				$w = $i + 1;				
				
				//引入列表模板				
				//include('../template/list.php');
				 include('../template/category-list-template.php');
				 //获取缓存信息
				$info = ob_get_contents();
				 //echo $info;
				//header('content-type:text/html;charset=utf-8');
				 //将缓存信息输出
				 
				 //检测源码类型，解决乱码问题
				 //$encode = mb_detect_encoding($info, array("ASCII",'UTF-8',"GB2312","GBK",'BIG5'));
				 //echo $encode;			 
				file_put_contents(APP_PATH3.'/article/'.$theArticleArray[0]['categoryyw'].'/'.$theArticleArray[0]['categoryyw'].'-'.$w.'.html', $info);
				 
				//输出完后，将缓存清除
				ob_clean();
				 $this->s = $w;
				//echo "<br/><hr/>";					
			}
			
			//返回给前端的信息
			$returnArray = array(
				"status" => 200,
				"msg" => '列表页静态化成功',
				"result" => $this->s,
			);
			//将数组转换为json
			$returnJson = json_encode($returnArray);
			print_r($returnJson);
		}
		
		
		$thePageNumOb = $_GET['getPageNum'];
		
	}
	
	//前端栏目汇总列表静态化
	function fatherCategoryArrayPageOb(){
		//获取所有的父类id
		$theUtil = new articleUtil();
		$theFatherCategoryArray = $theUtil->getCategoryArray(0);
		$r = 0; //静态化父类成功的结果
		//遍历数组获取获取各父类的id
		foreach($theFatherCategoryArray as $key =>$value){
			//调用有分页的列表
			//$this->categoryArrayPageOb($value['cid']);
			//调用展示各个分类信息的列表
			if($this->categoryArrayCollectOb($value['cid'])){
				$r++;
			}
		}
		if($r>0){
			//组装返回前端的数组
			$categoryObArray = array(
				status => 200,
				msg => '父分类列表静态化成功',
				result => $r
			);
		}
		else{
			//组装返回前端的数组
			$categoryObArray = array(
				status => 400,
				msg => '父分类列表静态化失败',
				result => ''
			);						
		}
		//将array转为json返回给前端
		$categoryObJson = json_encode($categoryObArray);
		print_r($categoryObJson);
	}
	
	//总分类页静态化（这个为有分页列表）
	function categoryArrayPageOb($fid){
		if(!$fid){
			//获取需要静态化的分类组合
			$theCategoryId = $_GET['theCategoryId'];			
		}
		else{
			$theCategoryId = $fid;
		}
		
		echo $theCategoryId;
		//页码类型分类
		$typePage = "categoryList";
		
		//echo $theCategoryId;
		//根据得到的分类id获取对应的子集
		$theArticleUtil = new articleUtil();
		//获取每页的文章数量
		$pageNum = $_GET['pageNum'];
		
		
		$theCategoryChildId = $theArticleUtil->findCategoryChilrenArray($theCategoryId,'article');
		print_r($theCategoryChildId);
		if($theCategoryChildId){
			//将数组转成字符串
			$theCategoryChildIdString = implode(',',$theCategoryChildId);
			
			//设置查询
			$findAllAritcleArraySql = "select a.*,b.* from article as a join category as b on a.category_id = b.cid where a.category_id in ($theCategoryChildIdString) order by a.aid DESC";
			$findAllAritcleArraySql_db = mysql_query($findAllAritcleArraySql);
			
			//获取文章数量
			$findAllAritcleNum = mysql_num_rows($findAllAritcleArraySql_db);
			echo "数量：".$findAllAritcleNum;
			
			$findAllAritcleArray = array();
			while($findAllAritcleArraySql_db_array = mysql_fetch_assoc($findAllAritcleArraySql_db)){
				$findAllAritcleArray[] = $findAllAritcleArraySql_db_array;
			}
			
			//分页(需要取整)
			$getPageNum = floor($findAllAritcleNum / $pageNum);
			//取余，如果有余数时页数需要加一
			$ysPageNum = $findAllAritcleNum % $pageNum;
			
			echo "页数：".$getPageNum;
			echo "余数：".$ysPageNum;
			
			if($ysPageNum>0){
				$getPageNum = $getPageNum + 1;			
			}
			
			//循环获取对应的数组
			for($i = 0; $i<$getPageNum; $i++){
				//新建一个数组
				$categoryNumArray = array();
				//循环获取总数组中对应部分的数组
				for($j = $i*$pageNum; $j<$i*$pageNum+$pageNum; $j++){
					$categoryNumArray[] = $findAllAritcleArray[$j];				
				}						
				//引入模板
				//因为页数是从第1页开始的，所以需要$i+1;
				$p = $i + 1;
				include("../template/category-list-template.php");				
				
				//静态化选择
				$theOb = $_GET['theOb'];
				if($theOb == 'Ob'){
					//设置存储路径
					$rootPath = $_SERVER['DOCUMENT_ROOT'];
					echo "路径：".$rootPath;				
					$thePath = $rootPath . "/article/";
					//传建文件夹
					$theUtil = new util();
					$theUtil->createFile($thePath,$categoryInfoArray['categoryyw']);
					
					//使用file_put_contents传建存储文件，ob_get_contents获取缓存内容
					
					//存储的文件名称
					$savePath = $thePath . $categoryInfoArray['categoryyw'] . '/' . $categoryInfoArray['categoryyw'] .'-list-' .$p. '.html';
					
					file_put_contents($savePath,ob_get_contents());
					
					//清除缓存区，以免造成缓存重复
					ob_clean();  
					
				}
				
				//unset($categoryNumArray);
				

			}	
			//print_r($findAllAritcleArray);
			
		}			
	}
	
	//总分类页静态化（这个为有分页列表）
	function categoryArrayCollectOb($fid){
		if(!$fid){
			//获取需要静态化的分类组合
			$theCategoryId = $_GET['theCategoryId'];				
		}
		else{
			$theCategoryId = $fid;
			
			//查询该父类分类信息
			$theCategoryFatherSql = "select * from category where cid = $theCategoryId";
			$theCategoryFatherSql_db = mysql_query($theCategoryFatherSql);
			$theCategoryFatherArray = array();
			while($theCategoryFatherSql_db_array = mysql_fetch_assoc($theCategoryFatherSql_db)){
				$theCategoryFatherArray = $theCategoryFatherSql_db_array;
			}			
			
			//引入模板
			include("../template/category-collect-template.php");
			
			//选择是否静态化
			$theOb = $_GET['theOb'];
			if($theOb == 'Ob'){
				//设置存储路径
				$rootPath = $_SERVER['DOCUMENT_ROOT'];
				echo "路径：".$rootPath;				
				$thePath = $rootPath . "/article/";
				//传建文件夹
				$theUtil = new util();
				$theUtil->createFile($thePath,$theCategoryFatherArray['categoryyw']);
				
				//使用file_put_contents传建存储文件，ob_get_contents获取缓存内容
				
				//存储的文件名称
				$savePath = $thePath . $theCategoryFatherArray['categoryyw'] . '/' . $theCategoryFatherArray['categoryyw'] .'-list.html';
				
				if(file_put_contents($savePath,ob_get_contents())){
					ob_clean();
					return true;
				}
				else{
					ob_clean();
					return false;				
				}
				 
			}		
		}
	}
	
	
	//点击文章阅读时，浏览量加一
	function articleViwe(){
		$theUtil = new util();
		$theIp = $theUtil->getUserIp();
		echo $theIp;	
		//获取对应的文章id				
		$theArticleId = $_GET['articleId'];
		
		echo "文章id".$_SESSION['theArticleId'];
		echo "id".$_SESSION['theIp'];
		//检测避免刷浏览器，因而检测$_SESSION['theIp']是否存在
		if(!$_SESSION['theIp'] || $_SESSION['theIp'] != $theIp && !$_SESSION['theArticleId'] || $_SESSION['theArticleId'] != $theArticleId){
		
			//将该ip存入session中
			$_SESSION['theIp'] = $theIp;
			$_SESSION['theArticleId'] = $theArticleId;
			
			//浏览量加一
			$articleViweAddSql = "update article set article_view = article_view+1 where aid = $theArticleId";
			$articleViweAddSql_db = mysql_query($articleViweAddSql);
			
			//执行返回选择
			if($articleViweAddSql_db){
				//组装返回数组
				$returnArticleViewArray = array(
					status => 200,
					msg => '浏览量增加成功',
					result => '',
				);
				
			}
			else{
				$returnArticleViewArray = array(
					status => 400,
					msg => '浏览量增加失败',
					result => '',
				);				
			}
			
		}
		else{
			$returnArticleViewArray = array(
				status => 500,
				msg => '该ip浏览过了，所以就不增加浏览量了',
				result => '',
			);		
			
		}
			//将数组转换为json返回给前端
			$returnArticleViewJson = json_encode($returnArticleViewArray);
			print_r($returnArticleViewJson);
		
	}
	
	//外部请求随机文章
	function webGetRandArticleList(){
		$theNum = $_GET['theNum'];
		//echo $theNum;
		$theCategory = $_GET['theCategory'];
		$articleUtil = new articleUtil();
		$randArticleList = $articleUtil->getCategoryArticle($theCategory,$num = $theNum,$isRand="rand");
		//组装返回数组
		$returnArticleRandArray = array(
			status => 200,
			msg => '随机文章返回成功',
			result => $randArticleList,
		);	
		//将数组转换为json返回给前端
		$returnArticleRandJson = json_encode($returnArticleRandArray);
		print($returnArticleRandJson);
	}
	
	//搜索查询(首次加载)
	function findArticleList(){
		$theKeyword = $_GET['theKeyword'];
		$theNum = $_GET['theNum'];
		//echo $theKeyword;
		
		//查找相关封面
		$findThePageArraySql = "select * from page where ptitle like '%$theKeyword%' limit 0,$theNum";	
		$findThePageArraySql_db = mysql_query($findThePageArraySql);
		$findThePageArray = array();
		while($findThePageArraySql_db_array = mysql_fetch_assoc($findThePageArraySql_db)){
			$findThePageArray[] = $findThePageArraySql_db_array;
		}
		
		//查找出页面总数量
		$findThePageNumSql = "select * from page where ptitle like '%$theKeyword%'";
		$findThePageNumSql_db = mysql_query($findThePageNumSql);
		$findThePageArrayNum = mysql_num_rows($findThePageNumSql_db);
		
		//查找相关文章
		$findTheArticleArraySql = "select * from article where title like '%$theKeyword%' limit 0,$theNum";
		$findTheArticleArraySql_db = mysql_query($findTheArticleArraySql);
		$findTheArticleArray = array();
		while($findTheArticleArraySql_db_array = mysql_fetch_assoc($findTheArticleArraySql_db)){
			$findTheArticleArray[] = $findTheArticleArraySql_db_array;
		}
		
		//查找相关文章的总数量
		$findTheArticleNumSql = "select * from article where title like '%$theKeyword%'";
		$findTheArticleNumSql_db = mysql_query($findTheArticleNumSql);
		$findTheArticleArrayNum = mysql_num_rows($findTheArticleNumSql_db);
		
		//组装返回前端数组
		$returnFindArticleArray = array(
			status => 200,
			msg => '查找信息返回',
			pageNum => $findThePageArrayNum,
			pageResult => $findThePageArray,
			articleNum => $findTheArticleArrayNum,
			articleResult => $findTheArticleArray,
		);				
		
		//将数组转为json返给前端
		$returnFindArticleJson = json_encode($returnFindArticleArray);
		print $returnFindArticleJson;
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
		if($turl == 'oBarticle'){
			$this->oBarticle();
		}
		if($turl == 'theObMoreArticle'){
			$this-> theObMoreArticle();	
		}	
		if($turl == 'frontArticleList'){
			$this->frontArticleList();
		}
		if($turl == 'frontArticleListOb'){
			$this->frontArticleListOb();
		}
		if($turl == 'getBaseImgSave'){
			$this->getBaseImgSave();
		}
		if($turl == 'ajaxLoadArticle'){
			$this->ajaxLoadArticle();
		}
		if($turl == 'categoryArrayPageOb'){
			$this->categoryArrayPageOb();
		}
		if($turl == 'fatherCategoryArrayPageOb'){
			$this->fatherCategoryArrayPageOb();
		}
		if($turl == 'articleViwe'){
			$this->articleViwe();
		}
		if($turl == 'getArticleInfo'){
			$this->getArticleInfo();
		}
		if($turl == 'webGetRandArticleList'){
			$this->webGetRandArticleList();
		}
		if($turl =='findArticleList'){
			$this->findArticleList();
		}
		if($turl =='delArticle'){
			$this->delArticle();
		}
		if($turl == 'userArticleList'){
			$this->userArticleList();
		}
	}
}