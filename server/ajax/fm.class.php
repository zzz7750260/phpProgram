<?php 
include("../system.util.php");
class theFm{
	function addFmArticle(){
		$fmAuther = $_POST['FM-author'];
		$fmKeyword = $_POST['FM-keyword'];
		$fmShort = $_POST['FM-short'];
		$fmShortTitle = $_POST['FM-short-title'];
		$fmTitle = $_POST['FM-title'];
		$articleFm = $_POST['article-FM'];
		$articleFmFile = $_POST['article-FM-file'];
		$articlePic = $_POST['article-pic'];
		$articlePicFile = $_POST['article-pic-file'];
		$categorySelect = $POST['category_select'];
		$fmArticle = $_POST['fm-article'];
		
		$theUtil = new util();
		
		//生成日期
		$theDate = date("Y-m-d h:i:s");
		//插入数据库
		$insertFmArticleSql = "insert into FM (fm_category, f_title, f_keyword, f_img, f_admin, f_short, f_time,  	f_container) values ('$categorySelect', '$fmTitle', '$fmKeyword', '$articlePic', '$fmAuther', '$fmShort', '$theDate', '$fmArticle')";	
		$insertFmArticleSql_db = mysql_query($insertFmArticleSql);
		if($insertFmArticleSql_db){
			//当数据插入成功时，将图片base64转为图片
			if($articlePicFile){
				//设置上传路径
				$setImgPath = "/upload/fm/";
				$thePath = $theUtil->physicalPath($setImgPath);
				$returnImgArray = $theUtil->fileUpload($thePath,$articlePic,$articlePicFile);
			}
			
			//组装返回前端数据									
			$returnArray = array(
				status => 200,
				msg => "fm插入成功",
				result => array(
					img =>$returnImgArray
				)
			);
			
		}
		else{
			//组装返回前端数据									
			$returnArray = array(
				status => 400,
				msg => "fm插入失败",
				result => ""
			);
		}	

		//将数组转为json返回给前端
		$returnJson = json_encode($returnArray);
		print($returnJson);
	}
	
	//获取fm文件的上传
	function getFmFile(){
		$f = $_FILES['file'];
		//var_dump($f);
		$theUtil = new util();		
		
		if ($_FILES["file"]["error"] > 0){
			//echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
			$returnFmArray = array(
				status => 404,
				msg => "上传出现错误",
				result => "",
			);	
			
		}
		else{
			//echo "Upload: " . $_FILES["file"]["name"] . "<br />";
			//echo "Type: " . $_FILES["file"]["type"] . "<br />";
			//echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
			//echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";

			$setFmPath = "/mp3/".$_FILES["file"]["name"];
			$fmPath = $theUtil->physicalPath($setFmPath);
			
			
			if (file_exists($fmPath)){
				//echo $_FILES["file"]["name"] . " already exists. ";
				
				//设置返回前端数组
				$returnFmArray = array(
					status => 400,
					msg => "该歌曲已存在",
					result =>"",
				);
			}
			else{
				if(move_uploaded_file($_FILES["file"]["tmp_name"],$fmPath)){
					//设置返回前端数组
					$returnFmArray = array(
						status => 200,
						msg => "歌曲上传成功",
						result =>array(
							info => $_FILES["file"],
							url => $fmPath,
						),
					);					
				}
				else{
					$returnFmArray = array(
						status => 500,
						msg => "歌曲上传出现错误",
						result => "",
					);	
				}
				
			}
			
		}
		//将数组转为json返回给前端
		$returnFmJson = json_encode($returnFmArray);
		print($returnFmJson);
	}
	
	function theReturn($turl){
		if($turl == 'addFmArticle'){
			$this->addFmArticle();
		}
		if($turl == 'getFmFile'){
			$this->getFmFile();
		}
	}	
}