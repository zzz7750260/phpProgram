$(document).ready(function(){
	adminMenuAjax();
	adminRoleAjax();
	adminCategory();
	adminArticle();
})



//获取后台栏目菜单的ajax
function adminMenuAjax(){
	var theData;
	var theAdmin = {
		//获取用户信息	
		theUserInfo:function(){
			$.ajax({
				url:"../server/ajax/thelogin.php",
				type:"get",
				data:{turl:'theUser'},
				dataType:"json",
				async:false,
				success:function(data){
					alert(data);
					console.log(data)
					theData = data;
					var theDataArray = theData;
				}
			})
		},
		
		//根据用户的权限获取菜单信息		
		theMenu:function(){
			alert("获取的权限："+theData.role);
			$.ajax({
				url:"../server/ajax/thelogin.php",
				type:"get",
				data:{getrole:theData.role,getpid:"0",turl:"themenu"},
				dataType:"json",
				success:function(data){
					console.log("==============菜单信息=============");
					console.log(data);	
					var menuData = data;
					
					$.each(menuData,function(index,item){
						console.log("============获取的分类信息===========");
						console.log(item);	
						var theHtml = '<dl id="menu-article"><dt><i class="Hui-iconfont">&#xe616;</i> '+item.menuname+'<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt><dd><ul class="Hui-iconfont-'+item.mid+'"></ul></dd></dl>';
						alert(theHtml)
						$(theHtml).appendTo(".menu_dropdown");
						console.log("=============这个是item.child的值===========")
						console.log(item.child);						
						if(item.child !=null){
							$.each(item.child,function(index,items){																								
								console.log("===============分类信息内的信息==============");
								console.log(items);	
								var theHtml2 = '<li><a href="article-list.html" title="'+items.menuname+'">'+items.menuname+'</a></li>';
								alert(theHtml2);
								$(theHtml2).appendTo(".Hui-iconfont-"+item.mid+"");
							})
						}
					})
				}
			})								
		}
	}
	theAdmin.theUserInfo()
	theAdmin.theMenu()
}

/////////////////////
//管理员管理
/////////////////////
function adminRoleAjax(){
	/////////////////////
	//角色管理
	/////////////////////
	var theRole = {
		addRole:function(){
			alert("点击");
			$('#admin-role-save').click(function(){
				var theRoleName = $('#roleName').val();
				var theRoleYw = $('#roleYw').val();
				var theRoleMs = $('#RoleMs').val();
				var theRoleLmArray = [];
				var theRoleWzArray = [];
				var theRoleYhArray = [];
				
				if(theRoleName =="" || theRoleYw ==""){
					alert("角色名称和角色缩写为必填");
					return false;					
				}
				else{
					//var theRolelm = $("input[name='user-Character-0-0-0']").val();
					
					//获取资讯管理checkbox的值				
					$("input[name='user-Character-0-0-0']:checked").each(function(item){
						//alert("item:"+item);
						//alert($(this).val());					
						var theLmVal = $(this).val();
						theRoleLmArray.push(theLmVal);
						
					})
					
					//获取文章管理checkbox的值
					$("input[name='user-Character-0-1-0']:checked").each(function(item){
						var theWzVal = $(this).val();
						theRoleWzArray.push(theWzVal);
					})
					
					//获取用户中心管理的checkbox的值
					$("input[name='user-Character-1-0-0']:checked").each(function(item){
						var theYhVal = $(this).val();
						theRoleYhArray.push(theYhVal);										
					})
					
					if(theRoleLmArray == ''){
						theRoleLmArray = ['lmcheck']
						
					}
						
					if(theRoleWzArray == ''){
						theRoleWzArray = ['wzcheck']
						
					}
					
					if(theRoleYhArray == ''){
						theRoleYhArray = ['yhcheck']
						
					}
					
					//将数组转成字符串存放数据库
					var stheRoleLmArray = theRoleLmArray.toString();
					var stheRoleWzArray = theRoleWzArray.toString();
					var stheRoleYhArray = theRoleYhArray.toString();
					
					alert(theRoleName);
					alert("栏目权限："+theRoleLmArray);
					alert("文章权限："+theRoleWzArray);
					alert("用户权限："+theRoleYhArray);					
					
					//获取form的数据
					var t= $('#form-admin-role-add').serializeArray();
					console.log(t);
										
					$.ajax({
						url:'../server/ajax/therole.php',
						type:'post',
						data:{turl:'addRole',getRoleName:theRoleName,getRoleYw:theRoleYw,getRoleMs:theRoleMs,getRoleLmQx:stheRoleLmArray,getRoleWzQx:stheRoleWzArray,getRoleYhQx:stheRoleYhArray},
						dataType:'json',
						success:function(data){
							alert(data)
							
						}
						
					})
				}
				window.location.reload();
			})
		},
		listRole:function(){
			$.ajax({
				url:'../server/ajax/therole.php',
				data:{turl:'listRole'},
				//dataType:'json',
				success:function(data){
					console.log("==========获取用户角色列表================");
					console.log(data);
					//后端返回的json数据必须要通过JSON.parse转换成json数据
					var dataJson = JSON.parse(data)
					console.log("===============转换后的json==============");
					console.log(dataJson);
					$.each(dataJson,function(index,item){
						//前端渲染
						$listHtml = '<tr class="text-c"><td><input type="checkbox" value="" name=""></td><td>'+item.rid+'</td><td>'+item.rolename+'</td><td><a href="#">赵六</a>，<a href="#">钱七</a></td><td>'+item.rolems+'</td><td class="f-14"><a title="编辑" href="javascript:;" onclick="admin_role_edit(\'角色编辑\',\'admin-role-add.html?rid='+item.rid+'\',\''+item.rid+'\')" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a> <a title="删除" href="javascript:;" onclick="admin_role_del(this,\''+item.rid+'\')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a></td></tr>';
						
						alert(item);
						$($listHtml).appendTo(".role-table-body");
						
					})					
				}
			})			
		},	
		getEditRole:function(){
			var _this = this;
			//获取链接参数
			var theUrlParams = window.location.search;
			alert("当前链接参数："+theUrlParams);			
			
			if(theUrlParams){
				//根据有没参数判断是增加角色的页面还是更改角色的页面，从而更改button的显示
				$("#admin-role-save").css("display","none");
				$("#admin-role-update").css("display","inline-block");
				
				//使用分类时必须要new一个				
				var theUtil = new util();
				
				//var theUtil = Object.create(theReg);		
				console.log("=============获取链接参数==============");		
				var theUrlParams = theUtil.theReg.getUrlParamsReg("rid");
				console.log(theUrlParams)						
				//alert(theUrlParams);
				
				//根据获取到的参数提交对于id的ajax并返回对于的角色对象
				$.ajax({
					url:'../server/ajax/therole.php',
					type:'get',
					data:{turl:"editRole",getId:theUrlParams},
					dataType:'json',
					async:false,
					success:function(data){
						console.log("============根据链接id获取的参数============");
						console.log(data);					
						//var dataJson = JSON.parse(data)
						//console.log("==============根据链接id获取的参数转换成json===========");
						//console.log(dataJson)
						
						//返回到html中
						alert("获取name：" + data.result[0].rolename);
						$("#roleName").val(data.result[0].rolename);
						$("#roleYw").val(data.result[0].roleyw);
						$("#RoleMs").val(data.result[0].rolems);
						
						//给checkbox赋值
						var theUtil = new util();
						var lmtheArr = theUtil.commUtil.strChangeArr(data.result[0].rolelmqx);	
						var wztheArr = theUtil.commUtil.strChangeArr(data.result[0].rolewzqx);	
						var yhtheArr = theUtil.commUtil.strChangeArr(data.result[0].roleyhqx);
						console.log("返回的数组"+lmtheArr);
						console.log("第一个数组的值"+lmtheArr[0]);
						
						//根据返回的权限对checkbox进行赋值
						$.each(lmtheArr,function(item){
							console.log("循环数组的值："+lmtheArr[item]);
							$('input[name="user-Character-0-0-0"][value='+lmtheArr[item]+']').prop('checked',true);				
						})
					
						$.each(wztheArr,function(item){
							console.log("循环数组的值："+wztheArr[item]);
							$('input[name="user-Character-0-1-0"][value='+wztheArr[item]+']').prop('checked',true);				
						})
						
						$.each(yhtheArr,function(item){
							console.log("循环数组的值："+yhtheArr[item]);
							$('input[name="user-Character-1-0-0"][value='+yhtheArr[item]+']').prop('checked',true);				
						})			
						console.log("================这个是theUrlParams的值================");
						console.log(theUrlParams);
						_this.updataRole(theUrlParams);
					}
				})
				
			}
			else{
				return false;
			}
		},
		
		updataRole:function(rtheId){	
				console.log("=============(外部)rtheId的值==============");
				console.log(rtheId);
				var rId = rtheId
				//数据提交
				$("#admin-role-update").click(function(){
				var theRoleName = $('#roleName').val();
				var theRoleYw = $('#roleYw').val();
				var theRoleMs = $('#RoleMs').val();
				var theRoleLmArray = [];
				var theRoleWzArray = [];
				var theRoleYhArray = [];
							
				//获取checkbox的值
				$("input[name='user-Character-0-0-0']:checked").each(function(item){
					//alert("item:"+item);
					//alert($(this).val());					
					var theLmVal = $(this).val();
					theRoleLmArray.push(theLmVal);
					
				})
				
				//获取文章管理checkbox的值
				$("input[name='user-Character-0-1-0']:checked").each(function(item){
					var theWzVal = $(this).val();
					theRoleWzArray.push(theWzVal);
				})
				
				//获取用户中心管理的checkbox的值
				$("input[name='user-Character-1-0-0']:checked").each(function(item){
					var theYhVal = $(this).val();
					theRoleYhArray.push(theYhVal);										
				})
				
				if(theRoleLmArray == ''){
					theRoleLmArray = ['lmcheck']
					
				}
					
				if(theRoleWzArray == ''){
					theRoleWzArray = ['wzcheck']
					
				}
				
				if(theRoleYhArray == ''){
					theRoleYhArray = ['yhcheck']
					
				}
				
				//将数组转成字符串存放数据库
				var stheRoleLmArray = theRoleLmArray.toString();
				var stheRoleWzArray = theRoleWzArray.toString();
				var stheRoleYhArray = theRoleYhArray.toString();	
				alert("点击获取id"+rtheId);
				console.log("=============(内部)rtheId的值==============");
				console.log(rtheId);
				alert(theRoleName)
				alert(stheRoleLmArray)
				
				
				$.ajax({
					url:'../server/ajax/therole.php',
					type:"post",					
					data:{turl:"updateRole",postId:rtheId,postrolename:theRoleName,postroleyw:theRoleYw,postrolems:theRoleMs,postrolelmqx:stheRoleLmArray,postrolewzqx:stheRoleWzArray,postroleyhqx:stheRoleYhArray},
					dataType:'json',
					async:false,
					success:function(data){
						console.log("===============更改角色返回的数据==============");
						console.log(data);						
					}
					
				})	
				window.location.reload();
			})
		}
		
	}
	
	var menuRole = {
		listMenuRole:function(){
			$.ajax({
				url:'../server/ajax/therole.php',
				type:'get',
				data:{turl:"listRoleMenu",getMpid:0},
				dataType:'json',
				success:function(data){
					console.log("=============用户菜单列表返回================");
					console.log(data);
					$.each(data,function(index,item){
						console.log("================用户菜单循环=============");
						console.log(item);
						
						//HTML渲染
						var menuHtml = '<tr class="text-c"><td><input type="checkbox" value="'+item.mid+'" name=""></td><td>'+item.mid+'</td><td>'+item.mpid+'</td><td>'+item.menuname+'</td><td>'+item.menurole+'</td><td><a title="编辑" href="javascript:;" onclick="admin_permission_edit(\'角色编辑\',\'admin-permission-add.html\',\''+item.mid+'\',\'\',\'450\')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a> <a title="删除" href="javascript:;" onclick="admin_permission_del(this,\''+item.mid+'\')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a></td></tr>';
						
						$(menuHtml).appendTo(".list-menu-qx");
						
						$.each(item.child,function(index,items){
							var childMenuHtml = '<tr class="text-c"><td><input type="checkbox" value="'+items.mid+'" name=""></td><td>'+items.mid+'</td><td>'+items.mpid+'</td><td>'+items.menuname+'</td><td>'+items.menurole+'</td><td><a title="编辑" href="javascript:;" onclick="admin_permission_edit(\'角色编辑\',\'admin-permission-add.html\',\''+items.mid+'\',\'\',\'450\')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a> <a title="删除" href="javascript:;" onclick="admin_permission_del(this,\''+items.mid+'\')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a></td></tr>';
							
							$(childMenuHtml).appendTo(".list-menu-qx");
							
						})
					})
					
				}							
			})			
		},
		
		addMenuRole:function(){
			$("#admin-roleMenu-save").click(function(){
				var MenuName = $("#roleMenuName").val();
				var MenuYw = $("#roleMenuYw").val();
				var MenuMs = $("#RoleMenuMs").val();
				var MenuSelect = $("#roleMenuSelect").find("option:selected").val();
				var	MenuUrl = $("#RoleMenuUrl").val();
				var MenuRoleArray = [];
				$("input[name='menu-Character-0-0-0']:checked").each(function(){
					alert("选择值："+$(this).val());
					MenuRoleArray.push($(this).val());				
				})	
				alert("获取checkbox的值：" + MenuRoleArray);
				alert("获取select的值：" + MenuSelect);
				
				//将数组转成字符串存放到数据库中
				var MenuRoleString = MenuRoleArray.toString();
				
				$.ajax({
					url:'../server/ajax/therole.php',
					type:'post',
					data:{
						turl:"addRoleMenu",
						postMpid:MenuSelect,
						postMenuname:MenuName,
						postMenuyw:MenuYw,
						postMenuurl:MenuUrl,
						postMenurole:MenuRoleString,			
					},
					dataType:'json',
					success:function(data){
						console.log("=================获取返回的添加菜单的信息===============");
						console.log(data)
						
					}					
				})
				window.location.reload();
			})						
		},
		
		
		
		listMenuRoleList:function(){
			var that = this;
			$.ajax({
				url:'../server/ajax/therole.php',
				type:'get',
				dataType:'json',
				data:{turl:"getListRoleMenu"},
				success:function(data){
					console.log("==============获取返回的角色列表================");
					console.log(data);
					//html渲染
					$.each(data,function(index,item){
						var roleListHtml = '<label class=""><input type="checkbox" value="'+item.roleyw+'" name="menu-Character-0-0-0" id="menu-Character-0-0-'+item.rid+'">'+item.rolename+'</label>';
						$(roleListHtml).appendTo(".permission-list-role");
					})	

					that.addMenuRole();
				}
			})			
		},
		
		listMenuRoleFather:function(){
			$.ajax({
				url:'../server/ajax/therole.php',
				type:'get',
				data:{turl:"getListRoleMenuFather"},
				dataType:'json',
				success:function(data){
					console.log("===================获取菜单的父类===================");
					console.log(data);
					var res = data.result;
					//html渲染
					$.each(data.result,function(index,item){
						var selectHtml = '<option value="'+item.mid+'">'+item.menuname+'</option>';
						$(selectHtml).appendTo(".role-menu-select");
					})
					
				}
			})			
		}		
	}
			
	
	theRole.addRole();
	theRole.listRole();
	theRole.getEditRole();
	
	menuRole.listMenuRole();
	menuRole.listMenuRoleList();
	menuRole.listMenuRoleFather();
	//menuRole.addMenuRole()
	//theRole.updataRole(5);
}

/////////////////////
//分类管理
/////////////////////
function adminCategory(){
	var theCategory = {		
		listCategory:function(){
			$.ajax({
				url:"../server/ajax/thecategory.php",
				type:'get',
				data:{
					turl:"listCategory",		
				},
				dataType:"json",
				success:function(data){
					console.log("================分类管理返回的数值===================");					
					console.log(data);	
					
					//html渲染
					$.each(data,function(index,item){
						console.log("============菜单遍历展示=================");
						console.log(item);
						var category_html = '<option value="'+item.cid+'">'+item.categoryname+'</option>';
						$(category_html).appendTo("#category_select");
					})
					
					
					
					
				}
			})			
		},
		//菜单管理页列表
		pageListCategory:function(){
			$.ajax({
				url:"../server/ajax/theCategory.php",
				type:'get',
				data:{
					turl:"pageListCategory",
				},
				dataType:'json',
				success:function(data){
					console.log("===============菜单页面后端传过来的数据=============");
					console.log(data);
					
					//html渲染
					$.each(data,function(index,item){
						var listHtml = '<tr class="text-c"><td><input type="checkbox" value="" name=""></td><td>'+item['cid']+'</td><td class="text-l"><u style="cursor:pointer" class="text-primary" onClick="article_edit(\'查看\',\'article-zhang.html\',\'10001\')" title="查看">'+item['categoryname']+'</u></td><td>'+item['categoryyw']+'</td><td>'+item['cpid']+'</td><td>'+item['categoryms']+'</td><td>aaa.html</td><td class="f-14 td-manage"><a style="text-decoration:none" onClick="article_stop(this,\'10001\')" href="javascript:;" title="下架"><i class="Hui-iconfont">&#xe6de;</i></a><a style="text-decoration:none" class="ml-5" onClick="article_edit(\'资讯编辑\',\'article-add.html\',\'10001\')" href="javascript:;" title="编辑"><i class="Hui-iconfont">&#xe6df;</i></a><a style="text-decoration:none" class="ml-5" onClick="article_del(this,\'10001\')" href="javascript:;" title="删除"><i class="Hui-iconfont">&#xe6e2;</i></a></td></tr>';
						
						$(listHtml).appendTo(".page-category-list");
					})
					
				}
				
			})
			
		}
		
	}
	theCategory.listCategory();
	theCategory.pageListCategory();
		
}


//资讯管理
function adminArticle(){
	//将this赋值于that，引用当前变量的全局变量
	var that = this;
	console.log("============这个为that================");
	console.log(that);
	var theArticle = {
		//GlobalVar:function(){
		//	var pictureName;			
		//},
		addArticle:function(){
			//图片上传预览,将图片转成base64
			$("#article-fileList").change(function(){
				var theFile = this.files[0];
				reader = new FileReader();
				reader.readAsDataURL(theFile);//调用自带方法进行转换    
				reader.onload = function(){
					var theImgSrc = reader.result;
					alert(theImgSrc);	
					$(".show-picture").attr("src",theImgSrc);
				}
				//同时获取上传图片的名称
				//this.GlobalVar.pictureName = theFile['name'];
				that.pictureName = theFile['name'];
				//console.log("图片名称"+this.GlobalVar.pictureName);
				console.log("图片名称"+that.pictureName);
			})
			
			$("#btn-save").on("click",function(){
				saveArticle("draft");
			});
			
			$("#article-save").on("click",function(){
				saveArticle("public");
				
			});
			
			//提交文档的方法，是正式发布还是存草稿
			
			function saveArticle(isPublic){
				var theArticleStatus;
				//存草稿赋值
				if(isPublic == "draft"){
					theArticleStatus == 'false';
					
				}
				if(isPublic == "public"){
					theArticleStatus == 'true';
					
				}
												
				var thePostArray = {};
				$("#form-article-add").find(".row").each(function(){
					var theValue = $(this).find('.value-v').val();
					var theKey = $(this).find('.value-v').attr("name");	
					
					//当为评论状态选择时(input为checkbox)
					if(theKey == "article-pl"){
						//获取是否为选中状态
						var thePL = $(this).find('.value-v').prop('checked');
						alert(thePL)
						if(thePL == true){
							theValue = "true";
						}
						else{
							theValue = "false";
						}
						
					}
					//console.log(theKey +":"+ theValue);
					thePostArray[theKey] = theValue;
				})
				
				console.log("=============获取文章内容==============");
				var ue = UE.getEditor('editor');
				var str = ue.getContent();
				alert(str);	
				console.log("===========获取表单信息==============");
				console.log(thePostArray);
				console.log("图片名称"+that.pictureName);
				
				
				//将提交的数据进行修改
				thePostArray['turl'] = "addArticle";
				thePostArray['article-pic'] = that.pictureName;
				thePostArray['the-article'] = str;
				thePostArray['article-status'] = isPublic;
				console.log("===========修改后的表单信息==============");
				console.log(thePostArray);
				
				
				//向后端提交数据
				$.ajax({
					url:'../server/ajax/thearticle.php',
					type:'post',
					data:thePostArray,
					dataType:'json',
					success:function(data){
						console.log("===========提交文章草稿的返回值============");
						console.log(data);
					}
										
				})
				
			}
		}		
	}
	theArticle.addArticle();
		
}


//正则表达式分类
function util(){
	var that = this;
	this.theReg = {
		//获取链接参数的正则表达式
			getUrlParamsReg:function(name){
			//正则表达式
			reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)","i");
			//window.location.search 获取链接的参数并进行相关的匹配
			var r = window.location.search.substr(1).match(reg);
			if(r != null){
				return unescape(r[2])
			}
			return null;
		}		
	}
	//theReg.getUrlParamsReg(name);
	
	//综合通用
	this.commUtil = {
		strChangeArr:function(theStr){
			var arr = theStr.split(",")	
			return arr;
		}
		
	}			
}