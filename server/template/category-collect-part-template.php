<?php 
	//$theCategorySelectChild 为外部赋值数组
?>
<section>
	<div class="container cinema-article">
		<div class="cinema-article-container row">
			<div class="cinema-article-container-video col-md-12">
				<div class="cinema-article-container col-md-12">						
					<div class="cinema-article-title row">
						<div class="cinema-article-title-left col-md-2">
							<h3><?php echo $theCategorySelectChild['categoryname'];?></h3>
						</div>
						<div class="cinema-article-title-right col-md-10">
																			
							<span><a href="<?php 
								$theCategoryArray = $theArticleUtil->categoryDetail($theCategorySelectChild['cid']);
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
							$cinemaArticleArray = $theArticleUtil->getCategoryArticle($theCategorySelectChild['cid'],8);
							//print_r($hotArticleArray);
							//遍历数组，将数组组装成html
							foreach($cinemaArticleArray as $key => $value){
								$cinemaHtml .= '<div class="col-sm-6 col-md-3"><div class="thumbnail"><a href="'.$commonUtil->isHttpsCheckSelect().'//'.$_SERVER['HTTP_HOST'].'/article/'.$value['categoryyw'].'/show-'.$value['aid'].'.html"><img src="../../upload/cover/'.$value['article_img'].'" alt="'.$value['title'].'"><h5>'.$value['title'].'</h5></a><div><span>分类:<a href="'.$commonUtil->isHttpsCheckSelect().'//'.$_SERVER['HTTP_HOST'].'/article/'.$value['categoryyw'].'/'.$value['categoryyw'].'-1.html">'.$value['categoryname'].'</a></span><span>来源:<a href="'.$commonUtil->isHttpsCheckSelect().'//'.$_SERVER['HTTP_HOST'].'/article/cover-page/'.$value['pid'].'.html">'.$value['article_cover'].'</a></span></div></div></div>';
							}
							echo $cinemaHtml;
						?>
					</div>				
				</div>										
			</div>
							
		</div>
</section>