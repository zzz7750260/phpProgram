$(document).ready(function(){
	loginYZ();
	autoLoad();
	registerControl();
	userControl();	
})

//登录查询用户名是否存在
function loginYZ(){
	//失焦时
	var usernameYzValue,
		passwordYzValue;
	
	$(".tusername").blur(function(){
		var tusernameVal = $(".tusername").val();
		//alert(tusernameVal);
		$.ajax({
			url:'./server/ajax/thelogin.php',
			type:"get",
			dataType:'text',
			data:{username:tusernameVal,turl:'loginusername'},
			success:function(data){
				//alert("这个是ajax返回后的值："+ data)	
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

//注册相关的操作
function registerControl(){
	var registerUsernameYz,resgisterPasswordYz,resgisterPasswordYzYz,resgisterEmailYz;

	//用户名监测
	//聚焦时开始监测
	$('.rUsername').blur(function(){
		var rUsernameVal = $(this).val();
		console.log(rUsernameVal)
		if(!rUsernameVal){
			$('.usernameis').text("用户名不能为空").css("color","#ff0000");	
			registerUsernameYz = false;
		}
		else{
			//当存在时，发送后台检测该用户名是否存在
			$.ajax({
				url:'./server/ajax/thelogin.php',
				data:{turl:"loginusername",username:rUsernameVal},
				type:"get",
				dataType:"text",
				success:function(data){
					console.log("=============后端返回用户存在个数============");
					console.log(data);
					if(data > 0){
						$('.usernameis').text("该用户名已经存在").css("color","#ff0000");
						registerUsernameYz = false;
					}
					else{
						registerUsernameYz = true;					
					}
				}
			})			
		}
		//判断验证
		judgeRegister();
	})
	
	//失焦时消除提示
	$('.rUsername').focus(function(){
		$('.usernameis').text("");
		
	})
	
	//判断密码	
	$(".rPassword").blur(function(){
		var thePassword = $(".rPassword").val();
		console.log(thePassword);
		if(!thePassword){
			$(".passwordis").text("密码不能为空").css("color","#ff0000");	
			resgisterPasswordYz = false;
		}
		else{
			//js正则 密码必须包含字母和数字并且需要在6位数以上高
			var theReg = /^(?=.*[0-9].*)(?=.*[a-zA-Z].*).{6,}$/;
			//正则判断
			var theRegJudge = theReg.test(thePassword);
			if(!theRegJudge){
				$(".passwordis").text("密码必须包含字母").css("color","#ff0000");	
				resgisterPasswordYz = false;
			}
			else{
				resgisterPasswordYz = true;
				
			}
		}
		//判断验证
		judgeRegister();		
	})
	
	$(".rPassword").focus(function(){
		$(".passwordis").text("");	
	})
	
	//判断重复密码
	$(".rPasswordYz").blur(function(){
		var thePasswordYz = $(".rPasswordYz").val(); 
		var thePassword = $(".rPassword").val();
		if(!thePasswordYz){
			$(".passwordisYz").text("该值不能为空格").css("color","#ff0000");			
			resgisterPasswordYzYz = false;
		}
		else{
			if(thePasswordYz !=thePassword){
				$(".passwordisYz").text("两次密码不相同").css("color","#ff0000");		
				resgisterPasswordYzYz = false;
			}
			else{
				resgisterPasswordYzYz = true;			
			}
		}
		//判断验证
		judgeRegister();		
		
	})
	
	$(".rPasswordYz").focus(function(){
		$(".passwordisYz").text("");	
	})
	
	//邮件验证
	$(".rEmail").blur(function(){
		var rEmailValue = $(".rEmail").val();
		if(!rEmailValue){
			$(".eMailisYz").text("email不能为空").css("color","#ff0000");		
			resgisterEmailYz = false;
		}
		else{
			//邮箱验证的正则表达式
			var emailReg = /^[a-zA-Z0-9_-]+@([a-zA-Z0-9]+\.)+(com|cn|net|org)$/;
			var emailRegYz = emailReg.test(rEmailValue);
			if(!emailRegYz){
				$(".eMailisYz").text("email格式不正确").css("color","#ff0000");	
				  resgisterEmailYz = false;				
			}
			else{
				resgisterEmailYz = true;				
			}
		}
		//判断验证
		judgeRegister();
	})
	
	$(".rEmail").focus(function(){
		$(".eMailisYz").text("");	
	})
	
	//公共方法，检测registerUsernameYz,resgisterPasswordYz,resgisterPasswordYzYz,resgisterEmailYz;验证值是否都为true
	function judgeRegister(){
		if(registerUsernameYz && resgisterPasswordYz && resgisterPasswordYzYz && resgisterEmailYz){
			$(".tRegister").prop("disabled","");
		}		
		else{
			$(".tRegister").prop("disabled","disabled");
		}
	}
	
	//在验证成功后,点击向后台提交注册信息
	$(".tRegister").click(function(){
		//alert("点击");
		//获取注册表中的信息
		
		//声明表单数组
		var theRegisterArray = {};				
		$(".register-k").each(function(key,item){
			//console.log(key);
			//console.log(item);
			var theArrayKey = $(this).find(".value-v").attr("name");
			var theArrayValue = $(this).find(".value-v").val();
			
			//填充数组
			theRegisterArray[theArrayKey] = theArrayValue;		
		})
		
		//组装对应的数组
		theRegisterArray['turl'] = "registerAdd";	
		console.log(theRegisterArray);
		
		$.ajax({
			url:"./server/ajax/themember.php",
			data:theRegisterArray,
			type:'post',
			dataType:'json',
			success:function(data){
				console.log("后端返回的注册提示");
				console.log(data);
			}
		})
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
			
		},
		
		//点击图片加载视频
		imgVideoLoad:function(){
			$(".theArticle-imgk").click(function(){
				var articleVideoId = $(this).find('img').data("getid");
				alert(articleVideoId);
				
				//向后台发送视频链接请求
				$.ajax({
					url:"../../server/ajax/thearticle.php",
					data:{turl:"checkArticle",article_Id:articleVideoId},
					type:'get',
					dataType:'json',
					async:false,//设为同步等待视频加载
					success:function(data){
						console.log("============从后端返回的文章详细请求信息===========");
						console.log(data);
						alert(data.result.video_platform)
						
						//通过异步操作将video_platform转到全局数
						that.video_platform = data.result.video_platform;
						
						if(data.status == 200){
							$(".theArticle-imgk").find("img").css("display","none");
							
							//获取图片的宽度
							var videoWidth = $(".theArticle-imgk").width();
							
							//根据video的宽度来自适应高度
							
							var videoHeight = videoWidth/1.6;
							
							//根据返回的视频平台类型组装数据
							switch(data.result.video_platform){
								case 'bilibili':
								var videoHtml = '<iframe class="the-video" id="platform_bilibili" src="'+data.result.video_source+'" scrolling="no" border="0" frameborder="no" framespacing="0" allowfullscreen="true" webkitallowfullscreen="true" mozallowfullscreen="true" allowtransparency="true" style="width:100%;max-height:100%;height:'+videoHeight+'px"> </iframe>';
								//$(videoHtml).appendTo(".theArticle-imgk");								
								break;		
								
								case 'youku':
								var videoHtml = '<embed class="the-video" src="'+data.result.video_source+'" allowFullScreen="true" quality="high" width="'+videoWidth+'px" height="'+videoHeight+'px" align="middle" allowScriptAccess="always" type="application/x-shockwave-flash"></embed>'
								//$(videoHtml).appendTo(".theArticle-imgk");							
								break;
								
								case 'youkuHttps':
								//var videoHtml = '<iframe class="the-video" src="'+data.result.video_source+'" scrolling="no" border="0" frameborder="no" framespacing="0" allowfullscreen="true" webkitallowfullscreen="true" mozallowfullscreen="true" style="width:'+videoWidth+'px;max-height:'+videoHeight+'px;height:'+videoHeight+'px"> </iframe>';
								var videoHtml = '<iframe class="the-video" src="'+data.result.video_source+'" scrolling="no" border="0" frameborder="no" framespacing="0" allowfullscreen="true" webkitallowfullscreen="true" mozallowfullscreen="true" allowtransparency="true" style="width:100%;max-height:100%;height:'+videoHeight+'px"> </iframe>';
								break;
								
								
							}
							
							//将视频加到网站中
							$(videoHtml).appendTo(".theArticle-imgk");
						}
						

					}				
				})
			})
			
			//利用模态框
			$(".theArticle-imgks-img").click(function(){
				//获取到该video的id
				var theVideoId = $(this).data('getvideoid');
				alert(theVideoId);
				
				//打开模态框
				$('#myModal').modal();
							
				//向后端提交视频请求
				$.ajax({
					url:"../../server/ajax/thevideo.php",
					data:{turl:"showVideo",videoId:theVideoId},
					type:"get",
					dataType:"json",
					success:function(data){
						console.log("==========后台传递过来video的信息==============");
						console.log(data);
						//获取返回的状态
						if(data.status == 200){
													
							//获取图片的宽度
							var videoWidth = $(".modal-dialog").width();
							
							//根据video的宽度来自适应高度
							
							var videoHeight = videoWidth/1.6;
							
							console.log(data.result.video_pt);
							//根据返回的视频平台类型组装数据
							switch(data.result.video_pt){
								case 'bilibili':
								var videoHtml = '<iframe class="the-video" id="platform_bilibili" src="'+data.result.video_link+'" scrolling="no" border="0" frameborder="no" framespacing="0" allowfullscreen="true" webkitallowfullscreen="true" mozallowfullscreen="true" allowtransparency="true" style="width:100%;max-height:100%;height:'+videoHeight+'px"> </iframe>';
								//$(videoHtml).appendTo(".theArticle-imgk");								
								break;		
								
								case 'youku':
								var videoHtml = '<embed class="the-video" src="'+data.result.video_link+'" allowFullScreen="true" quality="high" width="'+videoWidth+'px" height="'+videoHeight+'px" align="middle" allowScriptAccess="always" type="application/x-shockwave-flash"></embed>'
								//$(videoHtml).appendTo(".theArticle-imgk");							
								break;
								
								case 'youkuHttps':
								//var videoHtml = '<iframe class="the-video" src="'+data.result.video_source+'" scrolling="no" border="0" frameborder="no" framespacing="0" allowfullscreen="true" webkitallowfullscreen="true" mozallowfullscreen="true" style="width:'+videoWidth+'px;max-height:'+videoHeight+'px;height:'+videoHeight+'px"> </iframe>';
								var videoHtml = '<iframe class="the-video" src="'+data.result.video_link+'" scrolling="no" border="0" frameborder="no" framespacing="0" allowfullscreen="true" webkitallowfullscreen="true" mozallowfullscreen="true" allowtransparency="true" style="width:100%;max-height:100%;height:'+videoHeight+'px"> </iframe>';
								break;	
								
								case "qq":
								var videoHtml = '<iframe class="the-video" src="'+data.result.video_link+'" scrolling="no" border="0" frameborder="no" framespacing="0" allowfullscreen="true" webkitallowfullscreen="true" mozallowfullscreen="true" allowtransparency="true" style="width:100%;max-height:100%;height:'+videoHeight+'px"> </iframe>';
								break;
								
								case "souhu":
								var videoHtml = '<iframe class="the-video" src="'+data.result.video_link+'" scrolling="no" border="0" frameborder="no" framespacing="0" allowfullscreen="true" webkitallowfullscreen="true" mozallowfullscreen="true" allowtransparency="true" style="width:100%;max-height:100%;height:'+videoHeight+'px"> </iframe>';
								break;
								
								case "iqiyi":
								var videoHtml = '<iframe class="the-video" src="'+data.result.video_link+'" scrolling="no" border="0" frameborder="no" framespacing="0" allowfullscreen="true" webkitallowfullscreen="true" mozallowfullscreen="true" allowtransparency="true" style="width:100%;max-height:100%;height:'+videoHeight+'px"> </iframe>';
								break;
								
								default:
								var videoHtml = '<iframe class="the-video" src="'+data.result.video_link+'" scrolling="no" border="0" frameborder="no" framespacing="0" allowfullscreen="true" webkitallowfullscreen="true" mozallowfullscreen="true" allowtransparency="true" style="width:100%;max-height:100%;height:'+videoHeight+'px"> </iframe>';
							}						
										
							//将视频加到模态框中
							//$(videoHtml).appendTo(".modal-body");
							
							//更改模态框的状态
							$(".modal").find(".modal-title").text(data.result.video_name);
							
							//将之前modal-body中的内容清除后，再添加新的视频内容
							$(".modal").find(".modal-body").empty();
							
							console.log(videoHtml);
							$(videoHtml).appendTo(".modal-body");							
						}	
					}
				})
				
				//当模态框关闭后同样将modal-body的内容清空
				$('#myModal').on('hide.bs.modal', function () {
				  // 执行一些动作...
				  	$(".modal").find(".modal-body").empty();
				})
						
				//判断是手机端，ipad，还是pc端
				//var userAgentInfo = navigator.userAgent;  
				//console.log(userAgentInfo);
				
				var theSelfControl = new selfControl();			
				var isPhone = theSelfControl.checkPhoneSide();
				
				console.log("是否移动端：" +isPhone);
				
				//判断为手机端,如果为手机端，将模态框位置进行新定位
				if(isPhone =="Android" || isPhone == "iPhone" || isPhone == "SymbianOS" || isPhone == "Windows Phone"){
					//获取该图的高度
					var imgHeight = $(this).height();
					
					//获取当前图片到顶部的距离			
					var imgTop = $(this).offset().top - imgHeight;
					console.log("移动端图片离顶部的距离:"+ imgTop);
					
					//实机测试暂时使用默认高度
					//$("#myModal").css("top",imgTop);
				}				
			})
								
		}
	}
	theClick.searchClick();
	theClick.commentPush();
	theClick.imgVideoLoad();
	
	//滚动加载
	var theScroll = {
		srollLoadArticle:function(){
			that.pageNum = 1;
			//获取页面的类型（判断是用户页面还是封面页面）
			var theType = $("body").data('type');
			//alert("页面的类型为：" + theType);
			
			//获取用户页面名称或者封面id
			var theValue = $("body").data('value');
			//alert("获取页面的值为：" + theValue);
			
			//鼠标滚动事件
			$(window).scroll(function(){
				var theWindowHeight = $(window).height();
				var theFooterOffset = $(".theFooter").offset().top;
				//console.log("窗口高度为："+theWindowHeight);
				//console.log("底部偏差为："+theFooterOffset);
				var scrollNum = $(window).scrollTop();
				//console.log("鼠标偏移为："+scrollNum);
				//if((scrollNum+theWindowHeight)>theFooterOffset && theWindowHeight<theFooterOffset){
				if((scrollNum+theWindowHeight)>theFooterOffset ){	
					that.pageNum = that.pageNum + 1
					console.log(that.pageNum);
					//向后端发出文章加载请求
					$.ajax({
						url:"../../server/ajax/thearticle.php",
						data:{turl:"ajaxLoadArticle",thePage:that.pageNum,theLimitNum:5,thePageType:theType,thePageValue:theValue},
						type:"get",
						async:false,
						dataType:"json",
						success:function(data){
							console.log("=============滚动请求传递过来的后端数据===============");
							console.log(data);
							//根据数据组装HTML并添加到前端中
							data.result.forEach(function(item){
								var articleJsonHtml ='<li><div class="user-article-k"><div class="user-article-img"><img src="../../upload/cover/'+item.article_img+'"></div><div class="user-article-container"><h3>'+item.title+'</h3></div></div></li>';
								$(articleJsonHtml).appendTo('.user-article-ul');
							})
							
							
							//that.pageNum = that.pageNum + 1;
							
						}				
					})					
							
				}
			})
			
			

		}
		
	}
	
	//按需控制:获取对应的地址进行对应的操作
	var theSelfControl = new selfControl();
	//var theUrlPath = theSelfControl.urlCheck('pathname','user-page')
	//alert("当前的地址为："+theUrlPath);
	if(theSelfControl.urlCheck('pathname','user-page') || theSelfControl.urlCheck('pathname','cover-page') &&!theSelfControl.urlCheck('pathname','list')){
		//alert("触发的地址");	
		
		theScroll.srollLoadArticle();	
	}
	else{
		//alert("不是触发的地址");		
	}
	
	
	
	
	
	//拖动窗口操作
	var dragControl = {
		windowDrag:function(){
			$(window).resize(function () { 
				//获取video的宽度
				var videoWidth = $(".modal-dialog").width() - 30;

				
				var videoHeight = videoWidth/1.6;
				$(".the-video").css({
					"width":videoWidth+"px",
					"height":videoHeight+"px",			
				})
			})		
		}		
	}
	dragControl.windowDrag();
}

//打开时自动加载的
function autoLoad(){
	that = this;
	var theLoad = {
		//文章页面自动加载
		commentLoad:function(){
			//获取加载评论对应的文章id
			var articleId = $('.the-comment-set').data("article");
			that.articleId = articleId;
			alert(articleId);
			if(articleId){
				$.ajax({
					url:"../../server/ajax/thecomment.php",
					type:"get",
					data:{turl:"listComment",getArticleId:articleId,getCommentPage:0,getCommentLimit:2},
					dataType:'json',
					
					success:function(data){
						console.log(data);
						//存在评论的正常返回是200
						if(data.status ==200){
							//遍历data组装html
							data.result.forEach(function(item){
								//data-commentid 存储评论的对应id
								//data-articleid 存储评论的对应的文章的id
								var listHtml = '<li class="article-comment-list-k-li" data-commentid="'+item['cmid']+'" data-articleid="'+item['cmtid']+'"><div class="">'+item['cm_comment']+'</div><input type="button" value="回复" class="article-comment-list-k-li-put"><br/><ul>'+childComment(item['childComment'])+'</ul><hr/></li>';
								$(listHtml).appendTo(".article-comment-list-k");
								
								//console.log(childComment(item['childComment']));
							})									
							
						}
				
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
		
		//阅读量加一
		articleViewAdd:function(){
			//向后端发送阅读量加一的请求
			$.ajax({
				url:'../../server/ajax/thearticle.php',
				data:{turl:'articleViwe',articleId:that.articleId},
				type:'get',
				dataType:'json',
				success:function(data){
					console.log("==============文章浏览量后端返回==============");
					console.log(data);
				}
			})
			
		},
		
						
	}
	theLoad.commentLoad();
	theLoad.articleViewAdd();
}

//按需控制
function selfControl(){
		//检测链接
	//type:为查询url的类型，默认all为查询整个url，pathname只查询partname部分
	//https://www.cnblogs.com/zhabayi/p/6419938.html
	//name:为需要查询的对象
	this.urlCheck = function(type ='all',name){
		var theUrl,judge;
		if(type == 'all'){
			theUrl =  window.location.href;		
		}
		if(type == 'pathname'){
			theUrl = window.location.pathname;
		}
		//return theUrl;
		//判断是否存在某个字段
		if(theUrl.indexOf(name)>-1){
			judge = true;
		}else{
			judge = false;
		}
		return judge;
	}	
	
	
	//检测是否是pc端还是移动端
	this.checkPhoneSide = function(){
		var userAgentInfo = navigator.userAgent;  
		var Agents = new Array("Android", "iPhone", "SymbianOS", "Windows Phone", "iPad", "iPod");  
		var agentinfo = null;  
		for (var i = 0; i < Agents.length; i++) {  
		   if (userAgentInfo.indexOf(Agents[i]) > 0) { 
			   //agentinfo = userAgentInfo; 
			   agentinfo = Agents[i];
			   break; 
		   }  
		}  
		if(agentinfo){
			return agentinfo;
		}else{
			return "PC"; 
		}  
	}
	
}
