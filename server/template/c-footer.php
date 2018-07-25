<footer>
	<div class="friendLink">
		<div class="container">
			<div class="friendLink-container">
				友情链接：
				<?php  
					//循环获取链接
					$friendLinkSql = "select * from friend_link where 1 = 1";
					$friendLinkSql_db = mysql_query($friendLinkSql);
					$friendLinkArray = array();
					while($friendLinkSql_db_array = mysql_fetch_assoc($friendLinkSql_db)){
						$friendLinkArray[] = $friendLinkSql_db_array;				
					}
					//循环数组，渲染html
					foreach($friendLinkArray as $key => $value){
						$friendHtml = '<span style="margin:10px"><a href="'.$value['flink'].'" rel="nofollow">'.$value['ftitle'].'</a></span>';
						echo $friendHtml;
					}
				?>
			</div>
		</div>
	</div>
	<div class="about">
		<div class="container">
			
		</div>
	</div>	
</footer>