<?php 
class theCategory{
	function listCategory(){
		//添加分类的选择类型
		$theCategoryType = $_GET['the-category-type'];
		
		//这里不用递归的方法循环调用数据库，而是通过数组的方法一次
		$listCategorySql = "select * from category where categorytype = '$theCategoryType'";
		$listCategorySql_db = mysql_query($listCategorySql);
		$listCategoryArray = array();
		while($listCategorySql_db_array = mysql_fetch_assoc($listCategorySql_db)){			
			$listCategoryArray[] = $listCategorySql_db_array;					
		}
		$this->listArray = $listCategoryArray;		
		//$this->getListCategory();
		//echo "外部调用";
		
		//组装返回的array数据
		//$listCategoryArrayReturn = array(
		//	'status' => 200,
		//	'msg' => '分类列表返回成功',
		//	'result' => array(
		//		'articleNum' =>
		//	)
		//);
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
	
	//在菜单管理中对菜单进行排列
	function categoryListPageArray(){
		//对分类进行输出并组成数组
		$categoryListSql = "select * from category where 1 = 1";
		$categoryListSql_db = mysql_query($categoryListSql);
		$categoryListArray = array();
		while($categoryListSql_db_array = mysql_fetch_assoc($categoryListSql_db)){
			$categoryListArray[] = $categoryListSql_db_array;
		}
		//将取到的数组放在全局的控制变量中
		$this->categoryListPageSj = $categoryListArray;
		//将新组的数组以json数据返回
		$categoryListJson = json_encode($this->dgCategoryList());
		print_r($categoryListJson);	
		return $categoryListJson;	
	}
	
	//对全局得到的数组进行递归分类
	function dgCategoryList($pid=0,&$listResult = array()){
		//echo "获取数组";
		//print_r($this->categoryListPageSj);
		$getDgCategoryListArray = $this->categoryListPageSj;
		//print_r("========aaaArray===========");
		//print_r($getDgCategoryListArray);
		foreach($getDgCategoryListArray as $keyDgCategoryListArray => $value){
			if($value['cpid'] == $pid){
				$listResult[] = $value;
				//print_r("=============循环的================");
				$this->dgCategoryList($value['cid'],$listResult);
				//print_r($listResult);
			}		
		}
		return $listResult;		
	}
	
	//插入分类信息
	function addCategory(){
		//获取对应的信息
		$theCategoryName = $_POST['categoryName'];
		$theCategoryNameYw = $_POST['categoryNameYw'];
		$theCategoryMs = $_POST['categoryMs'];
		$theCategorySelect = $_POST['category_select'];
		$theCategoryType = $_POST['category_type'];
		
		//获取存储的类型
		$saveType = $_POST['save_type'];
		
		//为存储
		if($saveType == 'save'){
			//判断分类是否存在
			$selectCategorySql = "select * from category where categoryname = '$theCategoryName' or categoryyw = '$theCategoryNameYw'";
			$selectCategorySql_db = mysql_query($selectCategorySql);
			$selectCategorySql_db_num = mysql_fetch_assoc($selectCategorySql_db);
			if($selectCategorySql_db_num != 0){
				$returnCategoryArray = array(
					status => 500,
					msg => '该分类名或分类英文已经存在',
					result => ''			
				);
			}else{
				$saveCategorySql = "insert into category (cpid, categoryname, categoryyw, categoryms, categorytype) values ('$theCategorySelect', '$theCategoryName', '$theCategoryNameYw', '$theCategoryMs', '$theCategoryType')";
				$saveCategorySql_db = mysql_query($saveCategorySql);
				
				if($saveCategorySql_db){
					$returnCategoryArray = array(
						status => 200,
						msg => '分类存储成功',
						result => ''			
					);		
				}
				else{
					$returnCategoryArray = array(
						status => 400,
						msg => '分类存储失败',
						result => ''			
					);					
				}
			}
			$returnCategoryJson = json_encode($returnCategoryArray);
			print_r($returnCategoryJson);
		}
	}
	
	//根据id获取对应的分类
	function getCategoryInfo(){
		$theCategoryId = $_GET['the-category-id'];
		
		$theCategoryInfoSql = "select * from category where cid = '$theCategoryId'";
		$theCategoryInfoSql_db = mysql_query($theCategoryInfoSql);
		
		if($theCategoryInfoSql_db){
			$theCategoryInfoSql_db_array = mysql_fetch_assoc($theCategoryInfoSql_db);
			$returnCategoryArray = array(
				status => 200,
				msg => "根据分类'$theCategoryId'返回对应分类信息",
				result => $theCategoryInfoSql_db_array,
			);
		}
		else{
			$returnCategoryArray = array(
				status => 400,
				msg => "分类信息返回失败",
				result =>'',
			);					
		}
		
		$returnCategoryJson = json_encode($returnCategoryArray);
		print_r($returnCategoryJson);
		
	}
	
	function theReturn($turl){
		if($turl == "listCategory"){
			$this->listCategory();			
		}
		if($turl == "pageListCategory"){
			$this->categoryListPageArray();
		}
		if($turl == "addCategory"){
			$this->addCategory();
		}
		if($turl == "getCategoryInfo"){
			$this->getCategoryInfo();
		}
	}		
}