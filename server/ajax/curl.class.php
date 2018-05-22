<?php 
class theCurl{
	function getCurlData(){
		echo "show curl";
		//初始化curl
		//$theUrl = "http://www.pearvideo.com/";
		$theUrl = "www.woygo.com/muyingchangshi/2017/0613/15953.html";
		$curlObject = curl_init();
		curl_setopt($curlObject, CURLOPT_URL, $theUrl);
		curl_setopt($curlObject, CURLOPT_RETURNTRANSFER,1);
		$output = curl_exec($curlObject);
		curl_close($curlObject);
		//var_dump($output);
		
		//对编码格式进行转码
		$contentOutput = iconv("gb2312","utf-8",$output);
		//var_dump($contentOutput);
		
		echo "=====================================</br>";
		
		//正则表达式设置
		//$preg = "/<h2 .*>.*<\/h2>/is";
		$preg = '/<h1.*>\r*\n*(.)*\r*\n*<\/h1>/';
		preg_match($preg,$contentOutput,$arr);
		//echo "a:".$a."<br/>";
		
		//将html进行转义		
		print_r(htmlspecialchars($arr[0]));

		//$conPreg = '/<div class="con-txt">\r\n*.*/';
		$conPreg = '/<p.*>\r*\n*.*\r*\n*<\/p>/';
		preg_match_all($conPreg, $contentOutput, $arrCon);
		print_r($arrCon);
		//print_r(htmlspecialchars($arrCon[0]));
	}
	
	function curlReturn($turl){
		if($turl == "getCurlData"){
			$this->getCurlData();
		}		
	}	
}