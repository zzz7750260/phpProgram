<?php
header("Content-Type:text/html; charset=utf-8"); //
include("../system.util.php");
class spiderUtil{
	//采集文章元素
	private $s_title;
	private $s_short_title;
	private $s_contaier;
	private $s_category;
	
	//采集视频元素
	private $sv_title;
	private $sv_img;
	private $sv_link;
	private $sv_article;
	private $sv_img_name;
	
	function curl_http($turl){
		set_time_limit(0);
		$curl = curl_init();
		curl_setopt($curl,CURLOPT_URL,$turl);
		curl_setopt($curl,CURLOPT_HEADER,1);
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,FALSE);
		curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,FALSE);
		$output = curl_exec($curl);
		if($output === False){
			echo "error";
		}	
		curl_close($curl);
		
		//将编码统一转为utf-8
		$result = mb_convert_encoding($output,'UTF-8',array('ASCII','UTF-8','GB2312','GBK'));
		return $result;
	}
	
	//设置栏目采集
	function setCategory(){
		$turl = "https://www.douguo.com/u/u48350980597650/recipe/";
		$categoryHtml = $this->curl_http($turl); 
		//var_dump($categoryHtml);
		//制定正则规则
		$reg = '/<h3><a href="(.*)" target="_blank">\s+(.*)\s+<\/a>\s+<\/h3>/isU';
		preg_match_all($reg,$categoryHtml,$result);
		//var_dump($result);
		$theResultitle = $result[2];
		//var_dump($theResultitle);
		
		//将题目进行处理
		foreach($theResultitle as $catKey => $catValue){
			//echo (string)$catValue;
			//$catValueTitle = preg_replace("/\{u2014}/u",'a',$catValue);
			//$catValueTitleArray[] = $catValueTitle;
			//除去字符串空格
			$catValueTitle = preg_replace('# #','',$catValue);
			//$catValueTitle = trim($catValue);
			//echo substr($catValueTitle,0,-13);
			//对名称进行处理
			$catValueTitles = preg_replace('/\x{2014}[\x{4e00}-\x{9fa5}]+/u','',$catValueTitle);
			
			$catValueTitleArray[] = $catValueTitles;
			
		}
		//var_dump($catValueTitleArray);
		
		//遍历链接数组,获取信息
		foreach($result[1] as $urlKey => $urlValue){
			//echo $urlValue;
			//对对应的链接发出请求
			$this->setArticle($urlValue,$catValueTitleArray[$urlKey]);
			//echo $catValueTitleArray[$urlKey];
			//if($returnArticleValue['returnValue'] == 'true'){
				
			//}
		}		
	}
	
	//设置采集文章
	//$turl: 设置的采集链接
	//$title:设置的采集标题
	function setArticle($turl,$title){
		if(!$turl){
			$turl = "https://www.douguo.com/cookbook/1709547.html";	
		}
		if(!$title){
			$title = "响油白菜";
		}
		
		
		$articleHtml = $this->curl_http($turl); 
		//匹配描述
		$msRge = '/<div class="xtip" >\s*(.*)\s*<\/div>/isU';
		preg_match($msRge,$articleHtml,$msResult);
		//var_dump($msResult);
		//匹配材料
		$clRge = '/<td .*>\s*(.*)<\/td>/isU';
		preg_match_all($clRge,$articleHtml,$clResult);
		//var_dump($clResult);
		//匹配内容
		$nrRge = '/<p>(<span class=".*">.*<\/span>.*)<\/p>/isU';
		preg_match_all($nrRge,$articleHtml,$nrResult);
		//var_dump($nrResult);
		
		//将数组以空格分开
		$clResultContainer = implode('',$clResult[1]);//材料
		
		//将材料中的链接去除
		$clResultContainerTr = preg_replace("/https:\/\/[\w\.\/\x{4e00}-\x{9fa5}]+/u",'#',$clResultContainer);
		
		//printf($clResultContainerTr);
		
		//组装描述
		$msResultCantainer = '<p>'.$msResult[1].'</p>';
		
		$nrResultContainer = implode('',$nrResult[0]);//内容
		
		//设置整篇文章的内容
		$articleArrayContainer = array(
			ms => $msResultCantainer,
			cl => $clResultContainerTr,
			container => $nrResultContainer,
		);
		
		//将文章内容转换为字符串
		$spiderArticleContainer = implode("",$articleArrayContainer);
		//var_dump($articleArrayContainer);
		
		//设置短标题为
		$short_title = $title."做法步骤";	
		
		//设置分类
		$theCategory = 5;

		//赋值给全局
		$this->s_title = $title;
		$this->s_short_title = $short_title;
		$this->s_contaier = $spiderArticleContainer;
		$this->s_category = $theCategory;
		
		//将数据插入数据库,并获取其插入数据库后的状态和成功后得到的文章id
		$returnArticleValue = $this->insertSpiderArticle();		
		var_dump($returnArticleValue);
		
		
		
		//当插入文章状态为成功时，通过爬虫查找相关的视频d	
		if($returnArticleValue['returnValue'] == 'true'){
			$this->setVideoCategory($title,$returnArticleValue['videoArticle']);
		}
	}
	
	//二次查找视频列表
	//$title: 传入的名称
	//$articleId：对应文章的id
	function setVideoCategory($title,$articleId){
		if(!$title){
			$title = '响油白菜';
		}
		$theArticleId = $articleId;
		
		//链接不能输入中文，需要将中文转为Encode码
		$en_title = urlencode($title);
		
		$turl = "https://so.youku.com/search_video/q_$en_title";
		$searchVideoHtml = $this->curl_http($turl);
		//var_dump($searchVideoHtml);
		//正则查询对应的链接
		//$theVideoUrlReg = '/\/\/([a-zA-Z]+\.[a-zA-Z]+\.[a-zA-Z]+\/\w+\/\w+=+\.\w+)/is';
		//preg_match_all($theVideoUrlReg,$searchVideoHtml,$videoResult);
		//var_dump($videoResult[1]);
		
		//获取视频图片
		//$theVideoReg = '/class=\\"lazyImg\\"\s+src=\\"(\/\/\w+\.\w+\.\w+\/\w+)/isU';
		//$theVideoReg = '/<div class=\\"pack-cover\\">[a-zA-Z\\]+<img alt=\\"(\/\/\w+\.\w+\.\w+\/\w+\\)/isU';
		//$theVideoReg = '/<div class=\\\"pack-cover\\\">[a-zA-Z\\\]+<img alt=\\\"\/\/(\w+\.\w+\.\w+\/[0-9a-zA-Z]+)/is';
		//preg_match_all($theVideoReg,$searchVideoHtml,$videoResultArray);
		//var_dump($videoResultArray[1]);
		
		//获取图片和链接
		//$getVideoLinkReg = '/<div class=\\\"mod-left\\\">[a-zA-Z\\\]+<a class=\\\"sk-pack\\\"\s+data-spm=\\\"dposter\\\"\s+ target=\\\"_blank\\\"\s+href=\\\"\/\/([a-zA-Z]+\.[a-zA-Z]+\.[a-zA-Z]+\/\w+\/\w+=+\.\w+)\\\">[a-zA-Z\\\]+<div class=\\\"pack-cover\\\">[a-zA-Z\\\]+<img\s+class=\\\"lazyImg\\\"\s+src=\\\"(\/\/\w+\.\w+\.\w+\/\w+\\)\"/isU';
		
		$getVideoLinkReg = '/<div class=\\\"mod-left\\\">[a-zA-Z\\\]+<a class=\\\"sk-pack\\\"\s+data-spm=\\\"dposter\\\"\s+ target=\\\"_blank\\\"\s+href=\\\"\/\/([a-zA-Z]+\.[a-zA-Z]+\.[a-zA-Z]+\/\w+\/\w+=+\.\w+)\\\">[a-zA-Z\\\]+<div class=\\\"pack-cover\\\">[a-zA-Z\\\]+<img\s+class=\\\"lazyImg\\\"\s+src=\\\"\/\/(\w+\.\w+\.\w+\/\w+)\\\"\s+\/>[a-zA-Z\\\]+<\/div>/isU';
		
		preg_match_all($getVideoLinkReg,$searchVideoHtml,$getVideoLinkArray);
		//var_dump($getVideoLinkArray);
		
		//检测查到链接的长度，获取前面四条链接的信息
		//echo "长度：".$videoUrlNum = count($getVideoLinkArray[1]);
		
		//var_dump($getVideoLinkArray[1]);
		//foreach($getVideoLinkArray[1] as $a => $b){
		//	echo $b."=======";
		//}
		
		$videoArray = $getVideoLinkArray[1];
		$imgArray = $getVideoLinkArray[2];
		
		//foreach($getVideoLinkArray[2] as $c => $d){
			//echo $d."=======";
		//}
		
		if($videoUrlNum>4){
			$n = 3;
			for($i=0;$i<$n;$i++){
				//echo $videoResult[1][$i];
				//echo $videoResultArray[1][$i];
				$this->setVideoArticle($videoArray[$i],$imgArray[$i],$theArticleId);
			}		
		}
		else{
			foreach($videoArray as $videoLinkKey => $videoLinkValue){
				echo $imgArray[$videoLinkKey];
				$this->setVideoArticle($videoLinkValue,$imgArray[$videoLinkKey],$theArticleId);
			}
		}		
	}
	
	//查找视频内容页
	function setVideoArticle($turl,$imgUrl,$articleId){
		if(!$imgUrl){
			$imgUrl = '//vthumb.ykimg.com/054201015A530D418B7B449D260EED65';
		}
		if(!$turl){
			$turl = "v.youku.com/v_show/id_XMzIwOTM5MTU0MA==.html";
		}
		
		$videoHtml = $this->curl_http($turl);
		//var_dump($videoHtml);
		//获取当中的分享链接
		$shareReg = "/<iframe height=498 width=510 src=\'http:\/\/(.*)\' frameborder=0 \'allowfullscreen\'><\/iframe>/isU";
		preg_match_all($shareReg,$videoHtml,$videoResult);
		//var_dump($videoResult);		

		//获取当中的title
		$titleReg = '/<h1>\s+<span id="subtitle" title="(.*)">.*<\/span>\s+<\/h1>/isU';
		preg_match($titleReg,$videoHtml,$titleResult);
		//var_dump($titleResult);
					
		//生成图片的名称
		$theUtil = new util();
		$imgN = $theUtil->setSessionToken(3);		
		$theImgName = $imgN . '.jpg';
		
		//将所有的变量设为全局
		$this->sv_title = $titleResult[1];
		$this->sv_img = $imgUrl;
		$this->sv_link = $videoResult[1][0];
		$this->sv_article = $articleId;
		$this->sv_img_name = $theImgName;
		//$this->video_article_id = $articleId;
		
		
		//将视频信息插入数据库
		$theVideoResult = $this->insertSpiderVideo();
		
		//当视频信息插入成功后，再将图片储存
		if($theVideoResult == 'true'){
			//组装请求的图片地址
			$pImgUrl = $theUtil->isHttpsCheckSelect() .'//'. $imgUrl;
			
			$this->getPicture($pImgUrl,$theImgName);
		}
				
	}
	
	//将文章存入数据库
	function insertSpiderArticle(){
		//获取当前时间
		//echo "插入数据库";
		$theTime = date("Y-m-d h:i:s");
		$article_title = $this->s_title;
		$article_short_title = $this->s_short_title;
		$article_contaier = $this->getHtmlspecialchars($this->s_contaier);
		$article_category = $this->s_category;
		
		//设置与video连接的id
		$theUtil = new util();
		$theArticleVideoId = $theUtil->setSessionToken(4);	
		echo "文章视频的id：".$theArticleVideoId;
				
		echo $article_contaier;
		//$stitle = 
		//echo $article_contaier;
		$insertArticleSql = "insert into spider_article (s_title,s_short_title,s_contaier,s_category,s_time,s_article_id) values('$article_title','$article_short_title','$article_contaier', $article_category,'$theTime','$theArticleVideoId')";
		$insertArticleSql_db = mysql_query($insertArticleSql);
		if($insertArticleSql_db){
			echo "插入成功";
			$returnValue = 'true';
			
			//查询插入该文章的id
			$selectArticle = "select * from spider_article where s_title = '$article_title'";
			$selectArticle_db = mysql_query($selectArticle);
			$selectArticleArray = array();
			while($selectArticle_db_array = mysql_fetch_assoc($selectArticle_db)){
				$selectArticleArray = $selectArticle_db_array;
			}
			
			//组装返回值
			$returnArray = array(
				returnValue => $returnValue,
				returnId => $selectArticleArray['sid'],
				videoArticle => $theArticleVideoId,
			);
		}
		else{
			echo "插入失败";
			$returnValue = 'false';
			//组装返回值
			$returnArray = array(
				returnValue => $returnValue,
				returnId => $selectArticleArray['sid'],
				videoArticle => $theArticleVideoId,
			);
		};
		return $returnArray;
	}
	
	//将视频存入数据库
	function insertSpiderVideo(){
		$video_title = $this->sv_title;
		$video_img = $this->sv_img;
		$video_link = $this->sv_link;	
		$video_article = $this->sv_article;
		$vide_img_name = $this->sv_img_name;
		
		
		echo "插入视频数据库";
		echo $video_img;
		
		$insertVideoSql = "insert into spider_video (sv_article, sv_title, sv_img, sv_img_name, sv_link) values ('$video_article','$video_title','$video_img','$vide_img_name','$video_link')";
		$insertVideoSql_db = mysql_query($insertVideoSql);
		if($insertVideoSql_db){
			echo "视频插入成功";
			$returnVideoValue = "true";
		}
		else{
			echo "视频插入失败";
			$returnVideoValue = "false";
		}
		return $returnVideoValue;
	}
	
	//采集图片
	function getPicture($picUrl,$name){
		if(!$picUrl){
			$picUrl = 'http://vthumb.ykimg.com/054204085A0B13BF00000163BF0B980C';	
		}
		if(!$name){
			$name = 'aaa.jpg';
		}
			
		//获取图片信息
		$imgContent = file_get_contents($picUrl);
		//echo $imgContent;
		//设置存储路径
		//$theUtil = new util();
		$thePart = '/img/spider_img/'.$name;
		
		$theUtil = new util();
		$imgPart = $theUtil->physicalPath($thePart);
		
		//采集图片
		if(file_put_contents($imgPart,$imgContent)){
			echo "图片". $name ."上传成功";
		}
	}
	
	//在图片网站中查找对应的图片
	function findImg(){
		$turl = "http://www.58pic.com/tupian/meishi.html";
		$imgHtml = $this->curl_http($turl);
		var_dump($imgHtml);
	}
	
	
	//设置转义
	function getHtmlspecialchars($str){	
		//echo $tstr;
		$tStr = htmlspecialchars($str);
		//echo $tStr;
		$ttStr = addslashes($tStr);
		echo $ttStr;
		return $ttStr;	
	}
		
	function returnResult($turl){
		if($turl == "setCategory"){
			$this->setCategory();
		}
		if($turl == "setArticle"){
			$this->setArticle('','');
		}
		if($turl == "setVideoCategory"){
			$this->setVideoCategory('','');
		}		
		if($turl == "setVideoArticle"){
			$this->setVideoArticle('','','');
		}
		if($turl == "getPicture"){
			$this->getPicture('','');		
		}
		if($turl == "findImg"){
			$this->findImg();
		}
	}	
}	 
?>