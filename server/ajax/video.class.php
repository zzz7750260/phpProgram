<?php 
include("../system.util.php");
class theVideo{
	function addVideo(){
		//获取传递过来的视频数组
		$theVideoArray = $_POST['theVideoArray'];
		$theArticleId = $_POST['theArticleId'];
		//print_r($theVideoArray);
		
		$n = 0; //设置插入视频信息的成功数;
		
		$theUtil = new util();
				
		//将传递过来的数组进行遍历并存储到数据库中
		foreach($theVideoArray as $videoKey => $videoValue){
			
			$videoAddSql = "insert into video (video_article, video_name, video_pt, video_link, video_img) values ($theArticleId, '".$videoValue['video-name']."','".$videoValue['video-platform']."','".$videoValue['video-link']."' ,'".$videoValue['video-pic']."')";
			
			$videoAddSql_db = mysql_query($videoAddSql);
			
			//当插入成功时,将传递过来的base64图片进行存储
			if($videoAddSql_db){
				//设置图片上传路径
				$theVideoPath = $theUtil->physicalPath('/upload/video/');	
				//将图片上传
				$theUtil->fileUpload($theVideoPath,$videoValue['video-pic'],$videoValue['video-pic-file']);				
				$n++;				
			}
		}
		
		//当$n大于0时说明有video的信息插入成功
		if($n>0){
			//组装返回前端数据
			$returnVideoArray = array(
				status =>200,
				msg => "视频信息插入成功",
				result => $n
			);		
		}
		else{
			$returnVideoArray = array(
				status =>400,
				msg => "视频信息插入失败",
				result =>''
			);		
		}	
		
		//转换为json数据返回给前端
		$returnVideoJson = json_encode($returnVideoArray);		
		print_r($returnVideoJson);
	}

	function theReturn($turl){
		if($turl =='addVideo'){
			$this->addVideo();
			
		}	
	}	
}