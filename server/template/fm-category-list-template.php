<?php 
	//include_once('../system.mysql.int.php');
	include_once('../system.article.php');
	include_once('../system.util.php');
	$commonUtil = new util();	
	$theArticleUtil = new articleUtil();	
	
	//$a = getCategoryArticle(1);
	//print_r($a);
	
	//调用文章类
	$theArticleUtil = new articleUtil();
	
	//$theCategoryFatherArray为父类传递过来的信息
	//$categoryInfoArray = $theCategoryArray;
	
		
	//假设父类id
	$theFmFatherId = $_GET['the-fm-father-id'];
	//$theFmFatherId = $categoryInfoArray['cid'];
	
	//获取category的信息
	//$getCategoryInfoSql = "select * from category where cid = '$theFmFatherId'";
	//$getCategoryInfoSql_db = mysql_query($getCategoryInfoSql);
	//while($getCategoryInfoSql_db_array = mysql_fetch_assoc($getCategoryInfoSql_db)){
		//$theCategoryInfo = $getCategoryInfoSql_db_array;	
	//}
			
	
	//根据categoryId获取对应的文章
	//$getArticleArraySql = "select a.*, b.* from fm as a left join category as b on a.fm_category = b.cid where a.fm_category ='$theFmFatherIdorder' order by a.fid DESC limit 0,10";
	
	//$getArticleArraySql_db = mysql_query($getArticleArraySql);
	
	//$ArticleArrayValue = array();
	
	//while($getArticleArraySql_db_array = mysql_fetch_assoc($getArticleArraySql_db)){
		//$ArticleArrayValue[] = $getArticleArraySql_db_array;
	//}
	
	
	
	//获取传递过来的分类信息
	$theCategoryInfo = $theCategoryInfoSql_db_array;
	$getCategoryInfo =  $theCategoryInfo;
	
	//获取传递过来的文章列表
	//var_dump($selectCategoryArticleArray);
	$ArticleArrayValue = $selectCategoryArticleArray;
	$theArticleArray = $ArticleArrayValue;
		
			
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
				<h2><?php echo $getCategoryInfo['categoryname'];?></h2>
				<p><?php echo $getCategoryInfo['categoryms'];?></p>
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
			<div class="fm-article-container container">
				<div class="fm-article-container-left col-md-9">
				<?php 
					//遍历文章数组，渲染对应的文章列表
					foreach($theArticleArray as $theArticleKey => $theArticleValue){
						//获取对应的图片链接
						$getImgPath = '/upload/fm/'.$theArticleValue['f_img'];
						$imgPath = $commonUtil->webPath($getImgPath);
						
				?>		
					<div class="media">
					  <div class="media-left">
						<img src="<?php echo $imgPath;?>" class="media-object" style="width:240px">
					  </div>
					  <div class="media-body">
						<h4 class="media-heading"><a href ="<?php $afterArtilce = '/fm/'.$theArticleValue['categoryyw'].'/'.'fm-'.$theArticleValue['fid'] .'.html'; echo $commonUtil->webPath($afterArtilce); ?>"><?php echo $theArticleValue['f_title'];?></a></h4>
						<div class="media-body-info">
							<span>作者：<?php echo  $theArticleValue['f_admin']?></span> <span>日期：<?php echo  $theArticleValue['f_time']?></span> 
						</div>
						<div class="media-body-article">
							<p><?php echo $theArticleValue['f_short'];?></p>
						</div>
						
					  </div>
					</div>
				<?php
					};
				?>					
				</div>
				<div class="fm-article-container-right col-md-3">
					<h4>推荐阅读</h4>
					<ul class="list-group">
						<?php 
							$sideArticleArray = $theArticleUtil->getCategoryFmArray($getCategoryInfo['cpid'], $num=10, $type='random');
							//var_dump($sideArticleArray);
							//遍历数组，渲染html
							foreach($sideArticleArray as $sideArticleKey => $sideArticleValue){
								$sideFmArticlePath = '/fm/'.$sideArticleValue['categoryyw'].'/'.'fm-'.$sideArticleValue['fid'].'.html';
								$sideLiArticle = '<li class="list-group-item"><a href="'.$commonUtil->webPath($sideFmArticlePath).'">'.$sideArticleValue['f_title'].'</a></li>';
								echo $sideLiArticle;
							}
						
							
						?>
					</ul>
				</div>
			</div>
		</section>
		
		<section>
			<div class="container">
				
				<ul class="pager col-md-8 col-md-offset-4">
					<?php 
						if($setPage<=1){
							$pageHtml = '';
							$pageUrl = '/fm/'.$theCategoryInfoSql_db_array['categoryyw'] .'/'. $theCategoryInfoSql_db_array['categoryyw'] . '-' . $setPage . '.html';
							$pageHtml = '<li class="col-md-2"><a href="'.$commonUtil->webPath($pageUrl).'">首页</a></li>';
							$pageHtml .='<li class="col-md-3"><select class="form-control"><option>页数</option><option>第'.$setPage.'页</option>';
							$pageHtml .='</select></li>';
						}
						if($setPage>1 && $setPage<$pages){
							$pageHtml = '';
							$setPageq = $setPage-1;
							$pageUrlq = '/fm/'.$theCategoryInfoSql_db_array['categoryyw'] .'/'. $theCategoryInfoSql_db_array['categoryyw'] . '-' . $setPageq . '.html';
							$pageHtml = '<li class="col-md-2"><a href="'.$commonUtil->webPath($pageUrlq).'">上一页</a></li>';
							$pageHtml .='<li class="col-md-3"><select class="form-control"><option>页数</option>';
							for($x=1; $x<$pages ; $x++){
								$pageUrls = '/fm/'.$theCategoryInfoSql_db_array['categoryyw'] .'/'. $theCategoryInfoSql_db_array['categoryyw'] . '-' . $x . '.html';
								$pageHtml .='<option value="'.$commonUtil->webPath($pageUrls).'">第'.$x.'页</option>';
								
							}
							$pageHtml .='</select></li>';
							$setPageh = $setPage+1;
							$pageUrlh = '/fm/'.$theCategoryInfoSql_db_array['categoryyw'] .'/'. $theCategoryInfoSql_db_array['categoryyw'] . '-' . $setPageh . '.html';						
							$pageHtml .='<li class="col-md-2"><a href="'.$commonUtil->webPath($pageUrlh).'">后一页</a></li>';
						}
						if($setPage>=$pages){
							$pageHtml = '';	
							$setPageq = $setPage-1;
							$pageUrlq = '/fm/'.$theCategoryInfoSql_db_array['categoryyw'] .'/'. $theCategoryInfoSql_db_array['categoryyw'] . '-' .$setPageq. '.html';
							$pageHtml = '<li class="col-md-2"><a href="'.$commonUtil->webPath($pageUrlq).'">前一页</a></li>';
							$pageHtml .= '<li class="col-md-3"><select class="form-control"><option>页数</option>';
							for($x=1; $x<$pages ; $x++){
								$pageUrls = '/fm/'.$theCategoryInfoSql_db_array['categoryyw'] .'/'. $theCategoryInfoSql_db_array['categoryyw'] . '-' . $x . '.html';
								$pageHtml .='<option value="'.$commonUtil->webPath($pageUrls).'">第'.$x.'页</option>';							
							}
							$pageHtml .='</select></li>';									
						}
						
						
						echo $pageHtml;
					?>
					<!--
					<li class="col-md-2"><a href="#">前一页</a></li>
					<li class="col-md-3">
						<select class="form-control">
							<option>1</option>
							<option>2</option>
							<option>3</option>
							<option>4</option>
							<option>5</option>
						</select>
					</li>
					<li class="col-md-2"><a href="#">后一页</a></li>-->
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