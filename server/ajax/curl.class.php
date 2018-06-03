<?php 
class theCurl{
	function getCurlData(){
		//echo "show curl";
		//初始化curl
		//$theUrl = "http://www.pearvideo.com/";				
		//$theUrl = "www.woygo.com/muyingchangshi/2017/0613/15953.html";
		$theUrl = $_POST['curl-url'];	
				
		//$output = $this->http_curl($theUrl);  //这个是没转码之前的设置
		
		$contentOutput = $this->http_curl($theUrl);
		//对编码格式进行转码
		//$contentOutput = iconv("gb2312","utf-8",$output);
		//var_dump($contentOutput);
		
		//echo "=====================================</br>";
		
		
		//正则表达式设置
		//$preg = "/<h2 .*>.*<\/h2>/is";
		
		//$preg = '/<h1.*>\r*\n*(.)*\r*\n*<\/h1>/';
		
		
		//获取题目的正则
		$reg = $_POST['curl-title'];
		
		//因为后端对于post过来的数据都会addslashes()转义，因而需要stripslashes()反转义处理
		$preg = stripslashes($reg);
		//echo $preg; 
		preg_match($preg,$contentOutput,$arr);
		//echo "a:".$a."<br/>";
		
		
		//将html进行转义		
		//print_r(htmlspecialchars($arr[0]));

		//$conPreg = '/<div class="con-txt">\r\n*.*/';
		//$conPreg = '/<p.*>\s*.*\s*<\/p>/';
		
		//获取内容的正则
		$onPreg = $_POST['curl-container'];		
		$conPreg = stripslashes($onPreg);
		
		preg_match_all($conPreg, $contentOutput, $arrCon);
		//print_r($arrCon);
		//print_r(htmlspecialchars($arrCon[0]));
		
		//echo "数据类型为：".$this->changeHtml($arrCon[0])."<br/>";
		
		//将获取到的数据进行转义
		$htmlTitle = $this->changeHtml($arr[0]);
		$htmlContainer = $this->changeHtml($arrCon[0]);
		
		//获取选择的分类
		$htmlCategory = $_POST['curl-select'];
		
		//获取文章的来源
		$htmlSource = $_POST['curl-source'];
		
		
		//获取是否存入数据库
		$theAdd = $_POST['getAdd'];
		if(!$theAdd){
			//组装返回数据
			$returnCurlArray = array(
				status => 300,
				msg => '返回数据内容成功',
				result => array(
					curlTitle => $htmlTitle,
					curlContainer =>$htmlContainer,
					curlCategory =>$htmlCategory,
					curlSource =>$htmlSource,
				)
			);
			
			$returnCurlJson = json_encode($returnCurlArray);
			print_r($returnCurlJson);			
		}
		if($theAdd == 'add'){
			//echo $htmlTitle;
			//echo $htmlContainer;
			
			//检测该文章是否被采集过
			$isExistNum = $this->isExistArticle($htmlTitle,$theUrl);
			//echo $isExistNum;
			if($isExistNum > 0){
				//返回对应的数组
				$returnCurlArray = array(
					status => 300,
					msg => '该文章已经被采集过',
					result => ''				
				);
			}
			else{
				$curlSql = "insert into curl_article (curl_title, curl_container,curl_category,curl_source,curl_html) values ('$htmlTitle','$htmlContainer','$htmlCategory','$htmlSource','$theUrl')";
				$curlSql_db = mysql_query($curlSql);
				if($curlSql_db){
					$returnCurlArray = array(
						status => 200,
						msg => '数据内容插入成功',
						result => ''
					);	
				}
				else{
					$returnCurlArray = array(
						status => 400,
						msg => '数据内容插入失败',
						result => ''
					);
					
				}	
				
			}
			$returnCurlJson = json_encode($returnCurlArray);
			print_r($returnCurlJson);			
		}
	}
	
	//批量采集
	function getCurlDataMore(){
		//获取网址
		$mCurlUrl = $_POST['curl-url'];
		//获取采集范围
		$mCurlB = $_POST['curl-begin'];
		$mCurlE = $_POST['curl-end'];		
		//获取网址后缀
		$mCurlS = $_POST['curl-suffix'];
		//获取标题的正则
		$mCurlTitle = $_POST['curl-title'];		
		//获取内容的正则
		$mCurlContainer = $_POST['curl-container'];		
		//获取存储的分类
		$mCurlCategory = $_POST['curl-select'];		
		//获取内容的来源
		$mCurlSource = $_POST['curl-source'];
		//获取显示的类型
		$mCurlType = $_POST['tTpye'];
		
		
		//获取是否添加
		$mCurlAdd = $_POST['curl-more-add'];
				
		//设置存放title的数组
		$theTitleArr = array();
		
		//设置成功插入的条数
		$successNum = 0;

		//设置失败插入的条数
		$falseNum = 0;
		
		//设置已经采集过文章的条数
		$repeatNum = 0;
		
		
		//循环组成对应的网址
		for($i = $mCurlB; $i<$mCurlE;$i++){
			//组装成对应的网址
			$theMUrl = $mCurlUrl . $i . '.' . $mCurlS;
			//echo $theMUrl;
			//提交网址并得到返回值
			$theOutPut = $this->http_curl($theMUrl);
			//echo $theOutPut;
			//根据返回结果对内容进行筛选
			$mCurlTitleShow = $this->theRegexHtml($mCurlTitle,$theOutPut);
			$mCurlContainerShow = $this->theRegexHtml($mCurlContainer,$theOutPut);
								
			$theTitleArr[] =  $mCurlTitleShow;
						
			//echo $mCurlTitleShow;
			//print_r($theOutPut);
			//echo "=============================<br/>";
			
			//当选择的类型为插入数据库时
			if($mCurlType == "add"){
				//检测该文章是否被采集过
				$isExistNumMore = $this->isExistArticle($mCurlTitleShow,$theMUrl);
				if($isExistNumMore>0){
					$repeatNum = $repeatNum + 1;					
				}
				else{
					$mCurlSql = "insert into curl_article (curl_title, curl_container, curl_category, curl_source, curl_html) values ('$mCurlTitleShow', '$mCurlContainerShow', '$mCurlCategory', '$mCurlSource', '$theMUrl')";
					
					$mCurlSql_db = mysql_query($mCurlSql);
					
					if($mCurlSql_db){
						$successNum = $successNum + 1;					
					}
					else{
						$falseNum = $falseNum + 1;					
					}									
				}

			}
						
		}	
		//当类型为显示时，返回的结果为
		if($mCurlType =="show"){
			//重新组装数组
			$returnCurlMoreShow = array(
				status => 200,
				msg => "批量采集返回展示成功",
				result => $theTitleArr
			);
			
			//将数组转换为json
			$returnCurlMoreShowJson = json_encode($returnCurlMoreShow);
			
			print_r($returnCurlMoreShowJson);
		}
		
		if($mCurlType =="add"){
			$returnCurlMoreShow = array(
				status => 300,
				msg => "批量采集插入数据",
				result => array(
					theSuccess => $successNum,
					theFalse => $falseNum,
					repeatNum => $repeatNum,
				)
			);
			
			//将数组转换为json
			$returnCurlMoreShowJson = json_encode($returnCurlMoreShow);
			print_r($returnCurlMoreShowJson);
		}
		
		//print_r($theTitleArr);
			
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
			
			//return $theRetureHtml;			
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
			//return $theRetureHtml;		
		}
		
		//进行addcslashes()转义
		//$aTheRetureHtml = addcslashes($theRetureHtml);
		//return $aTheRetureHtml;
		
		//进行mysql_real_escape_string()转义
		$aMTheRetureHtml = mysql_real_escape_string($theRetureHtml);
		return $aMTheRetureHtml;
		
		//进行addcslashes()转义
		//$aTheRetureHtml = addcslashes($aMTheRetureHtml);
		//return $aTheRetureHtml;
	}
	
	//curl请求的封装
	function http_curl($theUrl){
		$curlObject = curl_init();
		curl_setopt($curlObject, CURLOPT_URL, $theUrl);
		curl_setopt($curlObject, CURLOPT_RETURNTRANSFER,1);
		$output = curl_exec($curlObject);
		curl_close($curlObject);
		//var_dump($output);	

		//检测编码,如果格式不为utf-8，就自动转换为utf-8否则乱码
		$theCode = mb_detect_encoding($output,array("ASCII",'UTF-8',"GB2312","GBK",'BIG5'));
		//echo "当前的编码格式为：".$theCode;
		if($theCode != "UTF-8" || $theCode != "utf-8"){
			$output = iconv($theCode,"utf-8",$output);
		}			
		return $output;
	}
	
	//利用正则对内容进行筛选
	function theRegexHtml($theRegex,$theContainer){
		//需要针对传进来的正则stripslashes()反转义处理
		$sTheRegex = stripslashes($theRegex);
		//利用正则对内容进行筛选
		preg_match_all($sTheRegex,$theContainer,$theArr);
		
		//对获取到的数据进行根式化
		$theHtmlContainr = $this->changeHtml($theArr[0]);
		return $theHtmlContainr;
	
	}
	
	//检测该文章是否已经被采集过
	
	function isExistArticle($articleTitle,$articleHtml){
		$isExistSql = "select * from curl_article where curl_title = '$articleTitle' or curl_html = '$articleHtml'";
		$isExistSql_db = mysql_query($isExistSql);
		$isExistSql_db_num = mysql_num_rows($isExistSql_db);
		return $isExistSql_db_num;	
	}
	
	//返回执行方法
	function curlReturn($turl){
		if($turl == "getCurlData"){
			$this->getCurlData();
		}
		if($turl == "getCurlDataMore"){
			$this->getCurlDataMore();			
		}
	}	
	
}
