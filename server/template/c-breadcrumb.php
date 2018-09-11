<?php 
	include_once('../system.article.php');
	include_once('../system.util.php');
	$commonUtil = new util();
	//根据当前的id获取对应的分类信息
	
	$theArticleUtil = new articleUtil();
	$theCategoryInfoBreadcrumb = $theArticleUtil->categoryDetail($value['category_id']);	
	
	//根据文章的信息来获取父类对应的信息
	
	//echo $value['category_id'];
	
	//print_r($theCategoryInfoBreadcrumb);
	$theBreadcrumbArray = $theArticleUtil->getCategoryInfoArray($value['category_id']);
	//对数组进行顺序调整
	sort($theBreadcrumbArray);
	//print_r($theBreadcrumbArray);
	
	//根据递归方法获取对应的面板屑数组
	/*
	function getCategoryInfoArray($fid,&$categoryArray = ''){
		if($fid){
			//根据当前分类的父类信息获取该父类的详情	
			$theArticleUtil = new articleUtil();//调用必须要放在内部
			$fatherCategoryInfo = $theArticleUtil->categoryDetail($fid);	
			//将父类信息存放到数组中
			$categoryArray[] = $fatherCategoryInfo;
			//再根据父类的父类进行递归			
			getCategoryInfoArray($fatherCategoryInfo['cpid'],$categoryArray);
			
			//返回得到的数组	
		}

		return $categoryArray;
	}
	*/
	
	
	
?>

	<ol class="breadcrumb">
	<li>
		<a href="<?php $indexUrl = ''.$commonUtil->isHttpsCheckSelect().'//'.$_SERVER['HTTP_HOST']; echo $indexUrl;?>">首页</a>
	</li>
	
	<?php 
		$breadcrumbLiHtml = '';
		//遍历数组组成面板屑
		foreach($theBreadcrumbArray as $key => $breadcrumbValue){
			$breadcrumbLiHtml .= '<li><a href="';
			//$breadcrumbValue[cpid] 为0 时为最顶层分类，链接也要为汇总链接,否则为普通分类链接
			if($breadcrumbValue[cpid] == 0){
				$breadcrumbLiHtml .= ''.$commonUtil->isHttpsCheckSelect().'//'.$_SERVER['HTTP_HOST'].'/article/'.$breadcrumbValue['categoryyw'].'/'.$breadcrumbValue['categoryyw'].'-list-1.html';
					
			}
			else{
				$breadcrumbLiHtml .= ''.$commonUtil->isHttpsCheckSelect().'//'.$_SERVER['HTTP_HOST'].'/article/'.$breadcrumbValue['categoryyw'].'/'.$breadcrumbValue['categoryyw'].'-1.html';			
			}
			$breadcrumbLiHtml .= '">'.$breadcrumbValue['categoryname'].'</a></li>';
			
			
		}
		//为了防止重复，在输出完后最好赋予空值
		echo $breadcrumbLiHtml;
	?>
	<li><?php echo $value['title']?></li>;

	</ol>