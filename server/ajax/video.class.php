<?php 
include("../system.util.php");
class theVideo{
	function addVideo(){
		//��ȡ���ݹ�������Ƶ����
		$theVideoArray = $_POST['theVideoArray'];
		$theArticleId = $_POST['theArticleId'];
		//print_r($theVideoArray);
		
		$n = 0; //���ò�����Ƶ��Ϣ�ĳɹ���;
		
		$theUtil = new util();
				
		//�����ݹ�����������б������洢�����ݿ���
		foreach($theVideoArray as $videoKey => $videoValue){
			
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
		
		//��$n����0ʱ˵����video����Ϣ����ɹ�
		if($n>0){
			//��װ����ǰ������
			$returnVideoArray = array(
				status =>200,
				msg => "��Ƶ��Ϣ����ɹ�",
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

	function theReturn($turl){
		if($turl =='addVideo'){
			$this->addVideo();
			
		}	
	}	
}