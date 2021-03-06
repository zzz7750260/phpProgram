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
	include_once('../system.article.php');
	$commonUtil = new util();
	$articleUtil = new articleUtil();
	//根据文章的id获取评论数
	$commArticleId = $value['aid'];
	$theCommentSql = "select * from comment where cmtid = '$commArticleId'";
	$theCommentSql_db = mysql_query($theCommentSql);
	$theCommentSql_db_num = mysql_num_rows($theCommentSql_db);
	
	//根据文章的id获取对应的视频集
	
	
	?>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <title>
	<?php
		$theTitle = '';
		if($value['short_title']){
			$theTitle = $value['title'].'-'. $value['short_title'] . '-' . $value['categoryname'] .'-课间十分钟';
		}
		else{
			$theTitle = $value['title'].'-'. $value['categoryname'] .'-课间十分钟';
		}	
		echo $theTitle; 		
	?>
	</title>
	<meta name="keywords" content="<?php 
		$theKeyWord = '';
		$theKeyWord = $articleUtil->returnTheKeyWord($value['cpid'],$value['article_key']);
		$theKeyWord = $theKeyWord .','.$value['categoryname'] . ',课间十分钟';
		echo $theKeyWord;
	?>" />
	<meta name="description" content="<?php echo $value['article_short']?>" />
	<!--图标-->
	<link rel="shortcut icon" href="favicon.ico"/>
	<link rel="bookmark" href="favicon.ico"/>	
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
						<h3>相关视频</h3>
						<!--
						<div class="col-md-6 col-md-offset-3 theArticle-imgk">
							<img class="" data-getid ="<?php echo $value['aid'];?>" src="../../upload/cover/<?php echo $value['article_img'];?>">													
						</div>
						-->
						
						<div class="col-md-12 theArticle-imgks ">				
							<!--<img class="img-responsive theArticle-imgk-img" data-getid ="<?php echo $value['aid'];?>" src="../../upload/cover/<?php echo $value['article_img'];?>">-->
							
						<?php 
							//根据文章的id获取对应的视频集
							$theVideoArraySql = "select * from video where video_article = '$commArticleId'";
							$theVideoArraySql_db = mysql_query($theVideoArraySql);
							$theVideoArraySql_db_num = mysql_num_rows($theVideoArraySql_db);
							$theVideoArray = array();
							while($theVideoArraySql_db_array = mysql_fetch_assoc($theVideoArraySql_db)){
								$theVideoArray[] = $theVideoArraySql_db_array;
							}
													
							//根据视频的数量来决定框的大小	
							switch($theVideoArraySql_db_num){
								case 1:
									$htmlVideoK = '<div class="col-md-12">';
									break;
								case 2:
									$htmlVideoK = '<div class="col-md-6">';
									break;
								case 3:
									$htmlVideoK = '<div class="col-md-4">';
									break;					
								default: 
									$htmlVideoK = '<div class="col-md-3">';
							}
							
							$htmlVideoContainer = '';
							//遍历数组，将video的图片输出
							foreach($theVideoArray as $theVideoKey => $theVideoValue){							
								$htmlVideoContainer .= $htmlVideoK . '<img class="img-responsive theArticle-imgks-img" data-getvideoid ="'.$theVideoValue['vid'].'" src="../../upload/video/'.$theVideoValue['video_img'].'" title="'.$theVideoValue['video_name'].'" alt="'.$theVideoValue['video_name'].'"></div>';							
							}
							
							echo $htmlVideoContainer;
							//if($theVideoArraySql_db_num = 1){
							//	$htmlVideoK .= '<div class="col-md-12">';
							//}
																												
						?>
						
							<div class="clear"></div>
							
							
							<!--设置模态框-->
							<!-- 模态框（Modal） -->
							<div class="modal fade videoModal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
								<div class="modal-dialog modal-video">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
											<h4 class="modal-title" id="myModalLabel">模态框（Modal）标题</h4>
										</div>
										<div class="modal-body">在这里添加一些文本</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
											<!--<button type="button" class="btn btn-primary">提交更改</button>-->
										</div>
									</div><!-- /.modal-content -->
								</div><!-- /.modal -->
							</div>
						</div>
						
						<div class="clear"></div>
					</div>
					<div class="theArticle-container">
						<?php echo $value['article_container'];?>
					</div>
					
					<div class="clear"></div>
					
					<div class="theArticle-tag">
						<?php 
							if($value['article_tag']){
								//将tag字符串转换为数组
								$tagArray = $articleUtil->tagChangeArray($value['article_tag']);
								//print_r($tagArray);
								//遍历数组渲染htmls
								$tagHtml = '';
								$tagHtml .= '<div class="theArticle-tag-li">标签：';
								foreach($tagArray as $tagKey => $tagValue){
									$tagHtml .="<span>".$tagValue."</span>";									
								}
								$tagHtml .="</div>";
								echo $tagHtml;
							}
						?>
					</div>
					
					<div>		
						<div class="article-comment-input">
						<?php 
							if($value['commit_status'] == "true"){
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