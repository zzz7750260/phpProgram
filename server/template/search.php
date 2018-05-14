<?php
include_once('c-menu.php');
//获取传递过来的关键词
$theKeyWord = $_GET['getKeyWord'];

?>

<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <title><?php echo $theKeyWord;?>查询-课间十分钟</title>
	
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
		<?php  getMenu(); ?>
		<section>
			<div class="container list-container">
				<div class="list-left col-md-9">
				<?php 
					//查询总条数
					$searchListSql = "select * from article where title like '%$theKeyWord%'";
					$searchListSql_db = mysql_query($searchListSql);
					$searchListSql_db_num = mysql_num_rows($searchListSql_db);
					echo $searchListSql_db_num;
					
					$thePage = $_GET['getPage'];
					$theLimit = $_GET['getLimit'];
					$forPage = $thePage*$theLimit;
					
					if($searchListSql_db_num ==0){
						$listHtml = '<div class="list-container-k row">没有相对应的数据</div>';						
					} 
					else{
						
						$searchGetListSql = "select a.*,b.* from article as a left join category as b on a.category_id = b.cid where article_status = 'public' and title like '%$theKeyWord%' limit $forPage,$theLimit";
						
						$searchGetListSql_db = mysql_query($searchGetListSql);		
						
						$searchGetListSqlArray = array();	
						
						while($indexListSql_db_array = mysql_fetch_assoc($searchGetListSql_db)){
							$searchGetListSqlArray[] = $indexListSql_db_array;
						}
														
						//print_r($indexArray);
						foreach($searchGetListSqlArray as $key => $value){
							//html渲染
							$listHtml .= '<div class="list-container-k row"><a href="http://'.$_SERVER['HTTP_HOST'].'/program/article/'.$value['categoryyw'].'/'.$value['aid'].'.html"><div class="list-container-k-left col-md-9"><div class="list-container-k-left-title"><h4>'.$value['title'].'</h4></div><div class="list-container-k-left-container">'.$value['article_short'].'</div></div><div class="list-container-k-right col-md-3"><img src="http://'.$_SERVER['HTTP_HOST'].'/program/upload/cover/'.$value['article_img'].'" class="img-responsive"></div><div class="clear"></div><hr/></a></div>';  				
						}
																			
					}				
					echo $listHtml;	
				?>
				</div>
				<div class="list-right col-md-3">
				
				</div>
				<div class="clear"></div>
				<div>
					<ul class="pager">
						<?php
							//分析页码数
							//检测分成几页(总数量除分页条数，如果有余数，页数加一)
							$searchPageSize = floor($searchListSql_db_num / $theLimit);
							//检测是否有余数，如果有余数，就加一
							$searchYs = $searchListSql_db_num % $theLimit;
							
							$thePageP = $thePage+1;
							$thePageS = $thePage-1;
							
							if($searchYs > 0){
								$searchPageSize = $searchPageSize + 1;							
							}

							//获取当前页面
							echo $thePage;
							if($thePage == 0){								
								$searchPage = '<li><a href="./search.php?&getPage='.$thePageP.'&getLimit=2">下一页</a></li>';								
							}
							if($thePage > 0 && $thePage < $searchPageSize){
								$searchPage = '<li><a href="./search.php?&getPage='.$thePageS.'&getLimit=2">上一页</a></li><li><a href="./search.php?&getPage='.$thePageP.'&getLimit=2">下一页</a></li>';
							}
							if(($thePage+1) == $searchPageSize){
								$searchPage = '<li><a href="./search.php?&getPage='.$thePageS.'&getLimit=2">上一页</a></li>';								
							}
							echo $searchPage;
						?>
						
					</ul>
				</div>
			</div>
		</section>

    <!-- jQuery (Bootstrap 的所有 JavaScript 插件都依赖 jQuery，所以必须放在前边) -->
    <script src="https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script>
    <!-- 加载 Bootstrap 的所有 JavaScript 插件。你也可以根据需要只加载单个插件。 -->
    <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  </body>
</html>