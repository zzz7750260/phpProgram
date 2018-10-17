<?php
header("Content-Type:text/html; charset=utf-8"); //
class spiderUtil{
	function curl_http($turl){
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
		$reg = '/<h3><a href="(.*)" target="_blank">\s*(.*)\s*<\/h3>/isU';
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
		var_dump($catValueTitleArray);
	}
	
	//设置采集文章
	function setArticle($turl){
		$turl = "https://www.douguo.com/cookbook/1709547.html";	
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
		
		printf($clResultContainerTr);
		
		$nrResultContainer = implode('',$nrResult[0]);//内容
		
		//设置整篇文章的内容
		$articleArrayContainer = array(
			ms => $msResult[1],
			cl => $clResultContainerTr,
			container => $nrResultContainer,
		);
		
		//var_dump($articleArrayContainer);
	}
	
	//二次查找视频列表
	function setVideoCategory(){
		$turl = "https://so.youku.com/search_video/q_%E5%AD%9C%E7%84%B6%E7%89%9B%E8%82%89";
		$searchVideoHtml = $this->curl_http($turl);
		//var_dump($searchVideoHtml);
		//正则查询对应的链接
		$theVideoUrlReg = '/\/\/[a-zA-Z]+\.[a-zA-Z]+\.[a-zA-Z]+\/\w+\/\w+=+\.\w+/is';
		preg_match_all($theVideoUrlReg,$searchVideoHtml,$videoResult);
		//var_dump($videoResult);
		
		//获取视频图片
		//$theVideoReg = '/class=\\"lazyImg\\"\s+src=\\"(\/\/\w+\.\w+\.\w+\/\w+)/isU';
		//$theVideoReg = '/<div class=\\"pack-cover\\">[a-zA-Z\\]+<img alt=\\"(\/\/\w+\.\w+\.\w+\/\w+\\)/isU';
		$theVideoReg = '/<div class=\\\"pack-cover\\\">[a-zA-Z\\\]+<img alt=\\\"(\/\/\w+\.\w+\.\w+\/[0-9a-zA-Z]+)/is';
		preg_match_all($theVideoReg,$searchVideoHtml,$videoResultArray);
		var_dump($videoResultArray);
	}
	
	//查找视频内容页
	function setVideoArticle(){
		$turl = "v.youku.com/v_show/id_XMzIwOTM5MTU0MA==.html";
		$videoHtml = $this->curl_http($turl);
		//var_dump($videoHtml);
		//获取当中的分享链接
		$shareReg = "/<iframe height=498 width=510 src=\'http:\/\/(.*)\' frameborder=0 \'allowfullscreen\'><\/iframe>/is";
		preg_match_all($shareReg,$videoHtml,$videoResult);
		var_dump($videoResult);				
	}
	
	
	function returnResult($turl){
		if($turl == "setCategory"){
			$this->setCategory();
		}
		if($turl == "setArticle"){
			$this->setArticle('');
		}
		if($turl == "setVideoCategory"){
			$this->setVideoCategory();
		}		
		if($turl == "setVideoArticle"){
			$this->setVideoArticle();
		}
	}	
}	 
?>