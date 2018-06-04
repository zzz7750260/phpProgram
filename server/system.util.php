<?php class util{
	//定义当前目录路径
	//传建文件夹
	function mkdirWj($name){
		//定义当前路径
		define('APP_PATH',dirname(dirname(__FILE__)));
		//echo APP_PATH;
		if(!file_exists(APP_PATH."/article/".$name)){
			mkdir(APP_PATH."/article/".$name,0777);
			//echo "传建文件成功";
			//return true;
		}
		else{
			//echo "该文件夹存在";
			//return false;
		}	
	}

	//设置生成随机的session_token
	//$num为随机数的位数
	function setSessionToken($num){
		$theTime = date("YmdHis");
		//生成随机$num位的数字
		$arr = array(0,1,2,3,4,5,6,7,8,9,'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
		$arrLenght =  count($arr);
		echo "长度".$arrLenght.">>>";
		for($i = 0; $i<$num; $i++){
			$str .= $arr[rand(0,$arrLenght-1)];			
		}
		$theRandNum = $theTime . $str;
		return $theRandNum;
		
	} 
}