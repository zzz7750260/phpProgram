<div class="the-comment">
	<div class="the-comment-set" data-article='<?php echo $value['aid'];?>'>
		<div class="row">
			<span class="col-md-4 col-sm-4 col-xs-4"><input class="comment-v form-control userInput" type="text" name="theName" autocomplete="on"placeholder="请输入用户名"></span>
			<span class="col-md-4 col-sm-4 col-xs-4"><input class="comment-v form-control emailInput" type="text" name="theEmail" autocomplete="on"placeholder="请输入邮箱"></span>
			<span class="col-md-4 col-sm-4 col-xs-4"><input class="comment-v form-control webInput" type="text" name="theWeb" autocomplete="on" placeholder="请输入网站"></span>
			<br/>
			<br/>	
			<div class="clear"></div>
		</div>
		<div>
			<textarea class="comment-v form-control" rows="6"  name="theComment" autocomplete="on" placeholder="请输入回复内容">
				
			</textarea>	
			<br/>	
		</div>
		<div class="">
			<input type="button" class="btn btn-primary" value="提交评论" id="comment-push">
		</div>
	</div>
	<!--回复框，只有点击评论回复的时候才会出现-->
	
	<!--添加窗口回复的模态框-->
	
	<!-- 模态框（Modal） -->
	<div class="modal fade" id="commentModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">回复内容</h4>
				</div>
				<div class="modal-body">
					<div class="the-reply" style="display:none">
						<div class="col-md-12">
							<button type="button" class="close commit-close" aria-hidden="true" >
								&times;
							</button>
						</div>
						<div class="row">		
							<span class="col-md-4 col-sm-4 col-xs-4"><input class="reply-v form-control userInput" type="text" name="theName" autocomplete="on" placeholder="请输入用户名"></span>
							<span class="col-md-4 col-sm-4 col-xs-4"><input class="reply-v form-control emailInput" type="text" name="theEmail" autocomplete="on" placeholder="请输入邮箱"></span>
							<span class="col-md-4 col-sm-4 col-xs-4"><input class="reply-v form-control webInput" type="text" name="theWeb" autocomplete="on" placeholder="请输入网站"></span>
							<div class="clear"></div>
							<br/>	
						</div>
						<div>
							<textarea class="reply-v form-control" rows="6"  name="theComment" autocomplete="on" placeholder="请输入回复内容">
								
							</textarea>
							<br/>			
						</div>
						<div class="">
							<input type="button" class="btn btn-primary" value="提交评论" id="reply-push">
						</div>
					</div>												
				</div>

			</div><!-- /.modal-content -->
		</div><!-- /.modal -->
	</div>
	
</div>