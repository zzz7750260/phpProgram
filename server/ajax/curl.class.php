<?php 
class theCurl{
	function getCurlData(){
		//echo "show curl";
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
		
		//echo "=====================================</br>";
		
		//正则表达式设置
		//$preg = "/<h2 .*>.*<\/h2>/is";
		$preg = '/<h1.*>\r*\n*(.)*\r*\n*<\/h1>/';
		preg_match($preg,$contentOutput,$arr);
		//echo "a:".$a."<br/>";
		
		
		//将html进行转义		
		//print_r(htmlspecialchars($arr[0]));

		//$conPreg = '/<div class="con-txt">\r\n*.*/';
		$conPreg = '/<p.*>\s*.*\s*<\/p>/';
		preg_match_all($conPreg, $contentOutput, $arrCon);
		//print_r($arrCon);
		//print_r(htmlspecialchars($arrCon[0]));
		
		//echo "数据类型为：".$this->changeHtml($arrCon[0])."<br/>";
		
		//将获取到的数据进行转义
		$htmlTitle = $this->changeHtml($arr[0]);
		$htmlContainer = $this->changeHtml($arrCon[0]);
		
		//获取是否存入数据库
		$theAdd = $_GET['getAdd'];
		if(!$theAdd){
			//组装返回数据
			$returnCurlArray = array(
				status => 200,
				msg => '返回数据内容成功',
				result => array(
					//curlTitle => $arr,
					curlContainer => $this->changeHtml($arrCon[0]),
				)
			);
			
			$returnCurlJson = json_encode($returnCurlArray);
			print_r($returnCurlJson);			
		}
		if($theAdd == 'add'){
			//echo $htmlTitle;
			//echo $htmlContainer;
			
			$curlSql = "insert into curl_article (curl_title, curl_container) values ('$htmlTitle','$htmlContainer')";
			$curlSql_db = mysql_query($curlSql);
			if($curlSql_db){
				$returnCurlArray = array(
					status => 200,
					msg => '返回数据内容成功',
					result => ''
				);	
			}
			else{
				$returnCurlArray = array(
					status => 400,
					msg => '返回数据内容失败',
					result => ''
				);
				
			}
			$returnCurlJson = json_encode($returnCurlArray);
			print_r($returnCurlJson);			
		}
	}
	
	function curlReturn($turl){
		if($turl == "getCurlData"){
			$this->getCurlData();
		}		
	}

	//将html进行转义
	function changeHtml($value){
		//判断传递进来的类型
		$theType = gettype($value);
		//echo $theType;
		//当类型为string（字符串）时，直接进行转义
		//htmlentities($str, ENT_COMPAT , "UTF-8")  需指定UTF-8否则乱码
		if($theType == "string"){
			$theRetureHtml = htmlentities($value, ENT_COMPAT,"UTF-8");
			return $theRetureHtml;			
		}
		//当类型为array（数组）时，先将数组转为字符串再进行转义
		if($theType == "array"){
			//print_r($value);
			$theArrayString = implode("",$value);
			//return $theArrayString;
			//将组成的字符串进行转义
			$theRetureHtml = htmlentities($theArrayString,ENT_COMPAT,"UTF-8");
			//$theRetureHtml = htmlentities($theArrayString);
			//echo $theRetureHtml;
			return $theRetureHtml;		
		}
	}
}