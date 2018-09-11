<?php 
include_once('../system.mysql.int.php');
//获取所有的菜单栏
include_once('../system.util.php');
//$commonUtil = new util();
//定义分类的生活路径
define('LISTPATH',dirname(dirname(dirname(__FILE__))));
//echo LISTPATH."<br/>";
//echo $_SERVER['HTTP_HOST'];


function getMenu(){
	$commonUtil = new util();
	//echo '内部的:'.LISTPATH;
	$menuSql = "select * from category where 1 = 1";
	$menuSql_db = mysql_query($menuSql);
	$menuArray = array();
	while($menuSql_db_array = mysql_fetch_assoc($menuSql_db)){
		$menuArray[] = $menuSql_db_array;
	}
	//print_r($menuArray);
	//遍历数组找出父类分类,并进行html组装
	$menuHtml .= '<nav class="navbar navbar-default" role="navigation"><div class="container-fluid"><div class="navbar-header"><button type="button" class="navbar-toggle" data-toggle="collapse"
data-target="#example-navbar-collapse"><span class="sr-only">切换导航</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button><a class="navbar-brand" href="'.$commonUtil->isHttpsCheckSelect().'//'.$_SERVER['HTTP_HOST'].'">课间十分钟</a></div><div class="collapse navbar-collapse" id="example-navbar-collapse"><ul class="nav navbar-nav">'; 
	foreach($menuArray as $key => $value){
		if($value['cpid'] == 0){
			$childH = getChildMenu($menuArray,$value['cid']);
			$menuHtml .= '<li><a href="'.$commonUtil->isHttpsCheckSelect().'//'.$_SERVER['HTTP_HOST'].'/article/'.$value['categoryyw'].'/'.$value['categoryyw'].'-0.html" class="dropdown-toggle" data-toggle="dropdown">'.$value['categoryname'].'<b class="caret"></b>'.$childH.'</a></li>';	
				//$menuHtml .= '<li><a href="../../article/'.$value['categoryyw'].'/'.$value['categoryyw'].'-0.html" >'.$value['categoryname'].'</a></li>';					
		}
	}
	$menuHtml .="</div></ul></nav>";
	echo $menuHtml;
}


//循环子类目录
function getChildMenu($fArr,$fid){
	$commonUtil = new util();
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
				$cHtml .='<li><a href="'.$commonUtil->isHttpsCheckSelect().'//'.$_SERVER['HTTP_HOST'].'/article/'.$value['categoryyw'].'/'.$value['categoryyw'].'-1.html">'.$value['categoryname'].'</a></li>';
			}		
		}
	}  
	//循环数组，寻找符合的子类

	$cHtml .='</ul>';
	
	//echo $cHtml;
	return $cHtml;
}

