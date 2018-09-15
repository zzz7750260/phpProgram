<!DOCTYPE html>
<html lang="zh-CN">
  <head>
	<?php 
		//
		//echo "=======这个是模板中的值==========<br/><hr/>";
		//print_r($value);
		//echo "<hr/>";
	//	echo $value['title'];
	include_once('../system.util.php');
	$commonUtil = new util();
	
	//根据文章的id获取评论数
	$commArticleId = $value['aid'];
	$theCommentSql = "select * from comment where cmtid = '$commArticleId'";
	$theCommentSql_db = mysql_query($theCommentSql);
	$theCommentSql_db_num = mysql_num_rows($theCommentSql_db);
	
	?>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <title><?php echo $value['title'] .'-'. $value['categoryname'] .'- 课间十分钟'?></title>
	<meta name="keywords" content="<?php echo $value['article_key']?>" />
	<meta name="description" content="<?php echo $value['article_short']?>" />

    <!-- Bootstrap -->
    <link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
	<link href="../../css/common.css" rel="stylesheet">
	<link href="../../css/article.css" rel="stylesheet">

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
	<div class="container">
		<div>
			<!--引入面包屑-->
			<?php 
				include('c-breadcrumb.php');
				//$theBreadcrumbArray = $this->getCategoryInfoArray($value['category_id']);
			?>
		</div>
		<section>
			<div class="row">
				<article class="theArticle col-md-9">
					<div class="theArticle-title">
						<h1><?php echo $value['title'];?></h1>
						<hr/>
					</div>
					
					<div class="theArticle-detail">
						<span>作者:<?php $thePathShowHtml = '<a href="'.$commonUtil->isHttpsCheckSelect().'//'.$_SERVER['HTTP_HOST'].'/article/user-page/'.$value['article_author'].'.html">'.$value['article_author'].'</a>'; echo $thePathShowHtml;?></span>
						
						<span>封面:<?php $thePathShowHtml = '<a href="'.$commonUtil->isHttpsCheckSelect().'//'.$_SERVER['HTTP_HOST'].'/article/cover-page/'.$value['pid'].'.html">'.$value['article_cover'].'</a>'; echo $thePathShowHtml;?></span>
						
						
						
						<span>分类:<?php $thePathShowHtml = '<a href="'.$commonUtil->isHttpsCheckSelect().'//'.$_SERVER['HTTP_HOST'].'/article/'.$value['categoryyw'].'/'.$value['categoryyw'].'-1.html">'.$value['categoryname'].'</a>'; echo $thePathShowHtml;?></span>
						
						<span>发布日期:<?php echo $value['commit_start'];?></span>
						
						<span>浏览量:<?php echo $value['article_view'];?></span>
						
						<span>评论数:<?php echo $theCommentSql_db_num;?></span>
						<br/>
						<hr/>
					</div>
					
					<div class="theArticle-img">
						<!--
						<div class="col-md-6 col-md-offset-3 theArticle-imgk">
							<img class="" data-getid ="<?php echo $value['aid'];?>" src="../../upload/cover/<?php echo $value['article_img'];?>">
							
							
						</div>
						-->
						<div class="col-md-12 theArticle-imgk">
							<img class="img-responsive theArticle-imgk-img" data-getid ="<?php echo $value['aid'];?>" src="../../upload/cover/<?php echo $value['article_img'];?>">
							
							
						</div>
						
						<div class="clear"></div>
					</div>
					<div class="theArticle-container">
						<?php echo $value['article_container'];?>
					</div>
				</article>
				
				<aside class="col-md-3 theSide">
				<ul>
				<?php 
					//引入文章的公共类
					include_once('../system.article.php');
					$theArticleUtil = new articleUtil();
					echo $value['cpid'];
					$theArticleSideRandArray = $theArticleUtil->getCategoryArticle($value['cpid'],6,"rand");
					//print_r($theArticleSideRandArray);
					
					//遍历数组,组装html列表
					foreach($theArticleSideRandArray as $key => $SideAritcleValue){
						$sideArticleListHtml = '<li class="col-md-6 col-xs-6"><a href="'.$commonUtil->isHttpsCheckSelect().'//'.$_SERVER['HTTP_HOST'].'/article/'.$SideAritcleValue['categoryyw'].'/'.$SideAritcleValue['aid'].'.html"><div class="side-article-list-img"><img class="img-responsive" src="../../upload/cover/'.$SideAritcleValue['article_img'].'">'.substr($SideAritcleValue['title'],0,18).'</div></a></li>';
						echo $sideArticleListHtml;				
					}
					
				?>	
				</ul>
			</aside>
			<div class="clear"></div>
			</div>
		</section>
		
		<section>
			
			<div class="article-comment-input">
			<?php 
				if($value['commit_status'] == true){
					include('c-comment-input.php');					
				}
				else{
					echo '<div data-article="">该文章不支持评论</div>';				
				}
						
			?>
			</div>
			<div class="article-comment-list">
				<ul class="article-comment-list-k">
				
				</ul>
			</div>		
		</section>
		
	</div>	
	<?php 
		//引入底部
		include("c-footer.php");
	?>


    <!-- jQuery (Bootstrap 的所有 JavaScript 插件都依赖 jQuery，所以必须放在前边) -->
    <script src="https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script>
    <!-- 加载 Bootstrap 的所有 JavaScript 插件。你也可以根据需要只加载单个插件。 -->
    <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script src="../../js/index.js"></script>
  </body>
</html>