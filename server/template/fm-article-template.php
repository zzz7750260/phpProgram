<!DOCTYPE html>
<html lang="zh-CN">
		<?php 
		//
		include_once(dirname(dirname(__FILE__))."/system.qiniu.php");
		
		//print_r($value);
		//echo "<hr/>";
		//	echo $value['title'];
		include_once('../system.util.php');
		include_once('../system.article.php');
		include_once('../system.mysql.int.php');
	//	echo "=======这个是模板中的值==========<br/><hr/>";
		
		$commonUtil = new util();
		$articleUtil = new articleUtil();
		$qiniuUtil = new qiniuUtil();
		//获取文章内容
	//	$fm_id = $_GET['fmarticleid'];
	//	echo $fm_id;
		$qiniuUtil = new qiniuUtil();
		$qiniuUtil->setQiniuUse('qiniu');
		$cdnUrl = $qiniuUtil->useQiniuCdnWeb();
		//echo "外部设置路径：".$cdnUrl;
		
		//根据id获取对应文章的信息
		//$getFmArticleSql = "select a.*,b.* from fm as a left join category as b on a.fm_category = b.cid where a.fid = '$fm_id'";
		//$getFmArticleSql_db = mysql_query($getFmArticleSql);
		//$getFmArticleArray = array();
		//while($getFmArticleSql_db_array = mysql_fetch_assoc($getFmArticleSql_db)){
		//	$getFmArticleArray = $getFmArticleSql_db_array;
		//}
		//var_dump($getFmArticleArray);
		//echo "数据显示；";
		$value = $getFmArticleArray;
	?>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <title>
	<?php
		$theTitle = '';
		if($value['short_title']){
			$theTitle = $value['f_title'].'-'. $value['short_title'] . '-' . $value['categoryname'] .'-课间十分钟';
		}
		else{
			$theTitle = $value['f_title'].'-'. $value['categoryname'] .'-课间十分钟';
		}	
		echo $theTitle; 		
	?>
	</title>
	<meta name="keywords" content="<?php 
		$theKeyWord = '';
		$theKeyWord = $value['f_keyword'];
		//$theKeyWord = $theKeyWord .','.$value['categoryname'] . ',课间十分钟';
		echo $theKeyWord;
	?>" />
	<meta name="description" content="<?php echo $value['f_short']?>" />
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
						<h1><?php echo $value['f_title'];?></h1>
						<hr/>
					</div>
					
					<div class="theArticle-detail">
						<span>作者:<?php $thePathShowHtml = '<a href="'.$commonUtil->isHttpsCheckSelect().'//'.$_SERVER['HTTP_HOST'].'/article/user-page/'.$value['f_admin'].'.html">'.$value['f_adminr'].'</a>'; echo $thePathShowHtml;?></span>
						
						<span>封面:<?php $thePathShowHtml = '<a href="'.$commonUtil->isHttpsCheckSelect().'//'.$_SERVER['HTTP_HOST'].'/article/cover-page/'.$value['pid'].'.html">'.$value['article_cover'].'</a>'; echo $thePathShowHtml;?></span>
						
						
						
						<span>分类:<?php $thePathShowHtml = '<a href="'.$commonUtil->isHttpsCheckSelect().'//'.$_SERVER['HTTP_HOST'].'/article/'.$value['categoryyw'].'/'.$value['categoryyw'].'-1.html">'.$value['categoryname'].'</a>'; echo $thePathShowHtml;?></span>
						
						<span>发布日期:<?php echo $value['f_time'];?></span>
						
						<span>浏览量:<?php echo $value['article_view'];?></span>
						
						<span>评论数:<?php echo $theCommentSql_db_num;?></span>
						<br/>
						<hr/>
					</div>	
					
					<div class="theArticle-FM" data-article = <?php echo $value['fid']?>>
						<div class="FM-dmwall">
							
						</div>
						<div class="FM-dm">
							<input type="text" class="FM-dm-text" id="FM-dm-text">
							<input type="button" class="FM-dm-put" id="FM-dm-put" value="发送弹幕">
						</div>
						<div class="FM-zz">
						
						</div>
						<!--<audio id="theFm" src="<?php echo $cdnUrl .'/mp3/'. $value['f_fm']?>" controls="controls">
							Your browser does not support the audio element.
						</audio>-->
						<audio id="theFm" src="" controls="controls">
							Your browser does not support the audio element.
						</audio>
					</div>
					<div class="theArticle-container">
						<?php echo $value['f_container'];?>
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
			
    <!-- jQuery (Bootstrap 的所有 JavaScript 插件都依赖 jQuery，所以必须放在前边) -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@1.12.4/dist/jquery.min.js"></script>
    <!-- 加载 Bootstrap 的所有 JavaScript 插件。你也可以根据需要只加载单个插件。 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js"></script>
	<script src="../../js/index.js"></script>	
  </body>
</html>
