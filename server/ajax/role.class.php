<?php 
	class theRole{
		function addRole($theRoleName,$theRoleYw,$theRoleMs,$theRoleLmQx,$theRoleWzQx,$theRoleYhQx){			
			$roleAddSql = "insert into role (rolename,roleyw,rolems,rolelmqx,rolewzqx,roleyhqx) values ('$theRoleName','$theRoleYw','$theRoleMs','$theRoleLmQx','$theRoleWzQx','$theRoleYhQx')" ;
			$roleAddSql_db = mysql_query($roleAddSql);
			echo $roleAddSql_db;
			if($roleAddSql_db){
				echo '<script>alert("角色添加成功")</script>';				
			}
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
				$editRoleJson = array("status"=>200,"msg"=>"编辑角色获取角色成功","result"=>1);
			}
			else{
				$editRoleJson = array("status"=>100,"msg"=>"编辑角色获取角色失败","result"=>2);				
			}
			print_r($editRoleJson);
			return $editRoleJson;
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
		}
	}
?>