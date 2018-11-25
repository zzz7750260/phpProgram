<?php 
	//获取fm的字category的id
	$theChildCategoryId = $theChildCategoryArray['cid'];
	//echo '获取的id'.$theChildCategoryId;
?>

<section class="fm-part">
	<div class="container fm-part-k">
		<div class="col-md-9 fm-part-k-article">
		
			<div class="fm-part-k-article-title">
				<h3><?php echo $theChildCategoryArray['categoryname'];?></h3>
			</div>
			<div class="fm-part-k-article-list">
				<?php 
					$fmHeaderArray = getFmCategoryArticle($theChildCategoryId,0,6);
					//var_dump($fmHeaderArray);
				?>
				<div class="fm-part-k-article-list-header">
					<?php 
						//遍历数组输出对应的信息
						foreach($fmHeaderArray as $fmHeaderKey => $fmHeaderValue){
													
					?>
							<div class="media col-md-6">
								<div class="media-left">
									<img src="../../upload/fm/<?php echo $fmHeaderValue['f_img'];?>" class="media-object" style="width:180px">
								</div>
								<div class="media-body">
									<h4 class="media-heading"><a href="<?php $theWebPathAfter = '/fm/'. $fmHeaderValue['categoryyw'] . '/fm-' .  $fmHeaderValue['fid'] . '.html'; echo $commonUtil->webPath($theWebPathAfter);?>"><?php echo $fmHeaderValue['f_title'];?></a></h4>
									<p><?php echo $fmHeaderValue['f_short'];?></p>
								</div>
							</div>
				
					<?php } ?>
				</div>
				<!--<div class="fm-part-k-article-list-li row">
					<ul class="col-md-4">
						<?php 
							//$theWebPath = $commonUtil->webPath()
							$fmArticleArray = getFmCategoryArticle($theChildCategoryId,1,5);	
							//遍历数组，渲染html
							foreach($fmArticleArray as $fmArticleKey => $fmArticleValue){
								$theWebPathAfter = '/fm/'. $fmArticleValue['categoryyw'] . '/fm-' .  $fmArticleValue['fid'] . '.html';
								$fmArticleLiHtml = '<li><h5><a href="'.$commonUtil->webPath($theWebPathAfter).'">'.$fmArticleValue['f_title'].'</a></h5></li>';
								echo $fmArticleLiHtml;
							}							
						?>	
										
					</ul>
					
					<ul class="col-md-4">
						<?php 
							//$theWebPath = $commonUtil->webPath()
							$fmArticleArray = getFmCategoryArticle($theChildCategoryId,6,10);	
							//遍历数组，渲染html
							foreach($fmArticleArray as $fmArticleKey => $fmArticleValue){
								$theWebPathAfter = '/fm/'. $fmArticleValue['categoryyw'] . '/fm-' .  $fmArticleValue['fid'] . '.html';
								$fmArticleLiHtml = '<li><h5><a href="'.$commonUtil->webPath($theWebPathAfter).'">'.$fmArticleValue['f_title'].'</a></h5></li>';
								echo $fmArticleLiHtml;
							}							
						?>	
										
					</ul>
					
					<ul class="col-md-4">
						<?php 
							//$theWebPath = $commonUtil->webPath()
							$fmArticleArray = getFmCategoryArticle($theChildCategoryId,11,15);	
							//遍历数组，渲染html
							foreach($fmArticleArray as $fmArticleKey => $fmArticleValue){
								$theWebPathAfter = '/fm/'. $fmArticleValue['categoryyw'] . '/fm-' .  $fmArticleValue['fid'] . '.html';
								$fmArticleLiHtml = '<li><h5><a href="'.$commonUtil->webPath($theWebPathAfter).'">'.$fmArticleValue['f_title'].'</a></h5></li>';
								echo $fmArticleLiHtml;
							}							
						?>	
										
					</ul>
					
					
				</div>	-->							
			</div>		
		</div>		
		<div class="col-md-3">
			<h4>猜你喜欢</h4>
			<ul class="list-group">
				<?php 
					$theArticleRandArray = $theArticleUtil->getCategoryFmArray($theChildCategoryId,6,'random');
					//var_dump($theArticleRandArray);
					//遍历数组，输出对应的文章
					foreach($theArticleRandArray as $theArticleRandKey => $theArticleRandValue){
						//组装成html输出
						$theWebPathAfter = '/fm/'. $theArticleRandValue['categoryyw'] . '/fm-' .  $theArticleRandValue['fid'] . '.html';
						
						$liHtml = '<li class="list-group-item"><a href="'.$commonUtil->webPath($theWebPathAfter).'">'.$theArticleRandValue['f_title'].'</a></li>';
						echo $liHtml;
					}
									
				?>
				<li class="list-group-item">
					<span class="badge">新</span>
					折扣优惠
				</li>
			</ul>		
		</div>
	</div>
</section>
