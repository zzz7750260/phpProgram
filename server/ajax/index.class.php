<?php
class theIndex{		
	function setIndexInfo(){
		//获取传递过来的用户信息
		$theIndexMain = $_POST['index-main'];
		$theIndexTitle = $_POST['index-title'];
		$theIndexKeyword = $_POST['index-keyword'];
		$theIndexShort = $_POST['index-short'];
		$theIndexAuthor = $_POST['index-author'];
		$theIndexRecord = $_POST['index-record'];
		echo $theIndexMain;
		//存储网站信息
		$setIndexSql = "insert web_info (web_name, web_title, web_keyword, web_short, web_author, web_record) values ('$theIndexMain', '$theIndexTitle', '$theIndexKeyword', '$theIndexShort', '$theIndexAuthor', '$theIndexRecord')";
		
		$setIndexSql_db = mysql_query($setIndexSql);
		
		if($setIndexSql_db){
			//组装返回数据
			$returnIndexArray = array(
				'status' => 200,
				'msg' => '网站信息设置插入成功',
				'result' =>''
			);			
		}
		else{
			$returnIndexArray = array(
				'status' => 400,
				'msg' => '网站信息设置失败',
				'result' =>''
			);				
		}
		$returnIndexJson = json_encode($returnIndexArray);	
		print_r($returnIndexJson);
	}
	function returnIndex($turl){
		if($turl == 'setIndexInfo'){
			$this->setIndexInfo();
		}			
	}	
}