<?php 
class theCategory{
	function listCategory(){
		//这里不用递归的方法循环调用数据库，而是通过数组的方法一次
		$listCategorySql = "select * from category";
		$listCategorySql_db = mysql_query($listCategorySql);
		$listCategoryArray = array();
		while($listCategorySql_db_array = mysql_fetch_assoc($listCategorySql_db)){			
			$listCategoryArray[] = $listCategorySql_db_array;					
		}
		$this->listArray = $listCategoryArray;		
		//$this->getListCategory();
		//echo "外部调用";
		$listCategoryJson = json_encode($this->getListCategory());
		print_r($listCategoryJson);
		//print_r($this->getListCategory());
		return $listCategoryArray;	
	}
	
	//无限递归的方法将菜单数组整理成合适的数组
	function getListCategory($pid=0,&$result=array(),$deep=0){
		$deep+=2;
		//echo "aaaa".$deep.'<br/>';
		$theListArray = $this->listArray;
		//echo "这个是this的数组：".$theListArray;	
		//print_r($theListArray);
		//print_r($result);
		foreach($theListArray as $keyListArray => $value){
			if($value['cpid'] == $pid){
				$value['categoryname'] = "|".str_repeat("-", $deep).$value['categoryname'];
				$result[] = $value;
				$this->getListCategory($value['cid'],$result,$deep);
			}			
		}
		return $result;		
	}
	
	function theReturn($turl){
		if($turl == "listCategory"){
			$this->listCategory();			
		}	
	}		
}