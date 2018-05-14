<?php 
include_once('../system.mysql.int.php');
//获取所有的菜单栏

//定义分类的生活路径
define('LISTPATH',dirname(dirname(dirname(__FILE__))));
echo LISTPATH."<br/>";
echo $_SERVER['HTTP_HOST'];


function getMenu(){
	//echo '内部的:'.LISTPATH;
	$menuSql = "select * from category where 1 = 1";
	$menuSql_db = mysql_query($menuSql);
	$menuArray = array();
	while($menuSql_db_array = mysql_fetch_assoc($menuSql_db)){
		$menuArray[] = $menuSql_db_array;
	}
	//print_r($menuArray);
	//遍历数组找出父类分类,并进行html组装
	$menuHtml .= '<nav class="navbar navbar-default" role="navigation"><div class="container-fluid"><div class="navbar-header"><a class="navbar-brand" href="#">课间十分钟</a></div><div><ul class="nav navbar-nav">'; 
	foreach($menuArray as $key => $value){
		if($value['cpid'] == 0){
			$childH = getChildMenu($menuArray,$value['cid']);
			$menuHtml .= '<li><a href="http://'.$_SERVER['HTTP_HOST'].'/program/article/'.$value['categoryyw'].'/'.$value['categoryyw'].'-0.html" class="dropdown-toggle" data-toggle="dropdown">'.$value['categoryname'].'<b class="caret"></b>'.$childH.'</a></li>';	
				//$menuHtml .= '<li><a href="../../article/'.$value['categoryyw'].'/'.$value['categoryyw'].'-0.html" >'.$value['categoryname'].'</a></li>';					
		}
	}
	$menuHtml .="</div></ul></nav>";
	echo $menuHtml;
}


//循环子类目录
function getChildMenu($fArr,$fid){
	//echo "======================";
	//print_r($fArr);
	//echo is_array($fArr)."xxxx";
	//echo $fid.'==='; 
	//组件子类的html
	$cHtml .='<ul class="dropdown-menu">';	
	
	if(is_array($fArr) && !empty($fArr)){    
		foreach($fArr as $key => $value){
			//echo "分类:".$value['cpid'];
			if($value['cpid'] == $fid){
				$cHtml .='<li><a href="http://'.$_SERVER['HTTP_HOST'].'/program/article/'.$value['categoryyw'].'/'.$value['categoryyw'].'-0.html">'.$value['categoryname'].'</a></li>';
			}		
		}
	}  
	//循环数组，寻找符合的子类

	$cHtml .='</ul>';
	
	//echo $cHtml;
	return $cHtml;
}


