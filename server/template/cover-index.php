<?php
	//include('../system.mysql.int.php');
	//$theUsername = 'haha123456';
	//$ob = $_GET['getOb'];   //获取是否存在静态化的标识
	//用户信息
	//$theCover = '脱口秀哈哈哈';
	
	//获取封面信息
	$theCoverInfoSql = "select * from page where title = '$theCover'";
	$theCoverInfoSql_db = mysql_query($theCoverInfoSql);	
	$theCoverInfoArray = array();
	while($theCoverInfoSql_db_array = mysql_fetch_assoc($theCoverInfoSql_db)){
		$theCoverInfoArray = $theCoverInfoSql_db_array; 
	}
	
	//获取与封面相关的文章列表
	$theCoverArticleSql = "select * from article where article_cover = '$theCover' order by aid DESC limit 0,5";
	$theCoverArticleSql_db = mysql_query($theCoverArticleSql);
	$theCoverArticleArray = array();
	while($theCoverArticleSql_db_array = mysql_fetch_assoc($theCoverArticleSql_db)){
		$theCoverArticleArray[] = $theCoverArticleSql_db_array;
	}
	
?>	
	<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <title><?php echo $theCoverInfoArray['title'] . '-' .$theCoverInfoArray['author'] ;?></title>

    <!-- Bootstrap -->
    <link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
	<link href="../../css/common.css" rel="stylesheet">
	
    <!-- HTML5 shim 和 Respond.js 是为了让 IE8 支持 HTML5 元素和媒体查询（media queries）功能 -->
    <!-- 警告：通过 file:// 协议（就是直接将 html 页面拖拽到浏览器中）访问页面时 Respond.js 不起作用 -->
    <!--[if lt IE 9]>
      <script src="https://cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body data-value="<?php echo $theCoverInfoArray['pid'];?>" data-type="cover">
	<section>
		<div class="user-head container">
			<h2><?php echo $theCoverInfoArray['title']?>封面介绍</h2>	
			<div class ="user-head-info">
				<div class="user-head-info-header col-md-2">
					<img class="img-responsive" src="../../upload/user_cover/<?php echo $theCoverInfoArray['cover_img'];?>">
				</div>
				
				<div class="user-head-info-introduction col">
					<?php echo $theCoverInfoArray['cover_introduction']?>
				</div>
				
				<div class="clear"></div>
			</div>
		</div>
	</section>

	
	<section>
		<div class="container user-article">		
			<ul class="user-article-ul">
				<?php 
					//必须要将$articleHtml设置成空，否则会导致后面静态化时会重复第一次的加载
					$articleHtml = '';
					//遍历文章数组并进行组装
					foreach($theCoverArticleArray as $key => $valueArticle){
						$articleHtml .= '<li><div class="user-article-k"><div class="user-article-img col-md-3"><img src="../../upload/cover/'.$valueArticle['article_img'].'" class="img-responsive"></div><div class="user-article-container col-md-9"><h3>'.$valueArticle['title'].'</h3></div></div><div class="clear"></div></li>';					
					}
					echo $articleHtml;
				?>
			</ul>
		</div>
	</section>
	
	<footer>
		<div class="theFooter">
			这个是底部
		</div>
	</footer>
	
    	
    <!-- jQuery (Bootstrap 的所有 JavaScript 插件都依赖 jQuery，所以必须放在前边) -->
    <script src="https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	 <script src="../../js/index.js"></script>
  </body>
</html>