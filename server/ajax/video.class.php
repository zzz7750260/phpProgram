<?php 
include("../system.util.php");
class theVideo{
	function addVideo(){
		//��ȡ���ݹ�������Ƶ����
		$theVideoArray = $_POST['theVideoArray'];
		$theArticleId = $_POST['theArticleId'];
		//print_r($theVideoArray);
		$theArticleType = $_POST['theArticleType'];	 //��ȡ���ݹ�������������ӻ��Ǳ༭
		
		$n = 0; //���ò�����Ƶ��Ϣ�ĳɹ���;
		
		$theUtil = new util();
				
		//�����ݹ�����������б������洢�����ݿ���
		foreach($theVideoArray as $videoKey => $videoValue){
			if($theArticleType == 'add'){
				$videoAddSql = "insert into video (video_article, video_name, video_pt, video_link, video_img) values ($theArticleId, '".$videoValue['video-name']."','".$videoValue['video-platform']."','".$videoValue['video-link']."' ,'".$videoValue['video-pic']."')";
			
				$videoAddSql_db = mysql_query($videoAddSql);
				
				//������ɹ�ʱ,�����ݹ�����base64ͼƬ���д洢
				if($videoAddSql_db){
					//����ͼƬ�ϴ�·��
					$theVideoPath = $theUtil->physicalPath('/upload/video/');	
					//��ͼƬ�ϴ�
					$theUtil->fileUpload($theVideoPath,$videoValue['video-pic'],$videoValue['video-pic-file']);				
					$n++;				
				}
			}
			
			if($theArticleType == 'edit'){
				$videoAddSql = "update video set video_name = '".$videoValue['video-name']."', video_pt = '".$videoValue['video-platform']."', video_link = '".$videoValue['video-link']."', video_img = '".$videoValue['video-pic']."' where vid = ".$videoValue['video-id']."";				
				$videoAddSql_db = mysql_query($videoAddSql);
				if($videoAddSql_db){
					if($videoValue['video-pic-file']){
						//����ͼƬ�ϴ�·��
						$theVideoPath = $theUtil->physicalPath('/upload/video/');	
						//��ͼƬ�ϴ�
						$theUtil->fileUpload($theVideoPath,$videoValue['video-pic'],$videoValue['video-pic-file']);																
					}
					$n++;
				}			
			}
		}
		
		//��$n����0ʱ˵����video����Ϣ����ɹ�
		if($n>0){
			//��װ����ǰ������
			$returnVideoArray = array(
				status =>200,
				msg => "��Ƶ��Ϣ�����༭�ɹ�",
				result => $n
			);		
		}
		else{
			$returnVideoArray = array(
				status =>400,
				msg => "��Ƶ��Ϣ����ʧ��",
				result =>''
			);		
		}	
		
		//ת��Ϊjson���ݷ��ظ�ǰ��
		$returnVideoJson = json_encode($returnVideoArray);		
		print_r($returnVideoJson);
	}
	
	//����video��id�����ض�Ӧ����Ϣ
	function showVideo(){
		$theVideoId = $_GET['videoId'];
		$videoShowArraySql = "select * from video where vid = $theVideoId";
		$videoShowArraySql_db = mysql_query($videoShowArraySql);
		$videoShowArray = array();
		while($videoShowArraySql_db_array = mysql_fetch_assoc($videoShowArraySql_db)){
			$videoShowArray = $videoShowArraySql_db_array;
		}
		//print_r($videoShowArray);
		
		//��ȡ���ݸ���
		if($videoShowArray){
			//��װ������Ƶ������
			$returnVideoShowArray = array(
				status => 200,
				msg =>'��Ƶ���سɹ�',
				result => $videoShowArray
			);			
		}
		else{
			//��װ������Ƶ������
			$returnVideoShowArray = array(
				status => 400,
				msg =>"��Ƶ����ʧ��",
				result => ''
			);	
		}
		//������ת��Ϊjson���ظ�ǰ��
		$returnVideoShowJson = json_encode($returnVideoShowArray);		
		print_r($returnVideoShowJson);	
	}
	
	//�������µ�id�����ض�Ӧ����Ƶ��
	function getArticleVideoArray(){
		//��ȡ���µ�id
		$theArticleId = $_GET['articleId'];
		$theVideoArraySql = "select * from video where video_article = $theArticleId";	
		$theVideoArraySql_db = mysql_query($theVideoArraySql);
		if($theVideoArraySql_db){
			$theVideoArray = array();
			while($theVideoArraySql_db_array = mysql_fetch_assoc($theVideoArraySql_db)){
				$theVideoArray[] = $theVideoArraySql_db_array;
			}
			//�����Ƿ�����Ƶ���ؽ��
			if(count($theVideoArray)>0){
				//����ǰ������
				$returnTheVideoArray = array(
					status => 200,
					msg =>"��Ƶ������سɹ�",
					result => $theVideoArray
				);	
			}
			else{
				$returnTheVideoArray = array(
					status => 300,
					msg => "������������Ƶ",
					result => ''
				);	
			}
		}
		else{
			$returnTheVideoArray = array(
				status => 400,
				msg =>"����δ֪����",
				result =>''
			);	
		}
		//������תΪjson���ظ�ǰ��
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