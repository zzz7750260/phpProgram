	<?php 
		include_once('../system.util.php');
		$commonUtil = new util();
		//获取传递过来的列表信息
		$theArticleArray = $thePageArray;
		include_once('../system.article.php');
		$articleUtil = new articleUtil();
	?>
<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <title><?php echo "频道列表-第".$p."页-课间十分钟";?></title>

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
  <body>
	<div class="container">
		<?php 
		include_once('c-menu.php');
		getMenu();
		?>
		<section>
			<div class="row">
				<article class="thelist col-md-10">
					
					<?php 			
																
						//循环输出文章列表
						foreach($theArticleArray as $item => $value){						
							
							$theList .= '<div class="thelist-k row"><div class="thelist-k-img col-md-3"><img src="../../upload/user_cover/'.$value['cover_img'].'" class="img-responsive" ></div><div class="thelist-k-c col-md-9"><div class="thelist-k-title"><a href="./'.$value['pid'].'.html"><h4>'.$value['title'].'</h4></a></div><div class="thelist-k-container"><div class="thelist-k-container-xx"><span>作者：<a href="../user-page/'.$value['author'].'.html">'.$value['author'].'</a></span></div><div class="thelist-k-container-js">'.$value['cover_introduction'].'</div><div class="thelist-k-container-array"><ul class="row">'.getArticleArrayHtml($value['ptitle']).'</ul></div><div class="thelist-k-container-more"><a  href="./'.$value['pid'].'.html">更多视频</a></div></div></div></div><hr/>';					
						}
						echo $theList;
						//由于$theList是使用字符串连接，因而在输出完后，需要将字符串清除，以免得到之前获取的数据
						$theList = '';
						
					?>	
					
				</article>
				
				<aside class="col-md-2">
					<?php //print_r($articleUtil->getRandArticleList(5,2));
						$getListArray = $articleUtil->getRandArticleList(5,2);
						foreach($getListArray as $key =>$value ){
							$listHtml .= '<div class="side-list"><div class="side-list-title">'.$value['title'].'</div><div class="side-list-container">'.$value['article_short'].'</div></div>';
						}
						echo $listHtml;
					?>
				</aside>
			</div>
			<div>
				<ul class="pager">
					<li><?php //echo "总共页数：".$pageNumZ?></li>
					
					<?php
						if($p == 1){
							$pageHtml = '<li><a href="../../article/'.$theArticleArray[0]['categoryyw'].'/'.$theArticleArray[0]['categoryyw'].'-'.($i+1).'.html">下一页</a></li></li>';
						}					
						else if($p== $pageNumZ)
						{
							if($pageNumZ == 1){
								$pageHtml = '<li><a href="../../article/'.$theArticleArray[0]['categoryyw'].'/'.$theArticleArray[0]['categoryyw'].'-'.($i).'.html">当前页</a></li></li>';	
								
							}
							if($pageNumZ > 1){
								$pageHtml = '<li><a href="../../article/'.$theArticleArray[0]['categoryyw'].'/'.$theArticleArray[0]['categoryyw'].'-'.($i-1).'.html">上一页</a></li></li>';
							}							
						}	
						else{
							$pageHtml = '<li><a href="../../article/'.$theArticleArray[0]['categoryyw'].'/'.$theArticleArray[0]['categoryyw'].'-'.($p-1).'.html">上一页</a></li></li><li><a href="../../article/'.$theArticleArray[0]['categoryyw'].'/'.$theArticleArray[0]['categoryyw'].'-'.($p+1).'.html">下一页</a></li></li>';
							
						}	
						echo $pageHtml;
					?>
					<!--<li><a href="#">上一页</a></li>
					<li><a href="#">下一页</a></li>-->
					<li>
						<select onchange="window.location = this.value">
							<option>页面选择</option>
							<?php 
								
								for($r = 0;$r<$pageNum;$r++){
									$z = $r+1;
									echo '<option value="'.$commonUtil->isHttpsCheckSelect().'//'.$_SERVER['HTTP_HOST'].'/article/cover-page/cover-list-'.$z.'.html">第'.$z.'页</option>';
								}
							?>
						</select>
					</li>
				</ul>
			</div>
			
		</section>
		
	</div>

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