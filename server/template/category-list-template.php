<?php 
	//include_once('../system.mysql.int.php');
	//include_once('../system.article.php');
	
	//获取分类页面的id
	$getCategoryId = $theCategoryId;
	
	//获取传递过来的文章信息
	$hotArticleArray = array();
	//print_r($categoryNumArray);
	
	//$hotArticleArray = $categoryNumArray;
	
	$categoryInfoSql = "select * from category where cid = $getCategoryId";
	$categoryInfoSql_db = mysql_query($categoryInfoSql);
	$categoryInfoArray = array();
	while($categoryInfoSql_db_array = mysql_fetch_assoc($categoryInfoSql_db)){
		$categoryInfoArray = $categoryInfoSql_db_array;
	}	
	//print_r($categoryInfoArray);
	
	
	$theArticleUtil = new articleUtil();
		
	
	
?>
<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <title><?php echo "海量" .$categoryInfoArray['categoryname'] . '在线观看-' . $categoryInfoArray['categoryname'] .'列表'.$p.'-课间十分钟' ?></title>
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
				<?php
					//根据父类获取子类
					$menuCategoryArray = $theArticleUtil->getCategoryArray($theCategoryId);
					//print_r($menuCategoryArray);
					$menuHtml = $categoryInfoArray['categoryname'] . ':';
					//循环子分类菜单
					foreach($menuCategoryArray as $menuKey => $menuValue){
						$menuHtml .= '<a href="http://'.$_SERVER['HTTP_HOST'].'/program/article/'.$menuValue['categoryyw'].'/'.$menuValue['categoryyw'].'-0.html"><span>'.$menuValue['categoryname'].'</span></a>';						
					}
					echo $menuHtml;				
				?>
			
			</div>
		
		</section>
		
		<section>
			<div class="container">
				<?php include('c-search.php');?>
			</div>
		</section>
		
		
		<section>
			<div class="container hot-article">
				<div class="hot-article-title">
					<h3>最新视频</h3>
				</div>
				
				<div class="hot-article-container col-md-12">
					<div class="row">
						<?php 
							//$hotArticleArray = $theArticleUtil->getCategoryArticle(0,8);
							//print_r($hotArticleArray);
							//遍历数组，将数组组装成html
							foreach($categoryNumArray as $key => $value){
								$theHtml .= '<div class="col-sm-6 col-md-3"><div class="thumbnail"><img src="../../upload/cover/'.$value['article_img'].'" alt="通用的占位符缩略图"><h5>'.$value['title'].'</h5><div><span>分类:<a href="http://'.$_SERVER['HTTP_HOST'].'/program/article/'.$value['categoryyw'].'/'.$value['categoryyw'].'-1.html">'.$value['categoryname'].'</a></span><span>来源:<a href="http://'.$_SERVER['HTTP_HOST'].'/program/article/cover-page/'.$value['pid'].'.html">'.$value['article_cover'].'</a></span></div></div></div>';
							}
							echo $theHtml;
							
							//在输出完后要清空原理的数据，否则会照成数据重复
							$theHtml = '';
							
							//unset($hotArticleArray);
						?>
					</div>
				</div>
				<div class="clear"></div>
			</div>	
		</section>
		
		<section>
			<div>
				<ul class="pager">
					<?php 
						if($p == 1){
							$n = $p + 1;
							echo '<li><a href="./'.$categoryInfoArray['categoryyw'].'-list-'.$n.'.html">Next</a></li>';	
						}	
						else if($p == $pageNum){
							$s  = $p - 1;
							echo '<li><a href="./'.$categoryInfoArray['categoryyw'].'-list-'.$s.'.html">Previous</a></li>';							
						}
						else{
							$n = $p + 1;
							$s  = $p - 1;
							echo '<li><a href="./'.$categoryInfoArray['categoryyw'].'-list-'.$s.'.html">Previous</a></li><li><a href="./'.$categoryInfoArray['categoryyw'].'-list-'.$n.'.html">Next</a></li>';					
						}
						
					?>
					<li>
						<select>
							<?php 
								for($a=1;$a<$getPageNum+1;$a++){
									echo '<option>第'.$a.'页</option>';
								}
							?>
						</select>
					<li>
				</ul>
			</div>
		</section>
					
    <!-- jQuery (Bootstrap 的所有 JavaScript 插件都依赖 jQuery，所以必须放在前边) -->
    <script src="https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script>
    <!-- 加载 Bootstrap 的所有 JavaScript 插件。你也可以根据需要只加载单个插件。 -->
    <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	
	<script src="./js/index.js"></script>
  </body>
</html>