<div class="the-comment">
	<div class="the-comment-set" data-article='<?php echo $value['aid'];?>'>
		<div>
			<span>名称：<input class="comment-v" type="text" name="theName" autocomplete="on"></span>
			<span>邮箱：<input class="comment-v" type="text" name="theEmail" autocomplete="on"></span>
			<span>网站：<input class="comment-v" type="text" name="theWeb" autocomplete="on"></span>
			<br/>
			<br/>		
		</div>
		<div>
			<textarea class="comment-v" rows="8" cols="80" name="theComment" autocomplete="on">
				
			</textarea>
			<br/>
			<br/>	
		</div>
		<div class="">
			<input type="button" value="提交评论" id="comment-push">
		</div>
	</div>
	<!--回复框，只有点击评论回复的时候才会出现-->
	<div class="the-reply" style="display:none">
		<div>
			<span>名称：<input class="reply-v" type="text" name="theName" autocomplete="on"></span>
			<span>邮箱：<input class="reply-v" type="text" name="theEmail" autocomplete="on"></span>
			<span>网站：<input class="reply-v" type="text" name="theWeb" autocomplete="on"></span>
			<br/>
			<br/>		
		</div>
		<div>
			<textarea class="reply-v" rows="8" cols="80" name="theComment" autocomplete="on">
				
			</textarea>
			<br/>
			<br/>	
		</div>
		<div class="">
			<input type="button" value="提交评论" id="reply-push">
		</div>
	</div>
</div>