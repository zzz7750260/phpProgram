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
		}
	}
?>