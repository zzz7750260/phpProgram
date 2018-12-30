<?php 
/////////////////////
//管理员管理
/////////////////////
	class theRole{	
		function addRole($theRoleName,$theRoleYw,$theRoleMs,$theRoleLmQx,$theRoleWzQx,$theRoleYhQx){			
			$roleAddSql = "insert into role (rolename,roleyw,rolems,rolelmqx,rolewzqx,roleyhqx) values ('$theRoleName','$theRoleYw','$theRoleMs','$theRoleLmQx','$theRoleWzQx','$theRoleYhQx')" ;
			$roleAddSql_db = mysql_query($roleAddSql);
			echo $roleAddSql_db;
			if($roleAddSql_db){
				$returnAddRoleInfoArray = array(
					status => 200,
					msg => '角色添加成功',
					result => ''
				);
				//echo '<script>alert("角色添加成功")</script>';				
			}
			else{
				$returnAddRoleInfoArray = array(
					status => 400,
					msg => '角色添加失败',
					result => ''
				);						
			}
			$returnAddRoleInfoJson = json_encode($returnAddRoleInfoArray);
			print_r($returnAddRoleInfoJson);
		}
		
		function listRole(){
			$listRoleSql = "select * from role";
			$listRoleSql_db = mysql_query($listRoleSql);
			$listRoleArray = array();
			while($listRoleSql_db_array = mysql_fetch_assoc($listRoleSql_db)){
				$listRoleArray[] = $listRoleSql_db_array;				
			}
			$jsonListRoleArray = json_encode($listRoleArray);
			print_r($jsonListRoleArray);
		}
		

		function delRole($theId){
			//echo "获取的id为".$theId;
			$delSql = "delete from role where rid = '$theId'";
			$delSql_db = mysql_query($delSql);
			if($delSql_db){			
				$delRoleArray = array("status"=>200,"msg"=>"角色删除成功","result"=>1);
				$delRoleJson = json_encode($delRoleArray); 
			}
			else{
				$delRoleArray = array("status"=>404,"msg"=>"角色删除失败","result"=>2);
				$delRoleJson = json_encode($delRoleArray);
			}
			print_r($delRoleJson);
			return $delRoleJson;
		}
		
		function editRole($theId){			
			$editRoleSql = "select * from role where rid = '$theId'";
			$editRoleSql_db = mysql_query($editRoleSql);
			$theEditRoleArray =  array();
			while($editRoleArray = mysql_fetch_assoc($editRoleSql_db)){
				$theEditRoleArray[] = $editRoleArray;		
			}
			if($theEditRoleArray){
				$editRoleArrayValue = array("status"=>200,"msg"=>"编辑角色获取角色成功","result"=>$theEditRoleArray);
			}
			else{
				$editRoleArrayValue = array("status"=>100,"msg"=>"编辑角色获取角色失败","result"=>2);				
			}
			
			$editRoleJson = json_encode($editRoleArrayValue);
			print_r($editRoleJson);
			
			
			return $editRoleJson;
		}
		
		function updataRole($theId,$theRoleName,$theRoleYw,$theRoleMs,$theRoleLmQx,$theRoleWzQx,$theRoleYhQx){
			$updataRoleSql = "update role set rolename = '$theRoleName', roleyw = '$theRoleYw', rolems = '$theRoleMs', 	rolelmqx ='$theRoleLmQx', rolewzqx = '$theRoleWzQx', roleyhqx = '$theRoleYhQx' where rid = $theId";
			
			$updataRoleSql_db = mysql_query($updataRoleSql);
			
		
			if($updataRoleSql_db){
				$updataArray = array("status"=>200,"msg"=>"角色更改成功","result"=>1);				
			}
			else{
				$updataArray = array("status"=>400,"msg"=>"角色更改失败","result"=>2);	
			}				
			//返回JSON数据		
			$updataJson = json_encode($updataArray);			
			print_r($updataJson);
			
			return $updataJson;
		}
		
		
		function addRoleMenu($theMpid,$theMenuname,$theMenuInfo,$theMenurole,$theMenuyw,$theMenuurl){
			$getType = $_POST['theType'];
			$getMenuId = $_POST['theMenuId'];
			if($getType == 'add'){
				$addRoleMenuSql = "insert into menu (mpid,menuname,menuinfo,menurole,menuyw,menuurl) values ('$theMpid','$theMenuname','$theMenuInfo','$theMenurole','$theMenuyw','$theMenuurl')";								
			}
			if($getType == 'update'){
				$addRoleMenuSql = "update menu set mpid = '$theMpid', menuname = '$theMenuname',menuinfo='$theMenuInfo',menurole = '$theMenurole', menuyw = '$theMenuyw', menuurl = '$theMenuurl' where mid = '$getMenuId'";									
			}

			$addRoleMenuSql_db = mysql_query($addRoleMenuSql);
			if($addRoleMenuSql_db){
				if($getType == 'add'){
					$addRoleMenuArray = array("status"=>200,"msg"=>"菜单插入成功","result"=>1);	
				}
				if($getType == 'update'){
					$addRoleMenuArray = array("status"=>200,"msg"=>"菜单更新成功","result"=>1);	
				}
			}
			else{
				$addRoleMenuArray = array("status"=>400,"msg"=>"菜单插入失败","result"=>2);				
			}
				$addRoleMenuJson = json_encode($addRoleMenuArray);	
				print_r($addRoleMenuJson);
				return $addRoleMenuJson;
		}
		
		function listRoleMenu($mpid){
			if(!$mpid){
				$mpid = 0;				
			}
			$listRoleMenuArray = array();
			$listRoleMenuSql = "select * from menu where mpid = '$mpid'";
			$listRoleMenuSql_db = mysql_query($listRoleMenuSql);
			$i = 0;
			while($listRoleMenuSql_db_array = mysql_fetch_assoc($listRoleMenuSql_db)){
				$listRoleMenuArray[$i] = $listRoleMenuSql_db_array;		
				$listRoleMenuArray[$i]['child'] = $this->listRoleMenu($listRoleMenuArray[$i]['mid']);
				$i++;
				//$listRoleMenuArray['child'] = $this->listRoleMenu($listRoleMenuArray['mid']);
			}
			//print_r($listRoleMenuArray);			
			//$listRoleMenuJson = json_encode($listRoleMenuArray);
			//print_r($listRoleMenuJson);
			return $listRoleMenuArray;	
		}
		
		//根据mid获取对应的菜单信息
		function getMenuInfo(){
			$theMenuId = $_GET['menu-id'];
			$theMenuInfoSql = "select * from menu where mid = '$theMenuId'";
			$theMenuInfoSql_db = mysql_query($theMenuInfoSql);
			if($theMenuInfoSql_db){
				$theMenuInfoSql_db_array = mysql_fetch_assoc($theMenuInfoSql_db);
				$returnMenuInfoArray = array(
					status => 200,
					msg => '菜单信息返回成功',
					result => $theMenuInfoSql_db_array,
				);
			}
			else{
				$returnMenuInfoArray = array(
					status => 400,
					msg => '菜单信息返回成功',
					result => '',
				);			
			}
			
			//将数组转换为json返回给前端
			$returnMenuInfoJson = json_encode($returnMenuInfoArray);
			print_r($returnMenuInfoJson);
		}
		
		function getListRoleMenu(){
			$getListRoleMenuSql = "select * from role";
			$getListRoleMenuSql_db = mysql_query($getListRoleMenuSql);
			$getListRoleMenuSqlArray = array();
			$i = 0;
			while($getListRoleMenuSql_db_array = mysql_fetch_assoc($getListRoleMenuSql_db)){
				$getListRoleMenuSqlArray[$i] = 	$getListRoleMenuSql_db_array;
				$i++;
			}
			$getListRoleMenuSqlJson = json_encode($getListRoleMenuSqlArray);
			print_r($getListRoleMenuSqlJson);
			return $getListRoleMenuSqlJson;
		}
		
		function getMenuFather(){
			$getMenuFatherSql = "select * from menu where mpid = 0";
			$getMenuFatherSql_db = mysql_query($getMenuFatherSql);
			$getMenuFatherSqlArray = array();
			$i = 0;
			while($getMenuFatherSql_db_array = mysql_fetch_assoc($getMenuFatherSql_db)){
				$getMenuFatherSqlArray[$i] = $getMenuFatherSql_db_array;
				$i++;
			}
			if($getMenuFatherSqlArray){
				$getMenuFatherSqlArrayBack = array("status"=>200,"msg"=>"菜单父类查询成功","result"=>$getMenuFatherSqlArray);			
			}
			else{
				$getMenuFatherSqlArrayBack = array("status"=>400,"msg"=>"菜单父类查询失败","result"=>2);			
			}
			
			$getMenuFatherSqlJsonBack = json_encode($getMenuFatherSqlArrayBack);
			
			print_r($getMenuFatherSqlJsonBack);	
			
			return $getMenuFatherSqlJsonBack;			
		}
		
		function delRoleMenu($theId){
			$delRoleMenuSql = "delete from menu where mid = '$theId'";
			$delRoleMenuSql_db = mysql_query($delRoleMenuSql);
			if(delRoleMenuSql_db){
				$delRoleMenuSql_db_array = array("status"=>200,"msg"=>"删除菜单成功","result"=>1);				
			}
			else{
				$delRoleMenuSql_db_array = array("status"=>400,"msg"=>"删除菜单失败","result"=>2);				
			}
			
			$delRoleMenuSql_db_json = json_encode($delRoleMenuSql_db_array);
			print_r($delRoleMenuSql_db_json);
			return $delRoleMenuSql_db_json;
		}
		
		
		/*前端返回对应的菜单数组*/
		function webMenuList(){
			$getMenuInfoArraySql = "select * from category where categorytype !='' and cpid = 0";
			$getMenuInfoArraySql_db = mysql_query($getMenuInfoArraySql);
			$getMenuInfoArray = array();
			while($getMenuInfoArraySql_db_array = mysql_fetch_assoc($getMenuInfoArraySql_db)){
				$getMenuInfoArray[] = $getMenuInfoArraySql_db_array ;
			}
			//遍历数组获取对应的子类
			foreach($getMenuInfoArray as $getMenuInfoKey => $getMenuInfoValue){
				$MenuChildListArray = $this->webMenuChildList($getMenuInfoValue['cid']);
				$getMenuInfoArray[$getMenuInfoKey]['childlist'] = $MenuChildListArray;
			}
			
			//组装返回前端数组
			$returnMenuListArray = array(
				status => 200,
				msg => "分类列表返回成功",
				result => $getMenuInfoArray,
			);
			
			$returnMenuListJson = json_encode($returnMenuListArray);
			
			print_r($returnMenuListJson);
		}
		
		//递归查询返回的子数组
		function webMenuChildList($fid){
			$getMenuChildInfoArray = array();
			$getMenuChildInfoArraySql = "select * from category where cpid = '$fid'";
			$getMenuChildInfoArraySql_db = mysql_query($getMenuChildInfoArraySql);
			while($getMenuChildInfoArraySql_db_array = mysql_fetch_assoc($getMenuChildInfoArraySql_db)){
				$getMenuChildInfoArray[] = $getMenuChildInfoArraySql_db_array;	
			}
			return $getMenuChildInfoArray;
		}
		
		function theRuturnRole($turl){
			if($turl == "addRole"){
				$theRoleName = $_POST['getRoleName'];
				echo "获取rolename".$theRoleName;
				$theRoleYw = $_POST['getRoleYw'];
				$theRoleMs = $_POST['getRoleMs'];
				$theRoleLmQx = $_POST['getRoleLmQx'];
				$theRoleWzQx = $_POST['getRoleWzQx'];
				$theRoleYhQx = $_POST['getRoleYhQx'];
				$this->addRole($theRoleName,$theRoleYw,$theRoleMs,$theRoleLmQx,$theRoleWzQx,$theRoleYhQx);				
			}
			
			if($turl == "listRole"){
				$this->listRole();
				
			}
			
			if($turl == "delRole"){
				$theId = $_GET['getId'];
				//echo "获取的id为".$theId;
				$this->delRole($theId);			
			}
			
			if($turl == "editRole"){
				$theId = $_GET["getId"];
				$this->editRole($theId);
			}
			
			if($turl == "updateRole"){
				$theId = $_POST['postId'];
				$theRoleName = $_POST['postrolename'];
				$theRoleYw = $_POST['postroleyw'];
				$theRoleMs = $_POST['postrolems'];
				$theRoleLmQx = $_POST['postrolelmqx'];
				$theRoleWzQx = $_POST['postrolewzqx'];
				$theRoleYhQx = $_POST['postroleyhqx'];
				
				$this->updataRole($theId,$theRoleName,$theRoleYw,$theRoleMs,$theRoleLmQx,$theRoleWzQx,$theRoleYhQx);			
			}
			
			if($turl == "addRoleMenu"){
				$theMpid = $_POST['postMpid'];				
				$theMenuname = $_POST['postMenuname'];
				$theMenuInfo = $_POST['postMenuInfo'];
				$theMenurole = $_POST['postMenurole'];
				$theMenuyw = $_POST['postMenuyw'];
				$theMenuurl	= $_POST['postMenuurl'];
				echo $theMpid;
				echo $theMenuname;
				echo $theMenurole;
				echo $theMenuyw;
				echo $theMenuurl;
				$this->addRoleMenu($theMpid,$theMenuname,$theMenuInfo,$theMenurole,$theMenuyw,$theMenuurl);								
			}
			
			if($turl == "listRoleMenu"){
				$mpid = $_GET['getMpid'];
				$listMenuList = $this->listRoleMenu($mpid);
				$listMenuListJson = json_encode($listMenuList);
				print_r($listMenuListJson);
			}
			
			if($turl == "getListRoleMenu"){
				$this->getListRoleMenu();
			}
			
			if($turl == "getListRoleMenuFather"){
				$this->getMenuFather();			
			}
			
			if($turl == "delRoleMenu"){
				$delmid = $_GET['getDelMid'];
				$this->delRoleMenu($delmid);				
			}
			
			if($turl == 'getMenuInfo'){
				$this->getMenuInfo();
			}
			if($turl == 'webMenuList'){
				$this->webMenuList();
			}
		}	
	}
?>