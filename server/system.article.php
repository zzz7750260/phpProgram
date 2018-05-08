<?php 
include_once('system.mysql.int.php');
class articleUtil{	
	//随机获取文章数
	function getRandArticleList($categoryId,$limitNum){
		$listSql = "select a.* from article as a join (select round(rand()*(select max(aid) from article)) as aid) as b on a.aid >= b.aid where category_id = '$categoryId' order by a.aid ASC limit $limitNum";
		
		//$listSql = "SELECT * FROM `table`  AS t1 JOIN (SELECT ROUND(RAND() * (SELECT MAX(id) FROM `table`)) AS id) AS t2 WHERE t1.id >= t2.id";
		
		$listSql_db = mysql_query($listSql);
		$listArray = array();
		while($listSql_db_array = mysql_fetch_assoc($listSql_db)){
			$listArray[] = $listSql_db_array; 
		} 
		//print_r($listArray);
		return $listArray;
	}	
}
//$articleUtil = new articleUtil;
//$articleUtil->getRandArticleList(5,2);