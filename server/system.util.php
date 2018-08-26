<?php class util{
	//定义当前目录路径
	//传建文件夹
	function mkdirWj($name){
		//定义当前路径
		define('APP_PATH',dirname(dirname(__FILE__)));
		//echo APP_PATH;
		if(!file_exists(APP_PATH."/article/".$name)){
			//if(mkdir(APP_PATH."/article/".$name)){
			//	echo $name."传建文件夹成功";
			//	chmod(APP_PATH."/article/".$name, 0777);
			//}
			if(mkdir(APP_PATH."/article/".$name, 0777 ,true)){
				echo $name."传建文件夹成功";
				
			}
			else{
				echo $name."传建文件夹失败";
			}
			//return true;
		}
		else{
			echo $name."该文件夹存在";
			//return false;
		}	
	}
	
	//传建文件夹（新）
	//$localPath：为存储的路径
	//$name：为需要传建的文件夹名称
	function createFile($localPath,$name){
		$thePath = 	$localPath . $name;
		//是否存在并传建文件夹
		if(!file_exists($thePath)){
			//如果不存在就传建
			mkdir($thePath,0777,true);
			echo $name."传建成功";
		}
		else{
			echo $name."传建失败";		
		}
	}
	

	//设置生成随机的session_token
	//$num为随机数的位数
	function setSessionToken($num){
		$theTime = date("YmdHis");
		//生成随机$num位的数字
		$arr = array(0,1,2,3,4,5,6,7,8,9,'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
		$arrLenght =  count($arr);
		//echo "长度".$arrLenght.">>>";
		for($i = 0; $i<$num; $i++){
			$str .= $arr[rand(0,$arrLenght-1)];			
		}
		$theRandNum = $theTime . $str;
		return $theRandNum;
		
	} 
	
	//图片上传
	function fileUpload($local,$name,$baseImg){
		//正则获取相关内容
		//存放路径设置
		if(preg_match('/^(data:\s*image\/(\w+);base64,)/', $baseImg, $result)){
			//print_r($result);
			$type = $result[2];
			if(in_array($type,array('pjpeg','jpeg','jpg','gif','bmp','png'))){
				//存放文件
				$theFilePath = $local . $name;
				//将图片信息file_put_contents到相关文件中
				if(file_put_contents($theFilePath,base64_decode(str_replace($result[1],'',$baseImg)))){
					//返回组装数据
					$returnImgArray = array(
						status => 200,
						msg => '图片上传成功',
						result =>''
					);					
				}
				else{
					$returnImgArray = array(
						status => 400,
						msg => '图片上传失败',
						result =>''
					);
				}
			}
			else{
				$returnImgArray = array(
					status => 500,
					msg => '图片格式不正确',
					result =>''
				);								
			}
			return $returnImgArray;
		}		
	}
	
	//给前端返回json函数的封装
	//$statusNum:返回的状态码
	//$theMsg:返回的信息介绍
	//$theResult:返回的结果,默认为空
	function ajaxJson($statusNum,$theMsg,$theResult=''){
		$returnArray = array(
			status => $statusNum,
			msg => $theMsg,
			result => $theResult
		);
		
		//转换为json
		$returnJson = json_encode($returnArray);
		return $returnJson;
	}
	
	//获取用户的IP
	function getUserIp(){
		if(isset($_SERVER["HTTP_CLIENT_IP"]) and strcasecmp($_SERVER["HTTP_CLIENT_IP"], "unknown")){
			return $_SERVER["HTTP_CLIENT_IP"];
		}
		if(isset($_SERVER["HTTP_X_FORWARDED_FOR"]) and strcasecmp($_SERVER["HTTP_X_FORWARDED_FOR"], "unknown")){
			return $_SERVER["HTTP_X_FORWARDED_FOR"];
		}
		if(isset($_SERVER["REMOTE_ADDR"])){
			return $_SERVER["REMOTE_ADDR"];
		}
		return "";		
	}

	//物理根路径
	function physicalPath($afterPath = ''){
		//获取根目录
		$rootPath = $_SERVER["DOCUMENT_ROOT"];
		if($afterPath){
			$rootPath = $rootPath . $afterPath; 
		}
		return $rootPath;		
	}
	
	//网络路径
	function webPath($afterPath = ''){
		//获取根目录
		$rootPath = $_SERVER['HTTP_HOST'];
		if($afterPath){
			$rootPath = $rootPath .$afterPath; 
		}
		return $rootPath;
	}	
}
