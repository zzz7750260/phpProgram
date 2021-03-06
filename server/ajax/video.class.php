<?php 
include("../system.util.php");
class theVideo{
	function addVideo(){
		//获取传递过来的视频数组
		$theVideoArray = $_POST['theVideoArray'];
		$theArticleId = $_POST['theArticleId'];
		//print_r($theVideoArray);
		$theArticleType = $_POST['theArticleType'];	 //获取传递过来的类型是添加还是编辑
		
		$n = 0; //设置插入视频信息的成功数;
		
		$theUtil = new util();
				
		//将传递过来的数组进行遍历并存储到数据库中
		foreach($theVideoArray as $videoKey => $videoValue){
			if($theArticleType == 'add'){
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
			
			if($theArticleType == 'edit'){
				$videoAddSql = "update video set video_name = '".$videoValue['video-name']."', video_pt = '".$videoValue['video-platform']."', video_link = '".$videoValue['video-link']."', video_img = '".$videoValue['video-pic']."' where vid = ".$videoValue['video-id']."";				
				$videoAddSql_db = mysql_query($videoAddSql);
				if($videoAddSql_db){
					if($videoValue['video-pic-file']){
						//设置图片上传路径
						$theVideoPath = $theUtil->physicalPath('/upload/video/');	
						//将图片上传
						$theUtil->fileUpload($theVideoPath,$videoValue['video-pic'],$videoValue['video-pic-file']);																
					}
					$n++;
				}			
			}
		}
		
		//当$n大于0时说明有video的信息插入成功
		if($n>0){
			//组装返回前端数据
			$returnVideoArray = array(
				status =>200,
				msg => "视频信息插入或编辑成功",
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
	
	//根据video的id来返回对应的信息
	function showVideo(){
		$theVideoId = $_GET['videoId'];
		$videoShowArraySql = "select * from video where vid = $theVideoId";
		$videoShowArraySql_db = mysql_query($videoShowArraySql);
		$videoShowArray = array();
		while($videoShowArraySql_db_array = mysql_fetch_assoc($videoShowArraySql_db)){
			$videoShowArray = $videoShowArraySql_db_array;
		}
		//print_r($videoShowArray);
		
		//获取数据个数
		if($videoShowArray){
			//组装返回视频的数据
			$returnVideoShowArray = array(
				status => 200,
				msg =>'视频返回成功',
				result => $videoShowArray
			);			
		}
		else{
			//组装返回视频的数据
			$returnVideoShowArray = array(
				status => 400,
				msg =>"视频返回失败",
				result => ''
			);	
		}
		//将数组转换为json返回给前端
		$returnVideoShowJson = json_encode($returnVideoShowArray);		
		print_r($returnVideoShowJson);	
	}
	
	//根据文章的id来返回对应的视频组
	function getArticleVideoArray(){
		//获取文章的id
		$theArticleId = $_GET['articleId'];
		$theVideoArraySql = "select * from video where video_article = $theArticleId";	
		$theVideoArraySql_db = mysql_query($theVideoArraySql);
		if($theVideoArraySql_db){
			$theVideoArray = array();
			while($theVideoArraySql_db_array = mysql_fetch_assoc($theVideoArraySql_db)){
				$theVideoArray[] = $theVideoArraySql_db_array;
			}
			//根据是否有视频返回结果
			if(count($theVideoArray)>0){
				//返回前端数据
				$returnTheVideoArray = array(
					status => 200,
					msg =>"视频结果返回成功",
					result => $theVideoArray
				);	
			}
			else{
				$returnTheVideoArray = array(
					status => 300,
					msg => "该文章暂无视频",
					result => ''
				);	
			}
		}
		else{
			$returnTheVideoArray = array(
				status => 400,
				msg =>"出现未知错误",
				result =>''
			);	
		}
		//将数据转为json返回给前端
		$returnTheVideoJson = json_encode($returnTheVideoArray);
		print_r($returnTheVideoJson);
	}
	
	function theReturn($turl){
		if($turl =='addVideo'){
			$this->addVideo();		
		}	
		if($turl =='showVideo'){
			$this->showVideo();
		}
		if($turl =='getArticleVideoArray'){
			$this->getArticleVideoArray();
		}
	}	
}