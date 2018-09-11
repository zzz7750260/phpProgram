	<?php 
		//print(chr(0xEF).chr(0xBB).chr(0xBF));
		//
		//echo "<br><hr/>=======这个是模板中的值==========<br/>";
		//print_r($theArticleArray);
		//echo "<hr/>";
		//echo $value['title'];
		include_once('../system.article.php');
		include_once('../system.util.php');
		$commonUtil = new util();
		$articleUtil = new articleUtil();
	?>
<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <title><?php echo $theArticleArray[0]['categoryname'];?></title>

    <!-- Bootstrap -->
    <link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
	

	
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
							$theList .= '<div class="thelist-k"><a href="../../article/'.$value['categoryyw'].'/'.$value['aid'].'.html"><div class="thelist-k-c col-md-9"><div class="thelist-k-title"><h4>'.$value['title'].'</h4></div><div class="thelist-k-container">'.$value['article_short'].'</div></div><div class="thelist-k-img col-md-3"><img src="../../upload/cover/'.$value['article_img'].'" class="img-responsive" ></div></a></div><hr/>';					
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
						if($i == 0){
							$pageHtml = '<li><a href="../../article/'.$theArticleArray[0]['categoryyw'].'/'.$theArticleArray[0]['categoryyw'].'-'.($i+1).'.html">下一页</a></li></li>';
						}
						if($i > 0 && $i< $pageNumZ){
							$pageHtml = '<li><a href="../../article/'.$theArticleArray[0]['categoryyw'].'/'.$theArticleArray[0]['categoryyw'].'-'.($i-1).'.html">上一页</a></li></li><li><a href="../../article/'.$theArticleArray[0]['categoryyw'].'/'.$theArticleArray[0]['categoryyw'].'-'.($i+1).'.html">下一页</a></li></li>';
							
						}						
						if(($i+1) == $pageNumZ)
						{
							if($pageNumZ == 1){
								$pageHtml = '<li><a href="../../article/'.$theArticleArray[0]['categoryyw'].'/'.$theArticleArray[0]['categoryyw'].'-'.($i).'.html">当前页</a></li></li>';	
								
							}
							if($pageNumZ > 1){
								$pageHtml = '<li><a href="../../article/'.$theArticleArray[0]['categoryyw'].'/'.$theArticleArray[0]['categoryyw'].'-'.($i-1).'.html">上一页</a></li></li>';
							}							
						}	
						echo $pageHtml;
					?>
					<!--<li><a href="#">上一页</a></li>
					<li><a href="#">下一页</a></li>-->
					<li>
						<select>
							<?php 
								$pageNumS = $pageNumZ;
								for($r = 0;$r<$pageNumS;$r++){
									$p = $r+1;
									echo '<option><a href="../../article/'.$theArticleArray[0]['categoryyw'].'/'.$theArticleArray[0]['categoryyw'].'-'.($p).'.html">第'.$p.'页</option>';
								}
							?>
						</select>
					</li>
				</ul>
			</div>
			
		</section>
		
	</div>


    <!-- jQuery (Bootstrap 的所有 JavaScript 插件都依赖 jQuery，所以必须放在前边) -->
    <script src="https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script>
    <!-- 加载 Bootstrap 的所有 JavaScript 插件。你也可以根据需要只加载单个插件。 -->
    <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  </body>
</html>