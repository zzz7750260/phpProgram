<?php class util{
	//定义当前目录路径
	//传建文件夹
	function mkdirWj($name){
		//定义当前路径
		define('APP_PATH',dirname(dirname(__FILE__)));
		echo APP_PATH;
		if(!file_exists(APP_PATH."/article/".$name)){
			mkdir(APP_PATH."/article/".$name,0777);
			echo "传建文件成功";
			//return true;
		}
		else{
			echo "该文件夹存在";
			//return false;
		}	
	}	
}