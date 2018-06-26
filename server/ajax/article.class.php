<?php 
ob_start();
include('../system.util.php');
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
		
		//需要对传递过来的链接进行转码
		$videoSourceT = urlencode($videoSource);
		
		
		//$theCommit = $_POST[''];
		
		$addSql = "insert into article (title, short_title, category_id, article_key, article_short, article_cover, article_author, article_source, commit_start, commit_end, article_img, video_platform, video_source, article_container, article_status, commit_status) values ('$theTitle', '$articleShortTitle', '$categorySelect', '$articleKeyword', '$articleShort', '$articleCover', '$articleAuthor', '$articleSource', '$plDataStart', '$plDataEnd', '$articlePic', '$videoPlatform', '$videoSource', '$theArticle', '$articleStatus', '$articlePl')";
		
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
				print_r($baseArray);
			}
		}
		
	}
	
	//后端展示文章列表
	function articleList(){
		//获取文章是否查询的文章状态，是查询草稿还是查询公开的，或者是查询所有
		$theStatus = $_GET['status']; 
		$theAuthor = $_GET['author'];
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
	
	//单文章静态化
	function oBarticle(){
		$theAid = $_GET['article_id'];
		//数据库查询
		$articleSql = "select a.*,b.* from article as a left join category as b on a.category_id = b.cid where a.aid = '$theAid'";
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
		echo "当前目录下的文件路径".APP_PATH;

		//文件静态化
		//读取缓存区的内容
		$out1 = ob_get_clean();

		echo $out1;

		//判断是否存在参数，存在参数就实现页面静态化
		$theOb = $_GET['getOb'];

		if($theOb == "ob")
		{
			//将内容静态化输出
			if(file_put_contents(APP_PATH.'/article/'.$articleArray['categoryyw'].'/'.$articleArray['aid'].'.html',$out1)){
				echo "输出成功";
			}
			else{
				echo "输出失败";
			}
		}		
	}
	
	function theObMoreArticle(){
		$theCategory = $_GET['categoryNum'];
		//查询数据库

		$theCategorySql = "select a.*,b.* from article as a left join category as b on a.category_id = b.cid where 1 = 1 and IF('$theCategory' = 0,0 = 0, a.category_id = '$theCategory')";
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

		print_r($theReturnJson);

		//获取当前文章的位置
		define("APP_PATH2",dirname(dirname(dirname(__FILE__))));
		echo "当前路径为".APP_PATH2;
		//检测article对应的category文件夹是否存在,并创建出对应的文件夹
		$theUtilFile = new util();

		//判断是否有静态化请求，如果有，需对文件进行相关的输出
		$theOb = $_GET['getOb'];
		if($theOb == 'obMore'){
			//循环数组，并输出静态文件
			foreach($theCategoryArray as $key => $value){
				//生成文章对于分类的文件夹
				$theUtilFile->mkdirWj($value['categoryyw']);
				
				//引入文章模板
				include('../template/article.php');
				//文章页静态化输出
				print_r($value);
				$moreOut = ob_get_contents();
				//echo $moreOut;
				if(file_put_contents(APP_PATH2.'/article/'.$value['categoryyw'].'/'.$value['aid'].'.html',$moreOut)){
					echo $value['aid']."输出成功";		
					
					//清除缓冲区,防止内容重复输出;
					ob_clean();
				}
				else{
					echo $value['aid']."输出失败";
				}
			}		
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
		$theAuthor = $_GET['theAuthor'];
		$theCover = $_GET['theCover'];
		
		$pageStart = ($thePage-1)*$theLimitNum;
		
		
		if($theAuthor){
			$aritcleListSql = "select * from article where 1=1 and if('$theAuthor' = '', 0 = 0, article_author = '$theAuthor') order by aid DESC limit $pageStart,$theLimitNum";
		}
		
		if($theCover){
			$articleListSql = "select * from article where 1 = 1 and if('$theCover' = '', 0 = 0 , article_cover = '$theCover') order by aid DESC limit $pageStart,$theLimitNum";		
		}
		
		$aritcleListSql_db = mysql_query($aritcleListSql);
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
				$listSql = "select a.*,b.* from article as a left join category as b on a.category_id = b.cid where 1 = 1 and IF('$theCategoryObId' = 0, 0 = 0, category_id = '$theCategoryObId') limit $qsNum,$theLimitOb";		
				$listSql_db = mysql_query($listSql);
				$theArticleArray = array();
				while($listSql_db_array = mysql_fetch_assoc($listSql_db)){
					$theArticleArray[] = $listSql_db_array;
				}
				//print_r($theArticleArray);
				
				//传建对应的文件夹	
				$theUtil = new util();
				$theUtil->mkdirWj($theArticleArray[0]['categoryyw']);	
				//引入列表模板
				include('../template/list.php');
				 
				 //获取缓存信息
				$info = ob_get_contents();
				 //echo $info;
				//header('content-type:text/html;charset=utf-8');
				 //将缓存信息输出
				 
				 //检测源码类型，解决乱码问题
				 //$encode = mb_detect_encoding($info, array("ASCII",'UTF-8',"GB2312","GBK",'BIG5'));
				 //echo $encode;
				file_put_contents(APP_PATH3.'/article/'.$theArticleArray[0]['categoryyw'].'/'.$theArticleArray[0]['categoryyw'].'-'.$i.'.html', $info);
				 
				//输出完后，将缓存清除
				ob_clean();
				 $this->s = $i;
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
	}
}