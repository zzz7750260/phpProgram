<?php 
	//include_once('../system.mysql.int.php');
	include_once('../system.article.php');
	include_once('../system.util.php');
	$commonUtil = new util();	
		
	
	//$a = getCategoryArticle(1);
	//print_r($a);
	
	//调用文章类
	$theArticleUtil = new articleUtil();
	
	//$theCategoryFatherArray为父类传递过来的信息
	$categoryInfoArray = $theCategoryFatherArray;
	
	$categoryChildId = $categoryInfoArray['cid'];
	
	//获取各子类信息
	$fatherCategoryListSql = "select * from category where cpid = $categoryChildId";
	$fatherCategoryListSql_db = mysql_query($fatherCategoryListSql);
	$fatherCategoryListArray = array();
	while($fatherCategoryListSql_db_array = mysql_fetch_assoc($fatherCategoryListSql_db)){
		$fatherCategoryListArray[] = $fatherCategoryListSql_db_array; 
	}
	
		
?>
<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <title><?php echo '海量'.$categoryInfoArray['categoryname'].'视频在线观看-'.$categoryInfoArray['categoryname'].'列表-课间十分钟';?></title>
	<meta name="keywords" content="<?php echo $categoryInfoArray['categoryname'].','.$categoryInfoArray['categoryname'].'视频,'.$categoryInfoArray['categoryname'].'列表,课间十分钟';?>" />
	<meta name="description" content="<?php echo $categoryInfoArray['categoryms'];?>" />
	<!--图标-->
	<link rel="shortcut icon" href="favicon.ico"/>
	<link rel="bookmark" href="favicon.ico"/>	
    <!-- Bootstrap -->
    <link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="./css/common.css" rel="stylesheet">
    <!-- HTML5 shim 和 Respond.js 是为了让 IE8 支持 HTML5 元素和媒体查询（media queries）功能 -->
    <!-- 警告：通过 file:// 协议（就是直接将 html 页面拖拽到浏览器中）访问页面时 Respond.js 不起作用 -->
    <!--[if lt IE 9]>
      <script src="https://cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
		<?php 
			include_once('c-menu.php');
			getMenu();
		?>
		<div class="jumbotron">
			<div class="container">
				<h2><?php echo $categoryInfoArray['categoryname'];?></h2>
				<p><?php echo $categoryInfoArray['categoryms'];?></p>
				<p>
					<?php include('c-search.php');?>
				</p>
			</div>
		</div>
		<!--
		<section>
			<div class="container">
				<?php include('c-search.php');?>
			</div>
		</section>
		-->

		
		<section>
			<div class="container hot-article">
				<div class="hot-article-title">
					<h3>最新视频</h3>
				</div>
				
				<div class="hot-article-container col-md-12">
					<div class="row">
						<?php 
							$hotArticleArray = $theArticleUtil->getCategoryArticle($categoryChildId,8);
							//print_r($hotArticleArray);
							//遍历数组，将数组组装成html
							foreach($hotArticleArray as $key => $value){
								$theHtml .= '<div class="col-sm-6 col-md-3"><div class="thumbnail img-k"><a href="'.$commonUtil->isHttpsCheckSelect().'//'.$_SERVER['HTTP_HOST'].'/article/'.$value['categoryyw'].'/show-'.$value['aid'].'.html"><img src="../../upload/cover/'.$value['article_img'].'" alt="'.$value['title'].'"><h5>'.$value['title'].'</h5></a><div class="hot-article-short"><p>'.substr($value['article_short'],0,96).'</p></div><div class="hot-article-category"><span>分类:<a href="'.$commonUtil->isHttpsCheckSelect().'//'.$_SERVER['HTTP_HOST'].'/article/'.$value['categoryyw'].'/'.$value['categoryyw'].'-1.html">'.$value['categoryname'].'</a></span><span>来源:<a href="'.$commonUtil->isHttpsCheckSelect().'//'.$_SERVER['HTTP_HOST'].'/article/cover-page/'.$value['pid'].'.html">'.$value['article_cover'].'</a></span></div></div></div>';
							}
							echo $theHtml;
						?>
					</div>
				</div>
				<div class="clear"></div>
			</div>	
		</section>
		
		<?php 
			//引入分类的内容模板，这样做能保证控制内容的灵活性
			//将获取到的父类分类信息赋值到内容模板中
			//$theCategorySelectChild = $fatherCategoryListArray[0];
			//引入内容模板
			//include('category-collect-part-template.php');
			
			//遍历数组，将各类分类的内容放入主页中
			foreach($fatherCategoryListArray as $fatherCategoryListKey => $fatherCategoryListValue){
				$theCategorySelectChild = $fatherCategoryListValue;	
				include('category-collect-part-template.php');
			}
		?>
		
		<?php 
			//加载边菜单
			//include("c-side-menu.php");
		?>		
		<section>
			<div class="container">
				<div class="cover-title row">
					<div class="cover-title-left col-md-2">
						<h3>封面列表</h3>
					</div>
					<div class="cover-title-right col-md-10">
						<a href="<?php $thePagePath = ''.$commonUtil->isHttpsCheckSelect().'//'.$_SERVER['HTTP_HOST'].'/article/cover-page/cover-list-1.html'; echo $thePagePath;?>">更多封面</a>
					</div>
				</div>
				<ul class="row">
					<?php
						$theCoverArray = $theArticleUtil->findCoverListArray(4,$categoryChildId);
						//print_r($theCoverArray);
						//遍历数组搭建html
						foreach($theCoverArray as $key => $value){
							$coverHtml .= '<li class="col-md-3 cover-array-li"><a href="'.$commonUtil->isHttpsCheckSelect().'//'.$_SERVER['HTTP_HOST'].'/article/cover-page/'.$value['pid'].'.html"><div class="cover-array-li-img"><img class ="img-responsive" src="../../upload/user_cover/'.$value['cover_img'].'"><div class="cover-array-li-img-title">'.$value['ptitle'].'</div></div></a></li>';						
						}
						echo $coverHtml;
					?>				
				</ul>

			</div>
		</section>
		
		<!--引入footer模板-->
		<?php 
			include('c-footer.php');
		?>
		
		
    <!-- jQuery (Bootstrap 的所有 JavaScript 插件都依赖 jQuery，所以必须放在前边) -->
    <script src="https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script>
    <!-- 加载 Bootstrap 的所有 JavaScript 插件。你也可以根据需要只加载单个插件。 -->
    <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	
	<script src="../../js/index.js"></script>
  </body>
</html>