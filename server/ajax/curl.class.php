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
	
	
	//单页面图片页面预览与采集
	//$getUrl为页面请求链接
	//$saveFile为存储的地址
	function getCurlOnePic($getUrl){
		//获取是预览还是存储
		$theType = $_POST['theType'];	
		
		//获取主链接
		$rootUrl = $_POST['rootUrl'];
		//echo $rootUrl . ">>>>>>>>>>>>>>>>><br/>";
		
		//获取正则表达式,因为php默认为提交的数据进行addslashes()转义，因而需要stripslashes()进行反转义
		$theRegexPic = stripslashes($_POST['theRegexPic']);
		//echo $theRegexPic . ">>>>>>>>>>>>>>>>><br/>";
				
		//获取是否有单独存在的链接(如果存在就使用单独的链接，否则使用传递过来的链接)
			
		if($getUrl){			
			$theUrl = $getUrl;	
		}
		else{
			$onePageUrl = $_POST['onePageUrl'];
			$theUrl = $onePageUrl;	
		}
		
		//$theUrl = "http://www.beautylegmm.com/Avy/beautyleg-928.html?page=1";
		//$theUrl = "http://www.beautylegmm.com/Kaylar/beautyleg-1409.html?page=".$i;
		//设置主路径，用于拼图片的路径
		//$rootUrl = "http://www.beautylegmm.com";
		
		$thePicHtml = $this->http_curl($theUrl);
		//print_r($thePicHtml);
		//设置采集图片正则(采用贪婪模式将图片全部匹配)
		//$theRegexPic = '/<img\s*src="(.*)".*alt=".*"\/><\/a>/';	
		//$theRegexPic = '/<img src="(.*)" border="0" alt="\[Beautyleg\].*" width="[0-9]*" height="[0-9]*" \/>/U';
		//$theRegexPic = '/<img src="(.*)"/isU';
		preg_match_all($theRegexPic,$thePicHtml,$picArr);
				
				
		if($theType == "check"){
			//先前端返回对应的数组
			$returnPicArray = array(
				status => 200,
				msg => '图片采集返回成功',
				result => $picArr[1],
			);
			$returnPicJson = json_encode($returnPicArray);
			print_r($returnPicJson);
		}
				
		if($theType == "save"){
			//获取存储的文件夹名称
			$theFile = $_POST['theFile'];
			
			if(!$theFile){
				$theFile == "common";
			}		
			
			//获取存储图片的根路径
			$saveRootPath = $_SERVER['DOCUMENT_ROOT'];
			$theSavePath = $saveRootPath.'/legpic/';	
			
			$theSavePathFile = $theSavePath . $theFile;
			//传建对应的文件夹
			//传建对应的文件夹
			if(!file_exists($theSavePathFile)){
				mkdir($theSavePathFile,0777,true);
				echo "</br>==========".$theSavePathFile."传建成功=============</br>";			
			}
			else{
				echo "</br>==========".$theSavePathFile."已经存在=============</br>";			
			}
			//sleep(3);
					
			//$theUrl = "http://www.beautylegmm.com/Avy/beautyleg-928.html?page=1";
			//$theUrl = "http://www.beautylegmm.com/Kaylar/beautyleg-1409.html?page=".$i;
			//设置主路径，用于拼图片的路径
			//$rootUrl = "http://www.beautylegmm.com";
			
			//$thePicHtml = $this->http_curl($theUrl);
			//print_r($thePicHtml);
			//设置采集图片正则(采用贪婪模式将图片全部匹配)
			//$theRegexPic = '/<img\s*src="(.*)".*alt=".*"\/><\/a>/';	
			//$theRegexPic = '/<img src="(.*)" border="0" alt="\[Beautyleg\].*" width="[0-9]*" height="[0-9]*" \/>/U';
			//$theRegexPic = '/<img src="(.*)"/isU;
			//preg_match_all($theRegexPic,$thePicHtml,$picArr);
			//print_r($picArr);
			
			//获取存储图片的根路径
			//$saveRootPath = $_SERVER['DOCUMENT_ROOT'];
			//$theSavePath = $saveRootPath.'/legpic/';		
			$saveFile = $theSavePathFile . '/';
			
			
			//遍厉数组，将图片存储到到文件夹中
			foreach($picArr[1] as $key => $value){
				//对获取到的路径并进行组装
				$picPath = $rootUrl . $value;
				
				
				//获取当前时间
				$picTime = date("YmdHisa") . $key;
				//echo $picPath;
				//echo $picTime."-------";
				//设置存储文件的路径
				$theSavePicPath = $saveFile . $picTime .'.jpg';
				
				//采用file_get_contents获取到对应的图片
				$thePicture = file_get_contents($picPath);
				
				//file_put_contents将图片存储到对应的文件夹中
				if(file_put_contents($theSavePicPath,$thePicture)){
					echo $picPath ."存储成功<br>";
				};
				//sleep(5);
			}
			
		}	
		
	}
	
	//图片采集（用于设置采集图片的页面方式）
	function getCurlPic($theUrlList){
	
		//获取分页的开始与结束
		$thePageBegin = $_POST['thePageBegin'];
		$thePageEnd = $_POST['thePageEnd'];

		//当不存在传递过来的链接时，说明为在页面中获取的
		if(!$theUrlList){
			//获取分页主体
			$thePageMain = $_POST['thePageMain'];			
		}
		if($theUrlList){
			$thePageMain = $theUrlList .'?page=';
			
		}
		
		//获取存储的文件夹名称
		$theFile = $_POST['theFile'];
		
		//获取存储图片的根路径
		$saveRootPath = $_SERVER['DOCUMENT_ROOT'];
		$theSavePath = $saveRootPath.'/legpic/';	
		
		//组装对应存储的文件夹
		$theSavePathFile = $theSavePath . $theFile ;
		
		//传建对应的文件夹
		if(!file_exists($theSavePathFile)){
			mkdir($theSavePathFile,0777,true);
			echo "</br>==========".$theSavePathFile."传建成功=============</br>";			
		}
		else{
			echo "</br>==========".$theSavePathFile."已经存在=============</br>";			
		}
								
			
		//设置图片路径进行检测		

		//循环分页，采集每个分页下的图片
		for($i=$thePageBegin;$i<=$thePageEnd;$i++){
			echo "第" . $i . "页</br>";
			
			//拼装请求地址
			$getUrl = $thePageMain . $i;
			
			echo ">>>>>>>>>>>".$getUrl.">>>>>>>>>>>>>>";
			//$theUrl = "http://www.beautylegmm.com/Avy/beautyleg-928.html?page=1";
			//$theUrl = "http://www.beautylegmm.com/Kaylar/beautyleg-1409.html?page=".$i;
			
			$this->getCurlOnePic($getUrl);
			//sleep(5);	
			
		}
			
	}
	
	//栏目采集
	function getCurlList($theUrlList){
		//不存在传递过来的$theUrlList时，为单栏目页面获取的	
		if(!$theUrlList){
			//获取采集栏目地址
			$theUrl = $_POST['listPageUrl'];			
		}	
		else{
			$theUrl = $theUrlList;	
		}		
		
		//获取正则表达式,对传递过来的正则进行转码
		$theRegexList = stripslashes($_POST['theRegexPicList']);
		
		//获取是查看或者存储的标识
		$theListType = $_POST['theListType'];
		
		//$theUrl = "http://www.beautylegmm.com/Xin/";
		
		$theListHtml = $this->http_curl($theUrl);
		//print_r($theListHtml);
		
		//正则表达式获取
		//$theRegexList = '/<a\s*href="(.*)"\s*title="\[Beautyleg\].*"><img.*>\s*<\/a>/';
		preg_match_all($theRegexList,$theListHtml,$theArr);
		//print_r($theArr);
		//得到多篇相关文章的路径
		//print_r($theArr[1]);		
		//设置图片存储路径
		
		//当$theListType为check的时候，返回对应的连接数组
		if($theListType == 'check'){
			//组装返回数组
			$listPicArray = array(
				status => 200,
				msg => '图片列表返回成功',
				result => $theArr[1],
			);
			//将数组转换为json返回给前端
			$listPicJson = json_encode($listPicArray);
			print_r($listPicJson);
		}
		if($theListType == 'save'){
			//遍历数组，并对数组中的连接进行图片采集
			foreach($theArr[1] as $key => $value){
				$this->getCurlPic($value);			
			}			
		}		
	}
	
	//多栏目采集
	function getCurlListMore(){
		//获取采集栏目网址主体
		$listPageUrlMain = $_POST['listPageUrlMain'];
		//获取采集栏目网址开始
		$listPageUrlBegin = $_POST['listPageUrlBegin'];
		////获取采集栏目网址结束
		$listPageUrlEnd = $_POST['listPageUrlEnd'];
		
		//循环组装提交的网址
		//http://www.beautylegmm.com/Avy/2.html
		for($i = $listPageUrlBegin; $i<= $listPageUrlEnd; $i++){
			$theHtml = $listPageUrlMain . '/' . $i .'.html'; 
			//提交栏目的请求调用
			$this->getCurlList($theHtml);
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
		set_time_limit(0);
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
		if($turl == "getCurlPic"){
			$this->getCurlPic('');
		}
		if($turl == "getCurlList"){
			$this->getCurlList('');
		}
		
		if($turl == "getCurlOnePic"){
			$this->getCurlOnePic('');
		}
		
		if($turl == 'getCurlListMore'){
			$this->getCurlListMore();
		}
	}	
	
}
