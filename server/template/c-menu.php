<?php 
include_once('../system.mysql.int.php');
//获取所有的菜单栏
function getMenu(){
	$menuSql = "select * from category where 1 = 1";
	$menuSql_db = mysql_query($menuSql);
	$menuArray = array();
	while($menuSql_db_array = mysql_fetch_assoc($menuSql_db)){
		$menuArray[] = $menuSql_db_array;
	}
	//print_r($menuArray);
	//遍历数组找出父类分类,并进行html组装
	$menuHtml .= '<nav class="navbar navbar-default" role="navigation"><div class="container-fluid"><div class="navbar-header"><a class="navbar-brand" href="#">菜鸟教程</a></div><div><ul class="nav navbar-nav">'; 
	foreach($menuArray as $key => $value){
		if($value['cpid'] == 0){
			$menuHtml .= '<li><a href="#">'.$value['categoryname'].'</a></li>';			
		}
	}
	$menuHtml .="</div></ul></nav>";
	echo $menuHtml;
}

getMenu();