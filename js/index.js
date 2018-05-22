$(document).ready(function(){
	loginYZ();
	autoLoad();
	userControl();	
})

//登录查询用户名是否存在
function loginYZ(){
	//失焦时
	var usernameYzValue,
		passwordYzValue;
	
	$(".tusername").blur(function(){
		var tusernameVal = $(".tusername").val();
		alert(tusernameVal);
		$.ajax({
			url:'./server/ajax/thelogin.php',
			type:"get",
			dataType:'text',
			data:{username:tusernameVal,turl:'loginusername'},
			success:function(data){
				alert("这个是ajax返回后的值："+ data)	
				if(data == 0){
					$(".usernameis").text("该用户名不存在").css("color","#ff0000");
					usernameYzValue = false;			
				}
				else{
					usernameYzValue = true;						
				}
			}
		})
		
		if(passwordYzValue == true && usernameYzValue == true){		
			$(".tlogin").prop("disabled","");		
		}
	})
	
	//聚焦时
	$(".tusername").focus(function(){
		$(".usernameis").text("")		
	})
	
	$(".tpassword").blur(function(){
		var tpasswordVal = $(".tpassword").val();
		//alert("密码值为:"+tpasswordVal);
		if(tpasswordVal ==""){
			$(".passwordis").text("密码不能为空").css("color","#ff0000");	
			passwordYzValue = false
		}
		else{		
			passwordYzValue = true
		}
		
		if(passwordYzValue == true && usernameYzValue == true){		
			$(".tlogin").prop("disabled","");		
		}
	})
	
	$(".tpassword").focus(function(){
		$(".passwordis").text("");		
	})	
	
}

function userControl(){
	that = this;
	var theClick = {
		//搜索提交
		searchClick:function(){
			//设置搜索的方法
			function theSearch(inputVal){
				//获取连接
				var theUrl = window.location.href;
				//页面跳转
				window.location.href= theUrl+"server/template/search.php?&getPage=0&getLimit=2&getKeyWord="+inputVal; 
			}
			
			//进行相关操作
			$('.the-button').on('click',function(){
				//获取input的value值
				var theInputV = $('.form-control').val()
				alert(theInputV);
				//调用跳转
				theSearch(theInputV)
			})
		},
		
		//评论提交
		commentPush:function(){
			$('#comment-push').click(function(){				
				//获取data中的文章id
				var articleId = $('.the-comment-set').data("article")
				alert(articleId);
				
				//声明获取信息的数组
				var commentArray = {};
				//获取评论中的相关信息
				$(".the-comment-set").find(".comment-v").each(function(){
					var theName = $(this).attr("name");
					var theVal = $(this).val();
					commentArray[theName] = theVal;
				})
				
				commentArray['articleId'] = articleId;
				commentArray['turl'] = "insertComment";
				console.log("=============获取到的评论信息===========");
				console.log(commentArray);
				
				//将获取到的信息提交到后台
				$.ajax({
					url:'/program/server/ajax/thecomment.php',
					type:'post',
					data:commentArray,
					dataType:'json',
					success:function(data){
						console.log("=============插入信息返回的状态=============");
						console.log(data);
					}
				})
				
			})
			

			//评论回复
			//需要用$(document)进行重新绑定
			$(document).on('click','.article-comment-list-k-li-put',function(){
				//alert("aa");
				
				var commentId = $(this).parent().data("commentid");
				var articleId = $(this).parent().data("articleid");
				console.log("评论的id："+commentId);
				console.log("文章的id："+articleId);
				
				$(".the-reply").css("display","block");		
				
				//提交评论的回复
				$("#reply-push").click(function(){
					//声明需提交的数据组
					var replyArray = {};
					
					//获取评论回复的相关信息
					$('.the-reply').find('.reply-v').each(function(){
						//获取每项的name
						var replyName = $(this).attr("name");
						
						//获取每项的value
						var replyVal = $(this).val();
						replyArray[replyName] = replyVal;
					})
					replyArray['turl'] = 'insertComment';
					replyArray['theCPid'] = commentId;
					replyArray['articleId'] = articleId;
					
					console.log("===========组装的数据值为===========");
					console.log(replyArray);
					
					//向后端提交ajax
					$.ajax({
						url:"../../server/ajax/thecomment.php",
						type:"post",
						data:replyArray,
						dataType:"json",
						success:function(data){
							console.log(data);
						}					
					})
				})				
			})						
			
		}
	}
	theClick.searchClick();
	theClick.commentPush();
	
}

//打开时自动加载的
function autoLoad(){
	var theLoad = {
		//文章页面自动加载
		commentLoad:function(){
			//获取加载评论对应的文章id
			var articleId = $('.the-comment-set').data("article")
			alert(articleId);
			if(articleId){
				$.ajax({
					url:"../../server/ajax/thecomment.php",
					type:"get",
					data:{turl:"listComment",getArticleId:articleId,getCommentPage:0,getCommentLimit:2},
					dataType:'json',
					success:function(data){
						console.log(data);
						//遍历data组装html
						data.result.forEach(function(item){
							//data-commentid 存储评论的对应id
							//data-articleid 存储评论的对应的文章的id
							var listHtml = '<li class="article-comment-list-k-li" data-commentid="'+item['cmid']+'" data-articleid="'+item['cmtid']+'"><div class="">'+item['cm_comment']+'</div><input type="button" value="回复" class="article-comment-list-k-li-put"><br/><ul>'+childComment(item['childComment'])+'</ul><hr/></li>';
							$(listHtml).appendTo(".article-comment-list-k");
							
							//console.log(childComment(item['childComment']));
						})				
					}
				})				
			}	

			//评论回复
			function childComment(childCommentArray){
				var childHtml ='';
				if(childCommentArray.length > 0){
					
					childCommentArray.forEach(function(item){
						
						//console.log(typeof(item));
						if(typeof(item) != "undefined"){
							//console.log(item);
							childHtml += '<li class="article-child-comment-list-k-li" data-commentid="'+item['cmid']+'" data-articleid="'+item['cmtid']+'"><div class="">'+item['cm_comment']+'</div><input type="button" value="回复" class="article-comment-list-k-li-put"><br/><hr/></li>';														
						}

					})	
					
					
				}	
				//console.log(childHtml)
				return childHtml;
			}
		},
		
	}
	theLoad.commentLoad();
}