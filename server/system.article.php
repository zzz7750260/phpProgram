<?php 
include_once('system.mysql.int.php');
class articleUtil{	
	//随机获取文章数
	function getRandArticleList($categoryId,$limitNum,$isRand = 'rand'){
		
		//默认为获取随机，可进行设置
		if($isRand = 'rand'){
			$listSql = "select a.* from article as a join (select round(rand()*(select max(aid) from article)) as aid) as b on a.aid >= b.aid where category_id = '$categoryId' order by a.aid ASC limit $limitNum";
		}
		if($isRand == 'common'){
			$listSql = "select * from article where category_id = '$categoryId' order by ASC limit $limitNum";
			
		}
		//$listSql = "SELECT * FROM `table`  AS t1 JOIN (SELECT ROUND(RAND() * (SELECT MAX(id) FROM `table`)) AS id) AS t2 WHERE t1.id >= t2.id";
		
		$listSql_db = mysql_query($listSql);
		$listArray = array();
		while($listSql_db_array = mysql_fetch_assoc($listSql_db)){
			$listArray[] = $listSql_db_array; 
		} 
		//print_r($listArray);
		return $listArray;
	}

		//公共类：根据父分类名称获取子分类
	//$catFatherId:为父类id；
	//$theParameter:为查询的类型
	function getCategoryArray($catFatherId,$theParameter = 'all'){
		//根据父类id获取对应的分类集
		//当$theParameter为all时，查询的是每项的所有值，为categoryyw时查询的只是categoryyw，用于查询获取对应的文章
		
		if($theParameter == 'all'){
			$theCategorySql = "select * from category where cpid = '$catFatherId'";
		}
		
		if($theParameter == 'categoryyw'){
			$theCategorySql = "select categoryyw from category where cpid = '$catFatherId'";
		}
		
		$theCategorySql_db = mysql_query($theCategorySql);
		$theCategoryArray = array();
		while($theCategorySql_db_array = mysql_fetch_assoc($theCategorySql_db)){
			$theCategoryArray[] = $theCategorySql_db_array;			
		}
		return $theCategoryArray;
	}
	
	//公共类：根据对应的分类获取相关的文章
	//$fCategory：分类的选择
	//
	function getCategoryArticle($fCategory,$num = 6){
		//获取对应的分类集
		$getTheCaregoryArray = $this->findCategoryChilrenArray($fCategory,'article');
		print_r($getTheCaregoryArray);
		
		//查询相关的文章集
		//$theCategorySql = "select * from article where category_id in (select cid from category where cpid = '$fCategory')";
		
		//将获取到的集合数组转成字符串
		$getTheCaregoryString = implode(',' , $getTheCaregoryArray);
		echo $getTheCaregoryString;
		
		$theCategorySql = "select a.*,b.*,c.* from article as a join category as b on a.category_id = b.cid join page as c on a.article_cover = c.title where a.category_id in ($getTheCaregoryString) order by aid DESC limit 0 , $num";
		$theCategorySql_db = mysql_query($theCategorySql);
		$theCategoryArticle = array();
		while($theCategorySql_db_array = mysql_fetch_assoc($theCategorySql_db)){
			$theCategoryArticle[] = $theCategorySql_db_array;
		}
		//print_r($theCategoryArticle);
		return $theCategoryArticle;
	}
	
	//公共类函数：返回所以分类值
	function findAllCategory(){
		$findCategorySql = "select * from category where 1 = 1";
		$findCategorySql_db = mysql_query($findCategorySql);
		$findCategoryArray = array();
		while($findCategorySql_db_array = mysql_fetch_assoc($findCategorySql_db)){
			$findCategoryArray[] = $findCategorySql_db_array;
		}	
		return $findCategoryArray;
	}
	
	//公共类函数：获取该分类下的所有子类
	//$theCate：为当前选择的分类
	//$resultArray：查询的所有分类集合
	//$theType：为返回的类型，article用于返回文章所用，category用于返回分类所用
	function findCategoryChilrenArray($theCate,$theType,&$resultArray=''){
		//获取所有的分类
		$theCaregoryArray = $this->findAllCategory();	
		//print_r($theCaregoryArray);
		//遍历数组获取相关的数组
		foreach($theCaregoryArray as $key => $value){
			if($value['cpid'] == $theCate){
				if($theType == 'article'){
					$resultArray[] = $value['cid'];		
				}
				if($theType == 'category'){
					$resultArray[] = $value;				
				}
				$this->findCategoryChilrenArray($value['cid'],$theType,$resultArray);
			}			
		}
		return $resultArray;
	}	
	
	//公共类：查询封面
	//$theNum：查询的数量
	function findCoverListArray($theNum){
		//查询为随机查询
		//多条查询会不适用
		//$findCoverSql = "select t1.* from page as t1 join (select round(rand()*((select max(pid) from page)-(select min(pid) from page))+(select min(pid) from page)) as pid) as t2 where t1.pid >=t2.pid order by pid limit 0,$theNum";
		$findCoverSql = "select * from page where pid >= ((select max(pid) from page)-(select min(pid) from page))*rand() + (select min(pid) from page) limit 0,$theNum";
		
		$findCoverSql_db = mysql_query($findCoverSql);
		$findCoverArray = array();
		while($findCoverSql_db_array = mysql_fetch_assoc($findCoverSql_db)){
			$findCoverArray[] = $findCoverSql_db_array;
		}
		return $findCoverArray;
	}
}
//$articleUtil = new articleUtil;
//$articleUtil->getRandArticleList(5,2);