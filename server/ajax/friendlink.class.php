<?php
include('../system.util.php');
class friendLink{
	//添加友链链接
	function addFriendLink(){
		//获取传递过来的数据
		$friendTitle = $_POST['friend-title'];
		$friendLink = $_POST['friend-link'];
		$friendIntroduction = $_POST['friend-introduction'];
		$friendType = $_POST['friend-type'];
		$friendImg = $_POST['friend-img'];
		$friendBase = $_POST['friend-base'];
		$friendSelect = $_POST['friend-select'];//获取传递过来的是编辑还是添加的类型
		$firendId = $_POST['friend-id']; //为编辑时，传递过来为需要编辑的id；
		
		//获取传建的时间
		$friendDate = date('Y-m-d H:m:s');
		
		if($friendSelect == 'add'){
			$friendLinkAddSql = "insert into friend_link (ftitle, flink, fintroduction, fdate, ftype, fimage) values('$friendTitle', '$friendLink', '$friendIntroduction', '$friendDate', '$friendType', '$friendImg')";
		}
		if($friendSelect == 'edit'){
			$friendLinkAddSql = "update friend_link set ftitle = '$friendTitle', flink = '$friendLink', fintroduction = '$friendIntroduction', ftype = '$friendType', fimage = '$friendImg' where fid = '$firendId'";
			
		}
		
		$friendLinkAddSql_db = mysql_query($friendLinkAddSql);
		if($friendLinkAddSql_db){
			//如果有传递过来的base64信息
			if($friendBase && $friendImg){
				//设置上传的路径
				//根目录
				$rootPath = $_SERVER['DOCUMENT_ROOT'];
				$thePath = $rootPath . '/program/upload/friendlink/';
				
				//调用通用类中的上传文件柜
				$theUtil = new util();
				$savePicArray = $theUtil->fileUpload($thePath,$friendImg,$friendBase);
			}
			//组装数组
			$friendLinkArray = array(
				status => 200,
				msg => '友情链接插入成功',
				result =>'',
				picMig =>$savePicArray,
			);
		}else{
			$friendLinkArray = array(
				status => 400,
				msg => '友情链接插入失败',
				result =>''
			);				
		}
		//转成json返回给前端
		$friendLinkJson = json_encode($friendLinkArray);
		print_r($friendLinkJson);
	}
	
	//获取友情链接列表
	function friendLinkList(){
		$friendListSql = "select * from friend_link where 1 = 1";
		$friendListSql_db = mysql_query($friendListSql);
		$friendListArray = array();
		while($friendListSql_db_array = mysql_fetch_assoc($friendListSql_db)){
			$friendListArray[] = $friendListSql_db_array;			
		}	
		//print_r($friendListArray);
		//搭建返回数组
		$returnFriendListArray = array(
			status => 200,
			msg => '友情链接列表返回成功',
			result => $friendListArray,
		);
		//转换成json数据返回给前端
		$returnFriendListJson = json_encode($returnFriendListArray);
		print_r($returnFriendListJson);
	}
	
	//根据id获取友情链接的相关信息，用于编辑
	function getFriendLinkInfo(){
		$friendId = $_GET['friendId'];
		$friendLinkInfoSql = "select * from friend_link where fid = $friendId";
		$friendLinkInfoSql_db = mysql_query($friendLinkInfoSql);
		$friendLinkInfoArray = array();
		while($friendLinkInfoSql_db_array = mysql_fetch_assoc($friendLinkInfoSql_db)){
			$friendLinkInfoArray = $friendLinkInfoSql_db_array;
		}
		//组装前端返回数据
		$returnFriendLinkInfoArray = array(
			status => 200,
			msg => '友情链接详情返回成功',
			result => $friendLinkInfoArray
		);
		
		//将数组转换为json返回给前端
		$returnFriendLinkInfoJson = json_encode($returnFriendLinkInfoArray);
		print_r($returnFriendLinkInfoJson);
	}
	
	//删除友情链接
	function delFriendLink(){
		//获取需要删除的id
		$theFriendLinkId = $_GET['friendLinkId'];
		$delFriendLinkSql = "delete from friend_link where fid = $theFriendLinkId";
		$delFriendLinkSql_db = mysql_query($delFriendLinkSql);
		if($delFriendLinkSql_db){
			$returnDelFriendArray = array(
				status => 200,
				msg => '友情链接删除成功',
				result =>''
			);			
		}
		else{
			$returnDelFriendArray = array(
				status => 400,
				msg => '友情链接删除失败',
				result =>''
			);			
		}
		//将数组转换为json返回给前端
		$returnDelFriendJson = json_encode($returnDelFriendArray);
		print_r($returnDelFriendJson);
	}
	
	function theReturn($turl){
		if($turl == 'addFriendLink'){
			$this -> addFriendLink();
		}
		if($turl == 'friendLinkList'){
			$this->friendLinkList();			
		}
		if($turl == 'getFriendLinkInfo'){
			$this->getFriendLinkInfo();
		}
		if($turl == 'delFriendLink'){
			$this->delFriendLink();
		}		
	}
}