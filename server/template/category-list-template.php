<?php 
	//include_once('../system.mysql.int.php');
	//include_once('../system.article.php');
	include_once('../system.util.php');
	$commonUtil = new util();
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
	<meta name="keywords" content="<?php echo $categoryInfoArray['categoryname'];?>" />
	<meta name="description" content="<?php echo $categoryInfoArray['categoryms'];?>" />
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
					<?php 
						include('c-search.php');
					?>
				</p>
			</div>
		</div>
		
		<section>
			<div class="container">			
				<?php
					//如果为总分类时，根据id寻找子分类，如果为子分类，根据其父分类找子分类
					
					if($typePage == "categoryList"){
						//根据父类获取子类
						$menuCategoryArray = $theArticleUtil->getCategoryArray($theCategoryId);
						//print_r($menuCategoryArray);
						$menuHtml = $categoryInfoArray['categoryname'] . ':';
					}
					if($typePage == "list"){
						$menuCategoryArray = $theArticleUtil->getCategoryArray($categoryInfoArray['cpid']);
						
					}

					//循环子分类菜单
					foreach($menuCategoryArray as $menuKey => $menuValue){
						$menuHtml .= '<a href="'.$commonUtil->isHttpsCheckSelect().'//'.$_SERVER['HTTP_HOST'].'/article/'.$menuValue['categoryyw'].'/'.$menuValue['categoryyw'].'-1.html"><span>'.$menuValue['categoryname'].'</span></a>';						
					}
					echo $menuHtml;		
					//为了防止重复，将$menuHtml赋值为空
					$menuHtml = '';
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
								$theHtml .= '<div class="col-sm-6 col-md-3"><div class="thumbnail"><a href="'.$commonUtil->isHttpsCheckSelect().'//'.$_SERVER['HTTP_HOST'].'/article/'.$value['categoryyw'].'/show-'.$value['aid'].'.html"><img src="../../upload/cover/'.$value['article_img'].'" alt="'.$value['title'].'"><h5>'.$value['title'].'</h5></a><div><span>分类:<a href="'.$commonUtil->isHttpsCheckSelect().'//'.$_SERVER['HTTP_HOST'].'/article/'.$value['categoryyw'].'/'.$value['categoryyw'].'-1.html">'.$value['categoryname'].'</a></span><span>来源:<a href="'.$commonUtil->isHttpsCheckSelect().'//'.$_SERVER['HTTP_HOST'].'/article/cover-page/'.$value['pid'].'.html">'.$value['article_cover'].'</a></span></div></div></div>';
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
					
						if($typePage == "categoryList"){
							if($p == 1){
								$n = $p + 1;
								echo '<li><a href="./'.$categoryInfoArray['categoryyw'].'-list-'.$n.'.html">Next</a></li>';	
							}	
							else if($p == $getPageNum){
								$s  = $p - 1;
								echo '<li><a href="./'.$categoryInfoArray['categoryyw'].'-list-'.$s.'.html">Previous</a></li>';							
							}
							else{
								$n = $p + 1;
								$s  = $p - 1;
								echo '<li><a href="./'.$categoryInfoArray['categoryyw'].'-list-'.$s.'.html">Previous</a></li><li><a href="./'.$categoryInfoArray['categoryyw'].'-list-'.$n.'.html">Next</a></li>';					
							}														
						}
						
						if($typePage == "list"){
							if($w == 1){
								$n = $w + 1;
								echo '<li><a href="./'.$categoryInfoArray['categoryyw'].'-'.$n.'.html">Next</a></li>';	
							}	
							else if($w == $pageNumZ){
								$s  = $w - 1;
								echo '<li><a href="./'.$categoryInfoArray['categoryyw'].'-'.$s.'.html">Previous</a></li>';							
							}
							else{
								$n = $w + 1;
								$s  = $w - 1;
								echo '<li><a href="./'.$categoryInfoArray['categoryyw'].'-'.$s.'.html">Previous</a></li><li><a href="./'.$categoryInfoArray['categoryyw'].'-'.$n.'.html">Next</a></li>';					
							}								
							
						}
											
					?>
					<li>
						<select onchange="window.location.href = this.value">
							<option>选择页数</option>
							<?php 
								if($typePage =="categoryList"){
									//因为p为分页数，如果这里用p的话，会影响到静态化
									for($lp=1;$lp<=$getPageNum;$lp++){
										echo '<option value="'.$commonUtil->isHttpsCheckSelect().'//'.$_SERVER['HTTP_HOST'].'/article/'.$categoryInfoArray['categoryyw'].'/'.$categoryInfoArray['categoryyw'].'-list-'.$lp.'.html"">第'.$lp.'页</option>';
									}																	
								}
								if($typePage =="list"){
									for($xp=1;$xp<=$pageNumZ;$xp++){
										echo '<option value="'.$commonUtil->isHttpsCheckSelect().'//'.$_SERVER['HTTP_HOST'].'/article/'.$categoryInfoArray['categoryyw'].'/'.$categoryInfoArray['categoryyw'].'-'.$xp.'.html"">第'.$xp.'页</option>';
									}										
								}
							?>
						</select>
					<li>
				</ul>
			</div>
		</section>
		<?php 
			//引入footer
			include('c-footer.php');	
		?>
					
    <!-- jQuery (Bootstrap 的所有 JavaScript 插件都依赖 jQuery，所以必须放在前边) -->
    <script src="https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script>
    <!-- 加载 Bootstrap 的所有 JavaScript 插件。你也可以根据需要只加载单个插件。 -->
    <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	
	<script src="../../js/index.js"></script>
  </body>
</html>