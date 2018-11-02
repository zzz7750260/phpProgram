<?php 
if ($_FILES["file"]["error"] > 0)
  {
  echo "Error: " . $_FILES["file"]["error"] . "<br />";
  }
else
  {
  echo "Upload: " . $_FILES["file"]["name"] . "<br />";
  echo "Type: " . $_FILES["file"]["type"] . "<br />";
  echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
  echo "Stored in: " . $_FILES["file"]["tmp_name"];
  }
  
	//设置存储路径
	$rootPath = $_SERVER["DOCUMENT_ROOT"];
	
	//设置图片路径
	$imgPath = '/img/qiniu/' . $_FILES["file"]["name"];
	
	//设置上传路径
	$putPath = $rootPath . $imgPath;
	
	//保存文件
	if(move_uploaded_file($_FILES["file"]["tmp_name"],$putPath)){
		echo "input = success";
		$sinput = 'success';
	}
	else{
		echo "input false";
		$sinput = 'false';
	}
	
	if($sinput == 'success'){
		$fileName = $imgPath;
		//$saveName = $_FILES["file"]["name"]
		include('qiniuforminfoga.php');
		//inputToQiniu($imgPath);
		
	}