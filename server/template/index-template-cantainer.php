<?php 
	//$theCategorySelect由外部赋值   
?>
<section>
	<div class="container cinema-article">
		<div class="cinema-article-container row">
			<div class="cinema-article-container-video col-md-9">
				<div class="cinema-article-container col-md-12">						
					<div class="cinema-article-title row">
						<div class="cinema-article-title-left col-md-2">
							<h3><?php echo $theCategorySelect['categoryname'];?></h3>
						</div>
						<div class="cinema-article-title-right col-md-10">
							
							<?php
								//防止出现重复，需重新的定义
								$arrayHtml = '';
							
								//获取对应的分组目录,这里只需要下一层子集
								$cinemaCategoryArray = $theArticleUtil->getCategoryArray($theCategorySelect['cid']);
								
								//print_r($cinemaCategoryArray);
								//遍历数组获取对应的菜单渲染
								foreach($cinemaCategoryArray as $key => $value){
									$arrayHtml .= '<span style="margin:5px"><a href="'.$commonUtil->isHttpsCheckSelect().'//'.$_SERVER['HTTP_HOST'].'/article/'.$value['categoryyw'].'/'.$value['categoryyw'].'-1.html">'.$value['categoryname'].'</a></span>';
								}
								echo $arrayHtml;
							?>
							
							
							<span><a href="<?php 
								$theCategoryArray = $theArticleUtil->categoryDetail($theCategorySelect['cid']);
								$theCategoryWz = $theCategoryArray['categoryyw'];
								$theUrl = ''.$commonUtil->isHttpsCheckSelect().'//' .$_SERVER['HTTP_HOST']. '/article/'.$theCategoryWz.'/'.$theCategoryWz.'-list-1.html';
								echo $theUrl;
							?>">更多</a></span>
						</div>
					</div>						
				
					<div class="row">
						<?php 
							//新建，避免重复上面数据
							$cinemaHtml = '';
							$cinemaArticleArray = $theArticleUtil->getCategoryArticle($theCategorySelect['cid'],8);
							//print_r($hotArticleArray);
							//遍历数组，将数组组装成html
							foreach($cinemaArticleArray as $key => $value){
								$cinemaHtml .= '<div class="col-sm-6 col-md-3"><div class="thumbnail"><a href="'.$commonUtil->isHttpsCheckSelect().'//'.$_SERVER['HTTP_HOST'].'/article/'.$value['categoryyw'].'/'.$value['aid'].'.html"><img src="../../upload/cover/'.$value['article_img'].'" alt="通用的占位符缩略图"><h5>'.$value['title'].'</h5></a><div><span>分类:<a href="'.$commonUtil->isHttpsCheckSelect().'//'.$_SERVER['HTTP_HOST'].'/article/'.$value['categoryyw'].'/'.$value['categoryyw'].'-1.html">'.$value['categoryname'].'</a></span><span>来源:<a href="'.$commonUtil->isHttpsCheckSelect().'//'.$_SERVER['HTTP_HOST'].'/article/cover-page/'.$value['pid'].'.html">'.$value['article_cover'].'</a></span></div></div></div>';
							}
							echo $cinemaHtml;
						?>
					</div>				
				</div>										
			</div>
			
			<div class="cinema-article-container-list col-md-3">
				<div class="cinema-article-container-list-title">
					<h4>猜你喜欢</h4>
				</div>
				<div class="cinema-article-container-list-container">
					<ul class="list-group">
						<?php 
							$cinemaArticleListArray = $theArticleUtil->getCategoryArticle($theCategorySelect['cid'],10,'rand');
							//print_r($cinemaArticleListArray);
							//遍历数组，前端渲染
							foreach($cinemaArticleListArray as $key => $value){
								$cinemaArticleListHtml = '<li class="list-group-item"><a href="'.$commonUtil->isHttpsCheckSelect().'//'.$_SERVER['HTTP_HOST'].'/article/'.$value['categoryyw'].'/'.$value['aid'].'.html">'.$value['title'].'</a></li>';
								echo $cinemaArticleListHtml;
							}
						?>
					</ul>
				</div>						
			</div>					
		</div>
</section>