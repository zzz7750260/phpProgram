<footer>
	<div class="friendLink theFooter">
		<div class="container">
			<div class="friendLink-container">
				友情链接：
				<?php  
					$selfUrl = $_SERVER['PHP_SELF'];
					//echo $selfUrl;
					if($selfUrl == '/server/ajax/theindex.php'){
						//循环获取链接
						$friendLinkSql = "select * from friend_link where 1 = 1";
						$friendLinkSql_db = mysql_query($friendLinkSql);
						$friendLinkArray = array();
						while($friendLinkSql_db_array = mysql_fetch_assoc($friendLinkSql_db)){
							$friendLinkArray[] = $friendLinkSql_db_array;				
						}
						//循环数组，渲染html
						foreach($friendLinkArray as $key => $friendLinkValue){
							$friendHtml = '<span style="margin:10px"><a href="'.$friendLinkValue['flink'].'" rel="nofollow">'.$friendLinkValue['ftitle'].'</a></span>';
							echo $friendHtml;
						}
						
					}
					

				?>
			</div>
		</div>
	</div>
	<div class="about">
		<div class="container">
			<span>关于我们</span> | <span>关于我们</span>
		</div>
	</div>	
</footer>