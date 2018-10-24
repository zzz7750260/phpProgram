<?php 
include_once('system.mysql.int.php');
class articleUtil{	
	//随机获取文章数
	function getRandArticleList($categoryId=0,$limitNum,$isRand = 'rand'){
		
		//默认为获取随机，可进行设置
		if($isRand = 'rand'){
			$listSql = "select a.* from article as a join (select round(rand()*(select max(aid) from article)) as aid) as b on a.aid >= b.aid where if($categoryId = 0,1=1,category_id = '$categoryId') order by a.aid ASC limit 0,$limitNum";
		}
		if($isRand == 'common'){
			$listSql = "select * from article where category_id = '$categoryId' order by ASC limit 0,$limitNum";
			
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
	//$num：查询文章的数量, 当为0时为无限数量
	function getCategoryArticle($fCategory,$num = 6,$isRand="list"){
		//获取对应的分类集(这个没包含父类)
		//$getTheCaregoryArray = $this->findCategoryChilrenArray($fCategory,'article');
		
		//这个包含父类
		$getTheCaregoryArray = $this->findCategoryFatherChilrenArray($fCategory,'article');
		//查询相关的文章集
		//print_r($getTheCaregoryArray);
		
		//$theCategorySql = "select * from article where category_id in (select cid from category where cpid = '$fCategory')";
		
		//将获取到的集合数组转成字符串
		$getTheCaregoryString = implode(',' , $getTheCaregoryArray);
		//echo $getTheCaregoryString;
		
		
		
		if($isRand == "list"){
			$theCategorySql = "select a.*,b.*,c.* from article as a join category as b on a.category_id = b.cid join page as c on a.article_cover = c.ptitle where a.category_id in ($getTheCaregoryString) order by aid DESC limit 0 , $num";
			
			//
			if($num == 0){
				$theCategorySql = "select a.*,b.*,c.* from article as a join category as b on a.category_id = b.cid join page as c on a.article_cover = c.ptitle where a.category_id in ($getTheCaregoryString) order by aid DESC";
			}
		}
		if($isRand == "rand"){
			$theCategorySql = "select a.*,b.*,c.* from article as a join category as b on a.category_id = b.cid join page as c on a.article_cover = c.ptitle where a.aid >= ((select max(a.aid) from article)-(select min(a.aid) from article)) * rand() + (select min(a.aid) from article) and a.category_id in ($getTheCaregoryString) limit 0, $num";
			
		}
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
	
	//公共类：查找包含父子类的分类返回
	//$resultArray：查询的所有分类id集合
	//$theType：为返回的类型，article用于返回文章所用，category用于返回分类所用
	function findCategoryFatherChilrenArray($theCate,$theType,&$resultArray=''){
		//获取所有的分类
		$theCaregoryArray = $this->findAllCategory();	
		//print_r($theCaregoryArray);
		//遍历数组获取相关的数组
		if($theType == 'article'){
			$resultArray[] = $theCate;		
		}
		foreach($theCaregoryArray as $key => $value){
			if($value['cpid'] == $theCate){

				if($theType == 'category'){
					$resultArray[] = $value;				
				}
				$this->findCategoryFatherChilrenArray($value['cid'],$theType,$resultArray);
			}			
		}
		//print_r($resultArray);
		return $resultArray;
	}
	
	
	//公共类：查询封面
	//$theNum：查询的数量
	//$cat：查询对应的分类
	function findCoverListArray($theNum,$cat=0){
		//查询为随机查询
		//多条查询会不适用
		//$findCoverSql = "select t1.* from page as t1 join (select round(rand()*((select max(pid) from page)-(select min(pid) from page))+(select min(pid) from page)) as pid) as t2 where t1.pid >=t2.pid order by pid limit 0,$theNum";
		$findCoverSql = "select * from page where pid >= ((select max(pid) from page)-(select min(pid) from page))*rand() + (select min(pid) from page) and if($cat = 0,1=1,cover_category = $cat) limit 0,$theNum ";
		
		$findCoverSql_db = mysql_query($findCoverSql);
		$findCoverArray = array();
		while($findCoverSql_db_array = mysql_fetch_assoc($findCoverSql_db)){
			$findCoverArray[] = $findCoverSql_db_array;
		}
		return $findCoverArray;
	}
	
	//公共类：返回对应分类的详细信息
	//$categoryId,需要差选的分类ID 
	function categoryDetail($categoryId){
		$theCategorySql = "select * from category where cid = $categoryId";
		$theCategorySql_db = mysql_query($theCategorySql);
		$theCategoryArray = array();
		while($theCategorySql_db_array = mysql_fetch_assoc($theCategorySql_db)){
			$theCategoryArray = $theCategorySql_db_array;
		}
		return $theCategoryArray;
	}
	
	//公共类：获取该category_id下的分类链，用于生成面板屑
	//根据递归方法获取对应的面板屑数组
	function getCategoryInfoArray($fid,&$categoryArray = ''){
		if($fid){
			//根据当前分类的父类信息获取该父类的详情	
			//$theArticleUtil = new articleUtil();//调用必须要放在内部
			$fatherCategoryInfo = $this->categoryDetail($fid);	
			//将父类信息存放到数组中
			$categoryArray[] = $fatherCategoryInfo;
			//再根据父类的父类进行递归			
			$this->getCategoryInfoArray($fatherCategoryInfo['cpid'],$categoryArray);
			
			//返回得到的数组	
		}

		return $categoryArray;
	}
	
	//文章公共类，将标签规范化并转化为数组
	//$theTag:为输入的标签
	function tagChangeArray($theTag){
		//设定转化的正则规范,查找逗号，空格等
		$theRex = "/(\n)|(\s)|(\t)|(\')|(')|(，)/";
		//将特殊符号改变为,
		$theResult = preg_replace($theRex,',',$theTag);
		//将$theResult分割为数组进行返回
		$theArray = explode(',',$theResult);
		return $theArray;
	}
	
	//文章公共类：根据父类返回关键词
	//fid:为父类id
	function returnTheKeyWord($fid,$keyWord){
		//获取父类的相关信息
		$theCategoryInfo = $this->categoryDetail($fid);
		switch($theCategoryInfo['categoryname']){
			case '电影':
				$returnKeyWord = $keyWord.'影评,'.$keyWord.'剧情介绍,'.$keyWord.'解说';
				break;
			case '美食':
				$returnKeyWord = $keyWord.'做法,'.$keyWord.'制作步骤,'.'怎么做'.$keyWord;
				break;
			default: 
				$returnKeyWord = $keyWord;
		}
		return $returnKeyWord;
	}
	
}
//$articleUtil = new articleUtil;
//$articleUtil->getRandArticleList(5,2);