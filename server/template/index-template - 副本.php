<?php 
	//include_once('../system.mysql.int.php');
	include_once('../system.article.php');
	
	//查询网站的设置信息
	$getIndexSql = "select * from web_info where 1 = 1";
	$getIndexSql_db = mysql_query($getIndexSql);
	$getIndexArray = array();
	while($getIndexSql_db_array = mysql_fetch_assoc($getIndexSql_db)){
		$getIndexArray[] =  $getIndexSql_db_array;
	}	
	
	//$a = getCategoryArticle(1);
	//print_r($a);
	
	//调用文章类
	$theArticleUtil = new articleUtil();
		
	
?>
<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <title><?php echo $getIndexArray[0]['web_title'];?></title>
	<meta name="keywords" content="<?php echo $getIndexArray[0]['web_keyword']?>" />
	<meta name="description" content="<?php echo $getIndexArray[0]['web_short']?>" />
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
				<h1><?php echo  $getIndexArray[0]['web_name'];?></h1>
				<p>这是一个超大屏幕（Jumbotron）的实例。</p>
				<p><a class="btn btn-primary btn-lg" role="button">
				 学习更多</a>
				</p>
			</div>
		</div>
		<section>
			<div class="container">
				<?php include('c-search.php');?>
			</div>
		</section>
		
		<!--
		<section>
			<div class="container list-container">
				<div class="list-left col-md-12">
				<?php 
					$indexListSql = "select a.*,b.* from article as a left join category as b on a.category_id = b.cid  where article_status = 'public' order by aid DESC limit 0,10";
					
					$indexListSql_db = mysql_query($indexListSql);				
					$indexArray = array();	
					
					while($indexListSql_db_array = mysql_fetch_assoc($indexListSql_db)){
						$indexArray[] = $indexListSql_db_array;
					}
					print_r($indexArray);
					foreach($indexArray as $key => $value){
						//html渲染
						$listHtml .= '<div class="list-container-k row"><a href="http://'.$_SERVER['HTTP_HOST'].'/program/article/'.$value['categoryyw'].'/'.$value['aid'].'.html"><div class="list-container-k-left col-md-9"><div class="list-container-k-left-title"><h4>'.$value['title'].'</h4></div><div class="list-container-k-left-container">'.$value['article_short'].'</div></div><div class="list-container-k-right col-md-3"><img src="http://'.$_SERVER['HTTP_HOST'].'/program/upload/cover/'.$value['article_img'].'" class="img-responsive"></div><div class="clear"></div><hr/></a></div>';  				
					}
					echo $listHtml;
				?>
				</div>
				<div class="list-right col-md-3">
				
				</div>
				
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
							$hotArticleArray = $theArticleUtil->getCategoryArticle(0,8);
							//print_r($hotArticleArray);
							//遍历数组，将数组组装成html
							foreach($hotArticleArray as $key => $value){
								$theHtml .= '<div class="col-sm-6 col-md-3"><div class="thumbnail"><img src="./upload/cover/'.$value['article_img'].'" alt="通用的占位符缩略图"><h5>'.$value['title'].'</h5><div><span>分类:<a href="http://'.$_SERVER['HTTP_HOST'].'/program/article/'.$value['categoryyw'].'/'.$value['categoryyw'].'-1.html">'.$value['categoryname'].'</a></span><span>来源:<a href="http://'.$_SERVER['HTTP_HOST'].'/program/article/cover-page/'.$value['pid'].'.html">'.$value['article_cover'].'</a></span></div></div></div>';
							}
							echo $theHtml;
						?>
					</div>
				</div>
				<div class="clear"></div>
			</div>	
		</section>
		
		<section>
			<div class="container cinema-article">
				<div class="cinema-article-title">
					<div class="cinema-article-title-left col-md-2">
						<h3>电影解说</h3>
					</div>
					<div class="cinema-article-title-right col-md-10">
						
						<?php 
							//获取对应的分组目录,这里只需要下一层子集
							$cinemaCategoryArray = $theArticleUtil->getCategoryArray(1);
							
							//print_r($cinemaCategoryArray);
							//遍历数组获取对应的菜单渲染
							foreach($cinemaCategoryArray as $key => $value){
								$arrayHtml .= '<span style="margin:5px">'.$value['categoryname'].'</span>';
							}
							echo $arrayHtml;
						?>
						
					</div>
				</div>
				<div class="cinema-article-container">
				<div class="cinema-article-container col-md-12">
					<div class="row">
						<?php 
							//新建，避免重复上面数据
							$cinemaHtml = '';
							$hotArticleArray = $theArticleUtil->getCategoryArticle(1,8);
							//print_r($hotArticleArray);
							//遍历数组，将数组组装成html
							foreach($hotArticleArray as $key => $value){
								$cinemaHtml .= '<div class="col-sm-6 col-md-3"><div class="thumbnail"><img src="./upload/cover/'.$value['article_img'].'" alt="通用的占位符缩略图"><h5>'.$value['title'].'</h5><div><span>分类:<a href="http://'.$_SERVER['HTTP_HOST'].'/program/article/'.$value['categoryyw'].'/'.$value['categoryyw'].'-1.html">'.$value['categoryname'].'</a></span><span>来源:<a href="http://'.$_SERVER['HTTP_HOST'].'/program/article/cover-page/'.$value['pid'].'.html">'.$value['article_cover'].'</a></span></div></div></div>';
							}
							echo $cinemaHtml;
						?>
					</div>				
				</div>
			</div>
		</section>
		
		<section>
			<div class="container">
				<div class="cover-title row">
					<div class="cover-title-left col-md-2">
						<h3>封面列表</h3>
					</div>
					<div class="cover-title-right col-md-10">
						<a href="./article/cover-page/cover-list-1.html">更多封面</a>
					</div>
				</div>
				<ul class="row">
					<?php
						$theCoverArray = $theArticleUtil->findCoverListArray(4);
						//print_r($theCoverArray);
						//遍历数组搭建html
						foreach($theCoverArray as $key => $value){
							$coverHtml .= '<li class="col-md-3 cover-array-li"><div class="cover-array-li-img"><img class ="img-responsive" src="../../upload/user_cover/'.$value['cover_img'].'"><div class="cover-array-li-img-title">'.$value['title'].'</div></div></li>';						
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
	
	<script src="./js/index.js"></script>
  </body>
</html>