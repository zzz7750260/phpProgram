$(document).ready(function(){
	init_load();
	//adminMenuAjax();
	//adminRoleAjax();
	//menuControl()
	//adminCategory();
	//adminArticle();
	//systemControl();
	//memberControl();
	//coverControl();
	//friendLinkControl();
	//emailControl();
	//userIndexPage();
	//fmControl();
})

//公共模块获取用户信息,初始化全局变量
var theAllUtil = new util();
var theAllUserInfo = theAllUtil.memberUtil.theMemberYz();

//页面加载（核心）
function init_load(){
	//获取加载参数
	var loadParams;
	var urlParams = window.location.pathname;
	//alert("链接aaa:"+ urlParams);
	
	//公共加载
	adminMenuAjax();
	
	//首页加载
	if(window.location.pathname == '/admin/' || window.location.pathname == '/admin/index-user.html' ){
		userIndexPage();
	}
	
	//资讯加载
	//loadParams = theAllUtil.theReg.selfControl('pathname','article');
	//console.log("资讯参数:"+loadParams);
	if(theAllUtil.theReg.selfControl('pathname','article')){
		adminArticle();
		fmControl();
	}
	
	//系统操作加载
	if(theAllUtil.theReg.selfControl('pathname','system')){
		systemControl();		
	}
	
	//用户系统加载
	if(theAllUtil.theReg.selfControl('pathname','member')){
		memberControl();
		//邮箱验证
		emailControl()
	}
	
	//权限系统加载
	if(theAllUtil.theReg.selfControl('pathname','role')){
		adminRoleAjax();
	}
	
	//菜单系统加载
	if(theAllUtil.theReg.selfControl('pathname','menu')){
		menuControl();
		//adminArticle
	}	
	
	
}


//在进入后台后的直接操作
//获取后台栏目菜单的ajax
function adminMenuAjax(){
	//引入通用模块
	var theUtil = new util();
	that = this;
	var theData;
	var theAdmin = {
		theYzLogin:function(){		
			var userInfoData = theAllUserInfo;
			
			//var userInfoData = theUtil.memberUtil.theMemberYz();
			//console.log("获取用户验证信息");
			//console.log(userInfoData);
			//根据返回的信息对链接进行跳转
			//如果返回的status不是200，都为非法登录，均跳转到错误页面
			if(userInfoData.status != 200){
				window.location.href = "./404.html";				
			}
			else{
				//在加载验证后，将加载层消除
				$("#preloader").css("display","none");
			}
			
		},
		
		//验证用户是否登录后再进行操作
		/*
		theYzLogin:function(){
			var userInfoData = theUtil.memberUtil.theMemberYz();
			console.log("获取用户验证信息");
			console.log(userInfoData);
			//向后台发出请求
			$.ajax({
				url:"../server/ajax/thelogin.php",
				data:{turl:"loginYz"},
				type:"get",
				dataType:"json",
				
				success:function(data){
					console.log("=============后端获取的验证信息==============");
					console.log(data);
					//根据返回的信息对链接进行跳转
					//如果返回的status不是200，都为非法登录，均跳转到错误页面
					if(data.status != 200){
						window.location.href = "./404.html";				
					}
					else{
						//在加载验证后，将加载层消除
						$("#preloader").css("display","none");
					}

					
				}
			})		
		},	
		*/
		
		//获取用户信息	
		theUserInfo:function(){
			$.ajax({
				url:"../server/ajax/thelogin.php",
				type:"get",
				data:{turl:'theUser'},
				dataType:"json",
				async:false,
				success:function(data){
					//alert(data);
					//console.log("============用户返回信息=============");
					//console.log(data)
					theData = data;
					var theDataArray = theData;
									
					//组装框架html() 
					$(".roleZh").text(theData.rolename);
					$(".roleName").text(theData.username);
					if(theData.role == 'admin'){
						var menuListHtml = '<li><a href="javascript:;" onclick="member_add(\'添加用户\',\'member-add.html\',\'\',\'510\')"><i class="Hui-iconfont">&#xe60d;</i> 用户</a></li>';
						$(menuListHtml).appendTo(".user-info-add");						
					}									
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
						//console.log("============获取的分类信息===========");
						//console.log(item);	
						var theHtml = '<dl id="menu-article"><dt><i class="Hui-iconfont">&#xe616;</i> '+item.menuname+'<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt><dd><ul class="Hui-iconfont-'+item.mid+'"></ul></dd></dl>';
						//alert(theHtml)
						$(theHtml).appendTo(".menu_dropdown");
						//console.log("=============这个是item.child的值===========")
						//console.log(item.child);						
						if(item.child !=null){
							$.each(item.child,function(index,items){																								
								//console.log("===============分类信息内的信息==============");
								//console.log(items);	
								var theHtml2 = '<li><a href="'+items.menuurl+'" title="'+items.menuname+'">'+items.menuname+'</a></li>';
								//alert(theHtml2);
								$(theHtml2).appendTo(".Hui-iconfont-"+item.mid+"");
							})
						}
					})
				}
			})
			
			//点击菜单栏
			$(document).on("click",".menu_dropdown dl",function(){
				$(".menu_dropdown dl").find('dt').removeClass('selected');
				$(".menu_dropdown dl").find('dd').css({
					"display":"none",
				});
				$(this).find('dt').addClass('selected');
				$(this).find('dd').css({
					"display":"block",
				});
			})
			//$(".menu_dropdown").find('dl').click(function(){

			//})
			
			
		}
		
	}
	theAdmin.theUserInfo();
	theAdmin.theMenu();
	theAdmin.theYzLogin();
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
					//alert("栏目权限："+theRoleLmArray);
					//alert("文章权限："+theRoleWzArray);
					//alert("用户权限："+theRoleYhArray);					
					
					//获取form的数据
					var t= $('#form-admin-role-add').serializeArray();
					//console.log(t);
										
					$.ajax({
						url:'../server/ajax/therole.php',
						type:'post',
						data:{turl:'addRole',getRoleName:theRoleName,getRoleYw:theRoleYw,getRoleMs:theRoleMs,getRoleLmQx:stheRoleLmArray,getRoleWzQx:stheRoleWzArray,getRoleYhQx:stheRoleYhArray},
						dataType:'json',
						success:function(data){
							console.log("============添加角色返回数据===============");
							console.log(data);
							if(data.status == 200){
								layer.msg('角色添加成功');							
							}
							else{
								layer.msg('角色添加失败');	
							}
							//alert(data)
							
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
						$listHtml = '<tr class="text-c"><td><input type="checkbox" value="" name=""></td><td>'+item.rid+'</td><td>'+item.rolename+'</td><td><a href="#">赵六</a>，<a href="#">钱七</a></td><td>'+item.rolems+'</td><td class="f-14"><a title="编辑" href="javascript:;" onclick="admin_role_edit(\'角色编辑\',\'role-add.html?rid='+item.rid+'\',\''+item.rid+'\')" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a> <a title="删除" href="javascript:;" onclick="admin_role_del(this,\''+item.rid+'\')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a></td></tr>';
						
						//alert(item);
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
				if(theUrlParams.indexOf("rid")>-1){
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
	
	if(theAllUtil.theReg.selfControl('pathname','role-list')){
		theRole.listRole();
	}	
	
	if(theAllUtil.theReg.selfControl('pathname','role-add')){
		theRole.addRole();		
		theRole.getEditRole();
	}
}

////////////////////////
//菜单管理系统
////////////////////////
function menuControl(){
	var menuRole = {
		listMenuRole:function(){
			$.ajax({
				url:'../server/ajax/therole.php',
				type:'get',
				data:{turl:"listRoleMenu",getMpid:0},
				dataType:'json',
				success:function(data){
					//console.log("=============用户菜单列表返回================");
					//console.log(data);
					$.each(data,function(index,item){
						//console.log("================用户菜单循环=============");
						//console.log(item);
						
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
	
	if(theAllUtil.theReg.selfControl('pathname','menu-list')){
		menuRole.listMenuRole();		
	}
	
	if(theAllUtil.theReg.selfControl('pathname','menu-add')){
		menuRole.listMenuRoleList();
		menuRole.listMenuRoleFather();
	}
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
					//$.each(data,function(index,item){
					//	console.log("============菜单遍历展示=================");
					//	console.log(item);
					//	var category_html = '<option value="'+item.cid+'">'+item.categoryname+'</option>';
					//	$(category_html).appendTo("#category_select");
					//})
					
					
					
					
				}
			})			
		},
		//菜单管理页列表
		pageListCategory:function(){
			$.ajax({
				url:"../server/ajax/thecategory.php",
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

	//引入通用模块
	var theUtil = new util(); 
	
	var theArticle = {
		//GlobalVar:function(){
		//	var pictureName;			
		//},
		addArticle:function(){
			//通过公共模块获取用户信息
			var getMemberInfo = theAllUserInfo;
			
			//var getMemberInfo = theUtil.memberUtil.theMemberYz();
			
			//通过公共模块确认是否存在参数，如果存在参数，就为编辑，没有为添加
			var getArticleId = theUtil.theReg.getUrlParamsReg("editArticleId");
			alert("该文章的id为"+getArticleId);
			
			
			//初始化百度富文本编辑器
			//var ue = UE.getEditor('editor');
					
			
			//通过公共模块获取封面信息,并对HTML进行组装
			var theCoverInfo = theUtil.memberUtil.getCoverInfo();
			console.log("===============外部的封面列表============");
			console.log(theCoverInfo);
			theCoverInfo.result.forEach(function(item){
				var coverHtml = '<option value="'+item['ptitle']+'">'+item['ptitle']+'</option>';
				$(coverHtml).appendTo("#article-cover");
			})
			
			//通过公共模块获取分类列表，并进行html组装
			var getCategoryList = theUtil.categoryUtil.getListCategory();
			console.log("==========(外部)分类列表===========");
			console.log(getCategoryList);
			getCategoryList.forEach(function(item){
				var categoryHtml = '<option value="'+item['cid']+'">'+item['categoryname']+'</option>';
				$(categoryHtml).appendTo("#category_select");
			})
			
			//通过公共数据：用户数据来组装文章作者
			$("#article-author").val(theAllUserInfo.article_author);
			
			
			
			if(getArticleId){
				//如果有参数，为编辑文章
				//公共模块：根据id获取文章的详细信息
				var getArticleInfo = theUtil.commAricle.getEditArticleInfo(getArticleId);
				
				console.log("=========外部获取的编辑文章信息===============");
				console.log(getArticleInfo);
				
				//公共模块，根据id获取文章对应的video信息
				var getArticleVideoInfoArray = theUtil.commAricle.getEditArticleVideo(getArticleId);
				console.log("=========外部获取的编辑video信息===============");
				console.log(getArticleVideoInfoArray);				
				
				//根据获取到的数值渲染到对应的表单中
				var theData = getArticleInfo.result;			
				$("#article-title").val(theData.title);
				$("#article-short-title").val(theData.short_title);
				//select 获取选中值
				$("#category_select").find("option[value="+theData.category_id+"]").attr("selected",true);
				//$("#category_select").val(theData.category_id);	
				$("#article-keyword").val(theData.article_key);
				$("#article-short").val(theData.article_short);
				$("#article-cover").find("option[value="+theData.article_cover+"]").attr("selected",true);
				$("#article-author").val(theData.article_author);
				//checkbox复选框
				if(theData.article_status){
					$("input:checkbox[name='article-pl']").attr("checked",true);
				}
				$("#datemin").val(theData.commit_start);
				$("#datemax").val(theData.commit_end);
				
				//图片赋值
				$("#article-file").val(theData.article_img)
				$("#article-file").attr("value",theData.article_img);
				$(".show-picture").attr("src","../upload/cover/"+theData.article_img+"");
				
				//平台选择
				$("#video-platform").find('option[value="'+theData.video_platform+'"]').attr("selected",true);
				$("#video-source").val(theData.video_source);
				
				//标签赋值
				$("#article-tag").val(theData.article_tag)
								
				//百度富文本赋值
				ue.addListener("ready", function () {
			　　　　// editor准备好之后才可以使用
			　　　ue.setContent(theData.article_container);

			　　});
			
				//遍历video的数组，将值填入视频输入框中
				$(".theVideo").empty();
				getArticleVideoInfoArray.forEach(function(item,key){
					var listHtml = '<div class="row video-k"><label class="form-label col-xs-4 col-sm-2 col-md-2">视频添加：</label><div class="video-put col-xs-8 col-sm-9 col-md-8"><div class="video-id video-value" name="video-id" data-videoid="'+item.vid+'">'+item.vid+'</div><input type="text" class="input-text video-value col-md-2 col-sm-2" value="'+item.video_name+'" placeholder="输入视频名称" name="video-name"><hr/><select name="video-platform" class="select select-video video-value col-md-2 col-sm-2" value="'+item.video_pt+'"><option value="none">全部栏目</option>								<option value="youkuHttps">├优酷</option>	<option value="bilibili">├哔哩哔哩</option><option value="baidu">├百度视频</option>					<option value="qq">├腾讯</option><option value="souhu">├搜狐</option><option value="iqiyi">├爱奇艺</option>						</select><hr/><input type="text" class="input-text video-value col-md-8 col-sm-8" value="'+item.video_link+'" placeholder="输入视频链接" name="video-link"><span class="video-btn-upload btn-upload form-group"><input id="video-file" class="input-text upload-url radius video-value the-video-file" type="text" name="video-pic"readonly value="'+item.video_img+'" placeholder="输入视频图片"><a href="javascript:void();" class="btn btn-primary radius "><i class="iconfont">&#xf0020;</i> 浏览文件</a><input type="file" id="video-fileList" multiple name="video-pic-file" class="video-input-filevideo-value input-file" data-imgbase>								 			</span>	<div class="video-img col-md-12"><div class="video-img-show col-md-4"><img src="../upload/video/'+item.video_img+'" class="img-responsive"></div>	<div style="clear:both"></div></div></div><div class="video-close col-md-1 col-sm-1">X</div><div style="clear:both"></div></div><div style="clear:both"></div>';
									
					$(listHtml).appendTo(".theVideo");
					$(".select-video").eq(key).find("option[value="+item.video_pt+"]").attr("selected",true);
					//$(listHtml).find(".select-video").val(item.video_pt);
				})
							
				//将提交按钮换为编辑按钮
				$(".article-put-group").css("display","none");
				$(".article-edit-group").css("display","block");
				
				//将需要编辑的文章编号存储到全局
				that.editArticleId =  theData.aid;
				
			}				
			
			console.log("得到用户信息："+ getMemberInfo.result.username)
			//将获取到的用户信息的用户名填到对应的用户框中
			$("input[name='article-author']").val(getMemberInfo.result.username);
			
			
			//图片上传预览,将图片转成base64
			$("#article-fileList").change(function(){
				var theFile = this.files[0];
				console.log("==============图片中的信息=============")
				console.log(theFile);
				reader = new FileReader();
				reader.readAsDataURL(theFile);//调用自带方法进行转换    
				reader.onload = function(){
					var theImgSrc = reader.result;
					alert(theImgSrc);	
					$(".show-picture").attr("src",theImgSrc);
					//将获取到的图片base64传递到外部对象
					that.baseImgurl = theImgSrc;
					
					$("#article-fileList").attr("value",theImgSrc);
					$("#article-file").attr("value",theFile['name']);
					
				}
				//同时获取上传图片的名称
				//this.GlobalVar.pictureName = theFile['name'];
				that.pictureName = theFile['name'];
				console.log("图片名称："+that.pictureName);
				//$("#article-file").val(that.pictureName);
				$("#article-file").attr("value",that.pictureName);
				//$("#article-file").text("value",that.pictureName);
				var articlePicName = $("#article-file").attr("value");
				console.log("图片框名称："+articlePicName);
				
				//console.log("图片名称"+this.GlobalVar.pictureName);
				//console.log("图片名称"+that.pictureName);
			})
			
			//上传封面图
			$("#article-pic-push").click(function(){
				//获取图片的base64
				var baseImg = that.baseImgurl;
				//获取图片的名称
				var imgName = that.pictureName;
				alert(baseImg);
				//将base64向后台提交
				$.ajax({
					url:'../server/ajax/thearticle.php',
					data:{turl:'getBaseImgSave',getBaseImg:baseImg,getImgName:imgName},
					type:'post',
					//dataType:'json',
					success:function($data){
						console.log($data);
					}
				})
			})
			
			
			//继续添加视频
			$("#video-add").click(function(){
				var videoHtml = '<div class="row video-k"><label class="form-label col-xs-4 col-sm-2 col-md-2">视频添加：</label><div class="video-put col-xs-8 col-sm-9 col-md-8"><input type="text" class="input-text video-value col-md-2 col-sm-2" value="" placeholder="输入视频名称"  name="video-name"><hr/> <select name="video-platform" class="select video-value col-md-2 col-sm-2"><option value="none">全部栏目</option><option value="youkuHttps">├优酷</option><option value="bilibili">├哔哩哔哩</option><option value="baidu">├百度视频</option><option value="qq">├腾讯</option><option value="souhu">├搜狐</option><option value="iqiyi">├爱奇艺</option></select><hr/><input type="text" class="input-text video-value col-md-8 col-sm-8" value="" placeholder="输入视频链接"  name="video-link"><span class="video-btn-upload btn-upload form-group"><input id="video-file" class="input-text upload-url radius video-value the-video-file" type="text" name="video-pic"  readonly value="" placeholder="输入视频图片"><a href="javascript:void();" class="btn btn-primary radius "><i class="iconfont">&#xf0020;</i> 浏览文件</a><input type="file" id="video-fileList" multiple name="video-pic-file" class="video-input-file video-value input-file" data-imgbase></span><div class="video-img col-md-12"><div class="video-img-show col-md-4"><img src="" class="img-responsive"></div><div style="clear:both"></div></div></div><div class="video-close col-md-1 col-sm-1">X</div><div style="clear:both"></div></div>';
				
				//$(".video-k").clone().appendTo(".theVideo");
				
				$(videoHtml).appendTo(".theVideo");
				
			})
			
			//消除增加的视频链接
			$(document).on("click", ".video-close", function(){
				$(this).parent().remove();
			})	

			
			//获取视频的值
			$("#video-get").click(function(){
				var videoArray = {};			
				var n = 0;
				$(".theVideo").find(".video-k").each(function(keyFather,itemFather){
					
					
					var videoChildrenArray = {};  //必须要对前一个值清除，否则就会导致最后一个循环值存在
					//console.log(key);
					//console.log(itemFather);
					
					$(itemFather).find(".video-put").find(".video-value").each(function(key,item){
						//console.log(key);
						//console.log(item);	
						
						//获取各个框的值并组成一个数组
						var videoChildrenKey = $(item).attr("name");		
						var videoChildrenValue = $(item).val();
						if(videoChildrenKey == "video-pic"){
							videoChildrenValue = $(item).attr("value");
						}
						if(videoChildrenKey == "video-pic-file"){
							videoChildrenValue = $(item).data("imgbase");
						}
						if(videoChildrenKey == "video-id"){
							videoChildrenValue = $(item).data("videoid");							
						}
						videoChildrenArray[videoChildrenKey] = videoChildrenValue;
						
						//that.videoChildrenArray = videoChildrenArray;
						
						//将这个数据的值存放在父类数组中		
																		
					})	
					console.log(keyFather);	
					console.log(videoChildrenArray);	
					//console.log(n);
					videoArray[keyFather] = videoChildrenArray;
					//console.log(videoArray);			
					
				})
				
				console.log(videoArray);		
				//将获取组装好的数组存储到全局变量
				that.videoArray = videoArray;
				//console.log(that);
				
				//将获取到的数据提交到后台
				$.ajax({
					url:"../server/ajax/thevideo.php",
					data:{turl:"addVideo",theVideoArray:that.videoArray},
					type:"post",
					dataType:"json",
					success:function(data){
						console.log("===========后端返回的video数据===============");
						console.log(data)
					}
					
				})
				
			})
			
			//获取视频的图片
			$(document).on("change",".video-input-file",function(){
				//console.log(that);
				
				var index = $(this).parents(".video-k").index();
				console.log(index);						
				//图片上传转为base64
				var file = this.files[0];
				
				console.log(file);
					
				//获取兄弟节点
				var theVideoInputHtml = $(this).siblings(".the-video-file");				
				//console.log(theVideoInputHtml);	
				var theVideoShowHtml = $(this).parent().siblings(".video-img");	
				
				$(theVideoInputHtml).attr("value",file['name']);
				
				var theValue = $(theVideoInputHtml).attr("value");
				console.log(theValue);
				
				var reader = new FileReader();
				reader.readAsDataURL(file);
				//var self = this;
				reader.onload = function(e){
					//console.log(that);
					
					//获取兄弟节点,并将获取到图片的的值传递给兄弟节点
					//获取到base64的值
					var imgBase = this.result;
					//console.log(imgBase);
					$(theVideoShowHtml).find("img").attr("src",this.result);
					
					//将base64储存到全局变量
					that.imgBaseShow = imgBase;
					//将获取到的base存到input中方便提取	
					//console.log("内："+that.imgBaseShow);		

					//console.log($(this));
					if(index !=0){
						index = index-1;
					}
					$(".video-input-file").eq(index).attr("data-imgbase",that.imgBaseShow);
					
				}
				
				//这里因为是异步的问题，所以外部不能取到值
				//console.log("外：" + that.imgBaseShow);
				//$(this).attr("data-imgbase",that.imgBaseShow);
				
			})
			
			//$(".video-input-file").click(function(){
			//	var index = $(this).index();
			//	console.log(index);	
			//})
			
			
			
			//封装获取视频信息
			function getVideoInfoArray(articleId,articleType = 'add'){
				var videoArray = {};			
				var n = 0;
				$(".theVideo").find(".video-k").each(function(keyFather,itemFather){
					
					
					var videoChildrenArray = {};  //必须要对前一个值清除，否则就会导致最后一个循环值存在
					//console.log(key);
					//console.log(itemFather);
					
					$(itemFather).find(".video-put").find(".video-value").each(function(key,item){
						//console.log(key);
						//console.log(item);	
						
						//获取各个框的值并组成一个数组
						var videoChildrenKey = $(item).attr("name");		
						var videoChildrenValue = $(item).val();
						if(videoChildrenKey == "video-pic"){
							videoChildrenValue = $(item).attr("value");
						}
						if(videoChildrenKey == "video-pic-file"){
							videoChildrenValue = $(item).data("imgbase");
						}
						if(videoChildrenKey == "video-id"){
							videoChildrenValue = $(item).data("videoid");							
						}						
						
						videoChildrenArray[videoChildrenKey] = videoChildrenValue;
						
						//that.videoChildrenArray = videoChildrenArray;
						
						//将这个数据的值存放在父类数组中		
																		
					})	
					console.log(keyFather);	
					console.log(videoChildrenArray);	
					//console.log(n);
					videoArray[keyFather] = videoChildrenArray;
					//console.log(videoArray);			
					
				})
				
				console.log(videoArray);		
				//将获取组装好的数组存储到全局变量
				that.videoArray = videoArray;
				//console.log(that);
				
				//将获取到的数据提交到后台
				$.ajax({
					url:"../server/ajax/thevideo.php",
					data:{turl:"addVideo",theVideoArray:that.videoArray,theArticleId:articleId,theArticleType:articleType},
					type:"post",
					dataType:"json",
					success:function(data){
						console.log("===========后端返回的video数据===============");
						console.log(data)
					}
					
				})				
				
			}
			
						
			$("#btn-save").on("click",function(){
				saveArticle("draft","add");
			});
			
			$("#article-save").on("click",function(){
				saveArticle("public","add");
				
			});
			
			$("#btn-edit-save").on("click",function(){
				saveArticle("draft","edit");
			});
			
			$("#article-edit-save").on("click",function(){
				saveArticle("public","edit");
				
			});
			
			
			//提交文档的方法，是正式发布还是存草稿
			
			function saveArticle(isPublic,isEdit){
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
					
					//当为article-file图片框时,获取它的value值
					if(theKey == "article-pic"){
						theValue = articlePicName = $("#article-file").attr("value");
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
				//thePostArray['article-pic'] = that.pictureName;
				thePostArray['the-article'] = str;
				thePostArray['article-status'] = isPublic;
				//提交base64链接
				thePostArray['article-img-base'] = that.baseImgurl;
				
				
				//文章类型的提交：是编辑·还是添加
				thePostArray['article-upload-type'] = isEdit;
				
				//如果存在参数为编辑，需要对该文章的id进行发送
				thePostArray['edit-article-id'] = that.editArticleId;
				
				
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
						
						//在文章文章添加成功返回后，获取到对应的文章id，并向该id提交对应的视频
						if(data.status == 200){
							//获取文章的id,并调用video获取提交
							getVideoInfoArray(data.result,isEdit);
							
						}
					}
										
				})
				
			}
		},
		
		articleList:function(){
			//文章查询的方法
			//在页面加载进去时进行查询所有的操作
			
			//获取用户信息
			//var theComm = new util();
			var getInfo = theUtil.commUser.getUserInfo();
			console.log("=========网络查询类获取用户信息（外）============");
			console.log(getInfo);
			
			
			function articleListAjax(articleStatus,theUsername){
				$.ajax({
					url:"../server/ajax/thearticle.php",
					data:{turl:"articleList",status:articleStatus,author:theUsername},
					type:"get",
					dataType:"json",
					success:function(data){
						console.log("===============后端传递的文章列表数据===============");
						console.log(data);
						
						var res = data.result;		
						
						data.result.forEach(function(item){
							
							var articleSh;							
							if(item.article_sh == "0"){
								articleSh = "未审核";
								
							}
							if(item.article_sh == "1"){
								articleSh = "已审核";
								
							}	
							
							var articleStatus;
							if(item.article_status == "draft"){
								articleStatus = "草稿";
								
							}
							if(item.article_status == "public"){
								articleStatus = "公开";								
							}								
							
							var theHtml = '<tr class="text-c"><td><input type="checkbox" value="" name=""></td><td>'+item.aid+'</td><td class="text-l"><u style="cursor:pointer" class="text-primary" onClick="article_edit(\'查看\',\'article-zhang.html?article_id='+item.aid+'\',\'10002\')" title="查看">'+item.title+'</u></td><td>'+item.categoryname+'</td><td>'+item.article_author+'</td><td>'+item.commit_start+'</td><td>21212</td><td class="td-status"><span class="label label-success radius">'+articleStatus+'</span></td><td class="wz-status"><span class="label label-success radius">'+articleSh+'</span></td><td class="f-14 td-manage"><a style="text-decoration:none" onClick="article_shenhe(this,\'10001\')" href="javascript:;" title="审核">审核</a><a style="text-decoration:none" class="ml-5" onClick="article_edit(\'资讯编辑\',\'article-add.html\',\'10001\')" href="javascript:;" title="编辑"><i class="Hui-iconfont">&#xe6df;</i></a><a style="text-decoration:none" class="ml-5" onClick="article_del(this,\'10001\')" href="javascript:;" title="删除"><i class="Hui-iconfont">&#xe6e2;</i></a><a style="text-decoration:none" class="ml-5"  href="../server/ajax/thearticle.php?turl=oBarticle&article_id='+item.aid+'&getOb=ob" title="生成文件"><i class="Hui-iconfont">&#xe645;</i></a></td></tr>';		

							$(theHtml).appendTo(".article-body");						
							
						})
	
					}	

					
				})				
			}
			
			//页面加载时直接调用出所有的数据查询
			articleListAjax('',getInfo.username);
			
			//查询发布文章
			$("#check_article").on("click",function(){
				articleListAjax('public',getInfo.username);
			})
			
			//查询草稿文章
			$("#check_draft").on("click",function(){
				articleListAjax('draft',getInfo.username);
			})				
		},
		//查看文章功能
		checkArticle:function(){
			//检测是否有参数
			var hasUrlParames = window.location.search;
			if(hasUrlParames){
				if(hasUrlParames.indexOf("article_id")>-1){
					//获取当前文章的id
					var useUtil = new util();
					var theId = useUtil.theReg.getUrlParamsReg("article_id")
					alert("当前的文章id为:"+theId);
					$.ajax({
						url:"../server/ajax/thearticle.php",
						data:{turl:"checkArticle",article_Id:theId},
						type:"get",
						dataType:"json",
						success:function(data){
							console.log("==============从后端返回的文章数据=============");
							console.log(data)
							
							var res = data.result;
							//前端dom渲染
							$(".page-title").find("h3").text(res.title);
							$(".page-info-author").text(res.article_author);
							$(".page-info-source").text(res.article_source);
							$(".page-info-data").text(res.commit_start);
							$(".page-short").find("p").text(res.article_short);
							$(".page-contain").html(res.article_container);
						}
					})				
				}
			}									
		},	

		//根据分类页面静态化请求
		articleHtml:function(){
			//向后端请求取得分类列表
			//获取文章的分类
			var data = theUtil.commAricle.getArticleCategoryJson();	
			console.log("===========外部获取的category-json=========");
			console.log(data);
				
			//获取fm的分类
			var fmData = theUtil.commAricle.getArticleCategoryJson('fm');	
			console.log("===========外部获取的fm-category=========");
			console.log(fmData);
			
			//html渲染
			data.forEach(function(item){
				var itemHtml = '<option value='+item.cid+'>'+item.categoryname+'</option>';
				$(itemHtml).appendTo("#ob-select-category-s");
				$(itemHtml).appendTo("#ob-select-category-lb");
			})
			
			//html渲染fm-category
			fmData.forEach(function(item){
				var itemHtml = '<option value='+item.cid+'>'+item.categoryname+'</option>';
				$(itemHtml).appendTo("#ob-select-category-fm");
				$(itemHtml).appendTo("#ob-select-fm-lb");			
			})
					
			//静态化fm页面
			$("#get-ob-fm").click(function(){
				//获取选中的分类
				var theCategory = $("#ob-select-category-fm").val();			
				//向后端发出静态化请求
				$.ajax({
					url:"../server/ajax/thefm.php",
					data:{turl:'getFmArticleOb','fm-article-category':theCategory},
					type:'get',
					dataType:'json',
					success:function(data){
						console.log("===============fm静态化请求返回值=================");
						console.log(data);
					}
				})
			})
			
			//选择选择框，根据选中的值来进行查询
			//获取选中值
			$("#ob-select-category-s").change(function(){
				var theSelect = $("#ob-select-category-s").find("option:selected").val();
				alert("内部值："+theSelect);
				that.theSelectVal = theSelect;
				
			});
			
			//点击提交并进行相关操作
			$("#get-ob").click(function(){
				alert("外部值："+that.theSelectVal);
				//根据选中的分类请求相关的后台查询
				$.ajax({
					url:"../server/ajax/thearticle.php",
					type:"get",
					data:{turl:'theObMoreArticle',getOb:"obMore",categoryNum:that.theSelectVal},
					//dataType:"json",
					success:function(data){
						console.log(data);						
					}
				})
			})
			
			$("#ob-select-category-lb").change(function(){
				var lbSelectVal = $("#ob-select-category-lb").find("option:selected").val();
				//将作用域内部的选中值传递给外部
				that.lbSelectVal = lbSelectVal;		
				alert("选中的分类值"+lbSelectVal);
			})
			
			$("#get-lb-ob").click(function(){
				alert("选中的外部分类值"+that.lbSelectVal);			
				//向后台提交静态化请求
				if(that.lbSelectVal != 0){
					$.ajax({
						url:'../server/ajax/thearticle.php',
						type:'get',
						data:{turl:"frontArticleListOb",getCategoryId:that.lbSelectVal,getLimit:2,getOb:"ob"},
						dataType:'json',
						success:function(data){
							console.log(data);
						}
					})
				}
				//that.lbSelectVal为0时，为全选，因而需要对select进行便利，并对所有的分类值进行请求
				if(that.lbSelectVal == 0){
					$("#ob-select-category-lb").find("option").each(function(){
						var lbAllSelect = $(this).val();
						alert("全选的值为" + lbAllSelect);
						//对所有的分类进行静态化处理
						if(lbAllSelect != 0){
							$.ajax({
								url:'../server/ajax/thearticle.php',
								type:'get',
								data:{turl:"frontArticleListOb",getCategoryId:lbAllSelect,getLimit:2,getOb:"ob"},
								dataType:'json',
								success:function(data){
									console.log(data);								
								}
							})
							
						}
					})					
				}
			})
			
			//fm列表静态化
			$("#get-fm-lb-ob").click(function(){
				//获取列表的选中值
				var getFmCategory = $("#ob-select-fm-lb").val();
				console.log("选中值："+ getFmCategory);
				if(getFmCategory == 0){
					//为0为全选
					$("#ob-select-fm-lb").find("option").each(function(index,item){
						//获取选项对应的value
						console.log(item);
						var fmCategoryValue = $(item).val();
						console.log("选择:"+ fmCategoryValue);
						if(fmCategoryValue !=0){
							//不为0时向后台提交fm分类静态化请求
							$.ajax({
								url:"../server/ajax/thefm.php",
								data:{turl:'fmCategoryList',getOb:'ob','get-category-id':fmCategoryValue},
								type:'get',
								dataType:'json',
								success:function(data){
									console.log("===========后端返回fm列表的静态化结果===========");
									console.log(data);
								}
							})
						}						
					})				
				}
				else{
					$.ajax({
						url:"../server/ajax/thefm.php",
						data:{turl:'fmCategoryList',getOb:'ob','get-category-id':getFmCategory},
						type:'get',
						dataType:'json',
						success:function(data){
							console.log("===========后端返回fm列表的静态化结果===========");
							console.log(data);
						}
					})				
				}
			})
			
			//父类列表静态化请求
			$("#get-father-category-ob").click(function(){
				//向后端发出静态化请求
				$.ajax({
					url:"../server/ajax/thearticle.php",
					data:{turl:"fatherCategoryArrayPageOb",pageNum:5,theOb:'Ob'},
					type:"get",
					//dataType:"json",
					success:function(data){
						console.log(data);
					}			
				})				
			})
			
			//首页静态化
			$('#get-index-ob').click(function(){
				$.ajax({
					url:'../server/ajax/theindex.php',
					data:{turl:'obHtml',getob:'ob'},
					type:'get',
					dataType:'json',
					success:function(data){
						console.log(data);
					}
				})				
			})
			
			//sitemap静态化操作
			$("#get-sitemap-ob").click(function(){
				//向后端提交静态化请求
				$.ajax({
					url:'../server/ajax/theindex.php',
					data:{turl:"obSitemap",getOb:"ob"},
					type:"get",
					dataType:"json",
					success:function(data){
						console.log(data);
					}
				})			
			})	
	
			//URL.txt静态化操作
			$("#get-url-ob").click(function(){
				//向后端提交静态化请求
				$.ajax({
					url:'../server/ajax/theindex.php',
					data:{turl:"obUrlsTxt",getOb:"ob"},
					type:"get",
					dataType:"json",
					success:function(data){
						console.log(data);
					}
				})			
			})
			
			//用户列表静态化
			$("#get-userPage-ob").click(function(){
				//向后端提供用户列表静态化信息
				$.ajax({
					url:"../server/ajax/thecover.php",
					data:{turl:"userPageOb",getOb:"ob"},
					type:"get",
					dataType:"json",
					success:function(data){
						console.log("===========用户列表静态化返回===============");
						console.loh(data);
					}
				})
			})
			
			//封面静态化
			$("#get-coverPage-ob").click(function(){
				//向后端提供用户列表静态化信息
				$.ajax({
					url:"../server/ajax/thecover.php",
					data:{turl:"coverPageOb",coverOb:"ob"},
					type:"get",
					dataType:"json",
					success:function(data){
						console.log("===========用户列表静态化返回===============");
						console.loh(data);
					}
				})
			})
			
			//封面列表静态化
			$("#get-coverList-ob").click(function(){
				//向后端提供用户列表静态化信息
				$.ajax({
					url:"../server/ajax/thecover.php",
					data:{turl:"coverListOb",thePageNum:5,theOb:"ob"},
					type:"get",
					dataType:"json",
					success:function(data){
						console.log("===========封面列表静态化返回===============");
						console.loh(data);
					}
				})
			})
		},
		
		//单篇文章内容采集
		curlArticle:function(){
			//组装HTML：添加分类选择
			var curlCategoryArray = theUtil.commAricle.getArticleCategoryJson();
			
			curlCategoryArray.forEach(function(item){
				var curlHtml = '<option value="'+item['cid']+'">'+item['categoryname']+'</option>';
				$(curlHtml).appendTo("#curl-select");			
			})
			
			//测试获取值
			var theSelectCurl = $("#curl-select").find("option:selected").val();
			console.log("测试获取值"+theSelectCurl);
			
			//当select改变的时候获取值
			$("#curl-select").change(function(){
				var theSelectCurl = $(this).find("option:selected").val();
				console.log("改测试获取值"+theSelectCurl);			
			})
			

			
			//采集文章预览
			$('#get-curl-article').click(function(){
				//向后台提交采集预览请求
				//获取提交的数据
				var theCurlArray = {};
				$(".curl-article-containerk").find(".value-v").each(function(item){
					var theCurlArrayKey = $(this).attr('name');
					var theCurlArrayVal = $(this).val();
					if(theCurlArrayKey == "curl-select"){
						theCurlArrayVal = $(this).find("option:selected").val();
						
					}
					//console.log("key:"+theCurlArrayKey);
					//console.log("val:"+theCurlArrayVal);
					theCurlArray[theCurlArrayKey] = theCurlArrayVal;
				})	
												
				//重组数据
				theCurlArray['turl'] = "getCurlData";			
				console.log(theCurlArray);				
				
				$.ajax({
					url:'../server/ajax/thecurl.php',
					//data:{turl:"getCurlData"},
					data:theCurlArray,
					type:'post',
					dataType:'json',
					//contentType: "application/json; charset=utf-8",
					success:function(data){
						console.log("==============后端返回的数据采集预览===========");
						console.log(data);
						//html组装
						var curlHtmlTitle = data.result.curlTitle;
						var curlHtmlContainer = data.result.curlContainer;
		
						//对curlHtmlTitle进行转义
						var theUtil = new util();						
						var curlHtmlTitleZ = theUtil.commUtil.HTMLDecode(curlHtmlTitle);
						var curlHtmlContainerZ = theUtil.commUtil.HTMLDecode(curlHtmlContainer);
						//alert(curlHtmlTitleZ);
						$(curlHtmlTitleZ).appendTo(".curl-article-yl-title");
						$(curlHtmlContainerZ).appendTo(".curl-article-yl-container");
					}
				})		
				
			})
			
			//采集内容添加
			$('#get-curl-article-add').click(function(){
				//获取提交的数据
				var theCurlArray = {};
				$(".curl-article-containerk").find(".value-v").each(function(item){
					var theCurlArrayKey = $(this).attr('name');
					var theCurlArrayVal = $(this).val();
					if(theCurlArrayKey == "curl-select"){
						theCurlArrayVal = $(this).find("option:selected").val();
						
					}
					//console.log("key:"+theCurlArrayKey);
					//console.log("val:"+theCurlArrayVal);
					theCurlArray[theCurlArrayKey] = theCurlArrayVal;
				})	
				
				//重组数据
				theCurlArray['turl'] = "getCurlData";	
				theCurlArray['getAdd'] = "add";
				
				console.log(theCurlArray);	

				//向后端提交采集存储请求
				$.ajax({
					url:'../server/ajax/thecurl.php',
					data:theCurlArray,
					type:'POST',
					dataType:'json',
					success:function(data){
						console.log("==============返回采集添加数据的返回值============");
						console.log(data)						
					}
				})
				
			})
			
			//批量采集
			$('#get-curl-article-more').on('click',function(){
				var curlMoreShow = moreCurlAjaxPush('show');
				console.log(curlMoreShow);
			})	
			
			//批量采集上传
			$('#get-curl-article-more-add').on('click',function(){
				var curlMoreShow = moreCurlAjaxPush('add');
				console.log(curlMoreShow);			
			})


			//批量采集的公共提交
			function moreCurlAjaxPush(theType){
				var theArticleArray = {};
				//遍历获取值
				$(".curl-article-containerk").find(".value-v").each(function(key,item){
					//key值
					//console.log(key);
					//当前元素值
					//console.log(item);
					var mCurlKey = $(this).attr("name");
					var mCurlValue = $(this).val();
					
					//当key为select选择框时
					if(mCurlKey == "curl-suffix" || mCurlKey == "curl-select"){
						mCurlValue = $(this).find("option:selected").val();						
					}
					theArticleArray[mCurlKey] = mCurlValue;
				})
				console.log(theArticleArray);
				
				
				//组装数据
				
				if(theType == "show"){
					theArticleArray['turl'] = "getCurlDataMore";
					theArticleArray['tTpye'] = "show";
				}
				if(theType == "add"){
					theArticleArray['turl'] = "getCurlDataMore";
					theArticleArray['tTpye'] = "add";										
				}				
				
				//向后端提交对应的数据
				$.ajax({
					url:'../server/ajax/thecurl.php',
					data:theArticleArray,
					type:'post',
					dataType:'json',
					async:false,//设置数据为同步获取对应的data
					success:function(data){
						console.log(data);
						that.data = data;
					}
				})
				return that.data;
			} 
		},
		
		//图片内容采集
		curlPicture:function(){
			//点击获取是多页面还是单页面
			$("input[name='pic-select-type']").click(function(){
				var theCheckTpye = $(this).prop("checked");
				alert(theCheckTpye);
				if(theCheckTpye == true){
					$(".curl-pic-main").css("display","none");
					$(".curl-pic-begin").css("display","none");
					$(".curl-pic-end").css("display","none");
					$(".pic-more-save").css("display","none");
					
					$(".curl-pic-url").css("display","block");
					$(".pic-one-save").css("display","inline-block");
					
				}
				else{
					$(".curl-pic-main").css("display","block");
					$(".curl-pic-begin").css("display","block");
					$(".curl-pic-end").css("display","block");
					$(".pic-more-save").css("display","inline-block");
					
					$(".curl-pic-url").css("display","none");
					$(".pic-one-save").css("display","none");
					
				}
			})
			
			
			//点击选择是单页栏目上传还是为多页栏目上传
			$('#theCheckListSelect').click(function(){
				//获取选择的状态
				var listCheckSelect = $(this).prop("checked");
				console.log(listCheckSelect);
				if(listCheckSelect == true){					
					$(".curl-pic-list-main").css("display","block");
					$(".curl-pic-list-begin").css("display","block");
					$(".curl-pic-list-end").css("display","block");
					$(".pic-list-more-save").css("display","inline-block");
					
					$(".curl-pic-list-url").css("display","none");
					$(".pic-list-save").css("display","none");
				}
				
				if(listCheckSelect == false){									
					$(".curl-pic-list-url").css("display","block");
					$(".pic-list-save").css("display","inline-block");		
					
					$(".curl-pic-list-main").css("display","none");
					$(".curl-pic-list-begin").css("display","none");
					$(".curl-pic-list-end").css("display","none");
					$(".pic-list-more-save").css("display","none");										
				}
				
			})
			
			
			
			//单页面图片采集点击预览
			$('#get-curl-pic').on('click',function(){
				pushCurlData('check');			
			})
			
			//单页面图片采集点击保存
			$('#get-curl-pic-add').on('click',function(){
				pushCurlData('save');			
			})			
			
			//多页面图片采集点击保存
			$('#get-curl-pic-more-add').on('click',function(){
				pushCurlData('moreSave');			
			})
			
			//图片栏目采集预览
			$('#get-curl-pic-list-check').on('click',function(){
				pushCurlData('picListCheck');			
			})			

			//图片栏目上传
			$('#get-curl-pic-list-add').on('click',function(){
				pushCurlData('picListAdd');			
			})		

			//多页栏目上传
			$('#get-curl-pic-list-more-add').on('click',function(){
				pushCurlData('picListMoreAdd');				
			})		
			
			//公共部分
			function pushCurlData(type){
				//获取照片采集图片表格的信息
				var curlPicArr = {};
				$(".row").each(function(item){
					//获取当前是否为显示，如果为显示，就获取其值
					var isBlock = $(this).css("display");
					if(isBlock == 'block'){
						//var isBlock = $(this).attr("style");  能获取到css的对象集
						var curlPicVal = $(this).find(".value-v").val();
						var curlPicKey = $(this).find(".value-v").attr("name");					
						//console.log(curlPicKey+"的当前转态为："+isBlock);
						//console.log(isBlock.display);
						curlPicArr[curlPicKey] = curlPicVal;		
					}
			
				})
				console.log(curlPicArr);
				//组装数组
				if(type == "check"){					
					curlPicArr['theType'] = "check";
					curlPicArr['turl'] = "getCurlOnePic";
				}
				
				if(type == "save") {
					curlPicArr['theType'] = "save";
					curlPicArr['turl'] = "getCurlOnePic";					
				}
				
				if(type == "moreSave"){
					curlPicArr['theType'] = "save";
					curlPicArr['turl'] = "getCurlPic";							
				}
				
				if(type == "picListCheck"){
					curlPicArr['turl'] = "getCurlList";
					curlPicArr['theListType'] = "check";
				}
				
				if(type == "picListAdd"){
					curlPicArr['turl'] = "getCurlList";
					curlPicArr['theListType'] = "save";
					curlPicArr['theType'] = "save";//文章的存储标识
				}

				if(type == "picListMoreAdd"){
					curlPicArr['turl'] = "getCurlListMore";
					curlPicArr['theListType'] = "save";
					curlPicArr['theType'] = "save";//文章的存储标识					
				}
				
				//向后端提交数据
				$.ajax({
					url:"../server/ajax/thecurl.php",
					data:curlPicArr,
					type:"post",
					dataType:'json',
					success:function(data){
						console.log("===============后端传递过来的预览数据========");
						console.log(data);
					}
				})
			}
			
		}								
	}
	
	//添加文章，fm等信息
	if(theAllUtil.theReg.selfControl('pathname','article-add')){
		theArticle.addArticle();
	}
	
	//theArticle.articleList();
	//theArticle.checkArticle();
	
	//静态化处理
	if(theAllUtil.theReg.selfControl('pathname','article-ob')){
		theArticle.articleHtml();
	}
		
	
	//theArticle.curlArticle();
	//theArticle.curlPicture();		
}

//FM文章管理
function fmControl(){	
	var theFM = {
		addFmArticle:function(){
			var ue = UE.getEditor('editor');
			//通过公共模块获取分类列表，并进行html组装
			var getFmCategoryList = theAllUtil.categoryUtil.getListCategory('fm');
			//遍历对象，组装html
			getFmCategoryList.forEach(function(item){
				var FmCategoryHtml = '<option value="'+item.cid+'">'+item.categoryname+'</option>';		
				$(FmCategoryHtml).appendTo("#ob-select-category-fm");
				
			})
			
			//组装作者
			$("#FM-author").val(theAllUserInfo.result.username);
			
			
			//检测是否存在fm的id，如果没有为添加，如果有为编辑
			var theEditFmId = theAllUtil.theReg.getUrlParamsReg('editFmId');
			if(theEditFmId){
				//向后台发出该文章信息的请求
				$.ajax({
					url:"../server/ajax/thefm.php",
					data:{'turl':'getFmArticle','the-fm-id':theEditFmId},
					type:'get',
					dataType:'json',
					success:function(data){
						console.log("==============后端传递过来编辑fm的数据=================");
						console.log(data);
						var fmInfo = data.result;
						//组装html
						$("#FM-title").val(fmInfo.f_title);
						$("#ob-select-category-fm").val(fmInfo.fm_category);
						$("#FM-keyword").val(fmInfo.f_keyword);
						$("#FM-short").val(fmInfo.f_short);
						$("#FM-author").val(fmInfo.f_admin);
						$("#article-file").val(fmInfo.f_img);
						$("#article-file").attr("value",fmInfo.f_img);
						$(".show-picture").attr("src",'../upload/fm/'+fmInfo.f_img+'');
						$("#article-FM").val(fmInfo.f_fm);
						$("#article-FM").attr("value",fmInfo.f_fm);		
						$("#article-FM-play").attr("src",fmInfo.mpurl);
						
						//百度富文本赋值
						ue.addListener("ready", function () {
					　　　　// editor准备好之后才可以使用
					　　　ue.setContent(fmInfo.f_container);				
						});
					}
					
				})
			}			
			
			//获取FmArticle的相关信息
			$("#article-FM-save").click(function(){
				getFmInfo(status = 'public')
			})	
			
			$("#article-FM-draft").click(function(){
				getFmInfo(status = 'draft');
			})	
			
			//公共方法，获取fm上传内容
			//status：为上传的信息是公开还是存储草稿
			function getFmInfo(status = 'public'){
				var fmArticleArray={},fmKey,fmValue;
				$("#form-article-FM-add").find(".row").find(".value-v").each(function(item){
					fmKey = $(this).attr("name");
					fmValue = $(this).val();
					if(fmKey =="article-FM" || fmKey =="article-FM-file" || fmKey =="article-pic-file" || fmKey =="article-pic"){
						fmValue = $(this).attr("value");
					}				
					fmArticleArray[fmKey] = fmValue;			
				})
				
				console.log("=============获取文章内容==============");				
				var str = ue.getContent();
				
				//组装提交数据				
				fmArticleArray["fm-article"] = str;
				fmArticleArray["turl"] = "addFmArticle";
				console.log(fmArticleArray);
				
				if(theEditFmId){
					fmArticleArray["turl"] = "editFmArticle";
					fmArticleArray["fm-editid"] = theEditFmId;
				}
				
				//组装是fm的存储状态：存草稿还是公开
				//if(status == 'public'){
				fmArticleArray["fm-status"] = status;
				//}
								
				//向后台提交信息
				$.ajax({
					url:"../server/ajax/thefm.php",
					data:fmArticleArray,
					type:"POST",
					dataType:"json",
					success:function(data){
						console.log("================fm提交返回数据====================");
						console.log(data);
						//当返回值为插入成功时，添加ajax的文件上传
						if(data.status == 200){
							console.log("=======触发文件提交============");
							var formData = new FormData();
							formData.append('file', $('#article-FM-fileList')[0].files[0]);
							$.ajax({
								url: '../server/ajax/thefm.php?turl=getFmFile',
								type: 'POST',
								dataType:'json',
								cache: false,
								data: formData,
								processData: false,
								contentType: false,
								success:function(data){
									console.log("===========文件上传返回=============");
									console.log(data);
								}
							})

						}
					}
				})			
			}

			//上传FM的操作
			$("#article-FM-fileList").change(function(){
				var theFile = this.files[0];
				var reader = new FileReader();
				console.log(theFile);
				var theFileName = theFile['name'];
				reader.readAsDataURL(theFile);
				reader.onload = function(e){
					//获取fm的base64
					$fmBaseUrl = this.result;
					//console.log($fmBaseUrl);
					$("#article-FM-play").attr("src",$fmBaseUrl);
					$("#article-FM-fileList").attr("value",$fmBaseUrl);
					$("#article-FM").attr("value",theFileName);
				}
			})
		}
	}
	
	//当链接存在article-add时调用
	if(theAllUtil.theReg.selfControl('pathname','article-add')){
		theFM.addFmArticle();	
	}
		
}


//封面管理
function coverControl(){
	that = this;
	that.pictureInfo;//设置全局变量图片信息
	console.log("=============全局用户信息===============");
	console.log(theAllUserInfo);
	var theCover = {
		coverAdd:function(){
			//组装html
			$("input[name='cover-author']").val(theAllUserInfo.result.username);
			
			//通过公共模块获取分类列表，并进行html组装
			var getCategoryListCover = theAllUtil.categoryUtil.getListCategory();
			console.log("==================封面外部获取分类=================");
			console.log(getCategoryListCover);
			//遍历数组，将分类添加到封面的html中
			getCategoryListCover.forEach(function(item,key){
				var categoryHtmlCover = '<option value="'+item.cid+'">'+item.categoryname+'</option>';
				$(categoryHtmlCover).appendTo("#cover-category");
			})
			
			//获取是否有参数，如果有为编辑封面，没有为增加封面
			var theCoverId = theAllUtil.theReg.getUrlParamsReg("coverId");
			console.log("封面参数为："+ theCoverId)
			
			if(theCoverId){
				//向后端发出请求获取封面详情
				$.ajax({
					url:"../server/ajax/thecover.php",
					data:{turl:"getTheCoverInfo",CoverIdNum:theCoverId},
					type:"get",
					dataType:"json",
					async:false,
					success:function(data){
						console.log("==============后端返回的封面详情==============");
						console.log(data);
						var coverInfo = data.result;
						//根据返回的数据组建HTML
						$("input[name='cover-title']").val(coverInfo.ptitle);
						$("input[name='cover-yw']").val(coverInfo.title_yw);
						$(".cover-short").val(coverInfo.cover_introduction);
						that.pictureInfoname = coverInfo.cover_img;
						$("#cover-pic").attr("data-img",coverInfo.cover_img);
						that.coverIdNum = coverInfo.pid;
						//设置选中的分类
						$("#cover-category").find("option[value='"+coverInfo.cover_category+"']").attr("selected",true);
						
						$(".show-picture").attr("src","../upload/user_cover/"+coverInfo.cover_img+"");
					}
					
				})
				
				//点击上传相关信息
				$("#cover-save").on("click",function(){
					pushCoverInfo("edit");
				});
			}
			else{
				//点击上传相关信息
				$("#cover-save").on("click",function(){
					pushCoverInfo("add");
				});				
				
			}
			//获取上传图片的相关信息
			$("#cover-pic").change(function(){
				var theFile = this.files[0];
				console.log(theFile);
				var reader = new FileReader();
				that.pictureInfo = theFile;	//将图片信息存到全局
				reader.readAsDataURL(theFile);
				reader.onload = function(e){
					$(".show-picture").attr("src",this.result);
					that.picBase = this.result //将base存到全局
					//将base64的值传给show-picture
					$(".show-picture").attr("data-base",that.picBase);
					//将图片的name传给cover-pic
					$("#cover-pic").attr("data-img",theFile);
				}
			})
			
			
			//公共类：向后端提交信息
			function pushCoverInfo(type){
				var coverInfoArray = {};
				
				$(".row").each(function(key,item){
					var cover_name = $(this).find(".value-v").attr("name");
					var cover_value = $(this).find(".value-v").val();					
					if(cover_name == "cover-pic"){
						if(!that.pictureInfo){
							cover_value = that.pictureInfoname;
						}
						else{
							cover_value = that.pictureInfo.name;	
						}
						//cover_value = $(this).data('img');
					}
					
					coverInfoArray[cover_name] = cover_value;
				})		
							
				console.log("===============获取的封面信息=============");
				console.log(coverInfoArray);
				

				//组装数组
				coverInfoArray['baseImg'] = that.picBase;
				console.log()
				coverInfoArray['turl'] = "addCover";
				if(type == "add"){					
					coverInfoArray['set-type'] = "add";
				}
				if(type == "edit"){
					coverInfoArray['set-type'] = "edit";	
					coverInfoArray['editId'] = that.coverIdNum
				}
											
				//coverInfoArray['editId'] = that.
				
				//向后端提交相关数据
				$.ajax({
					url:"../server/ajax/thecover.php",
					data:coverInfoArray,
					type:"post",
					dataType:"json",
					success:function(data){
						console.log("============提交封面信息的返回值==========");
						console.log(data);
					}
				})
				
			}
		},
		getCoverList:function(){
			//通过公共模块获取封面列表信息
			var theCoverListInfo = theAllUtil.memberUtil.getCoverInfo();
			console.log("=================外部封面返回值==================");
			console.log(theCoverListInfo);
			var theCoverListInfoArray = theCoverListInfo.result;
			
			//循环组装html加入到封面列表中
			theCoverListInfo.result.forEach(function(item){
				var coverLiHtml = '<li class="col-md-2"><div><div class="cover-header"><h4>'+item['title']+'</h4></div><div class="cover-body"><div class="cover-body-img"><img width ="100%" class="img-responsive" src="../upload/user_cover/'+item['cover_img']+'"></div><div class="cover-body-text">'+item['cover_introduction']+'</div><div class="cover-body-edit"><a title="编辑" href="javascript:;" onclick="member_add(\'编辑封面\',\'cover-index.html?coverId='+item['pid']+'\',\'\',\'510\')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a> <a title="删除" href="javascript:;" onclick="member_del(this,\''+item['pid']+'\')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a></div></div></li>';
				$(coverLiHtml).appendTo(".cover-ul");
			})
			
		}
		
	}
	theCover.coverAdd();
	theCover.getCoverList();
}


//用户系统管理
function systemControl(){
	var theSystem = {
		setIndex:function(){
			var getInfoArr = {};
			//点击获取网站的信息
			$("#system-index-save").click(function(){
				$(".theSystemInfo").each(function(){
					var theValue = $(this).find(".value-v").val();
					//alert(theValue);
					//获取name的值
					var theName = $(this).find(".value-v").attr('name');
					//alert(theName);
					getInfoArr[theName] = theValue;
				})
				getInfoArr['turl'] = "setIndexInfo";
				console.log(getInfoArr);
				//ajax向后端提交信息
				$.ajax({
					url:'../server/ajax/theindex.php',
					data:getInfoArr,
					type:'post',
					//dataType:'json',
					success:function(data){
						console.log("===============提交网站信息返回的数据===========");
						console.log(data);
					}
				})
			})

			
		},	
		setUserInfo:function(){
			//获取全局的user信息
			theAllUserInfo = theAllUserInfo.result
			console.log("==============全局的用户信息===============");
			console.log(theAllUserInfo);
			//组装html
			$(".user-info-header").find("img").attr("src",'../upload/head/'+theAllUserInfo.user_head);
			$("#user-name").val(theAllUserInfo.the_name);
			$("input:radio[value='"+theAllUserInfo.sex+"']").attr('checked','true');
			$("#user-email").val(theAllUserInfo.email);		
			$("#user-tel").val(theAllUserInfo.tel);	
			$(".user-short").val(theAllUserInfo.user_introduction);
			$("#user-header-file").val(theAllUserInfo.user_head);
			$("#user-header-file").attr("value",theAllUserInfo.user_head);
			
			//判断如果为游客时，显示邮箱验证按钮
			if(theAllUserInfo.role == 'visitor'){
				$(".system-email-button").css({
					"display":"block",			
				})
				
				//点击打开新窗口验证
				$('#system-email-check').click(function(){
					window.open("./member-email.html");
					
				})
			}			
			//如果图片上传框发生变化时，进行对应的图片赋值
			$("#user-header-file-base").change(function(){
				var theFile = this.files[0];
				var reader = new FileReader();
				reader.readAsDataURL(theFile);
				reader.onload = function(e){
					console.log("========获取图片信息==============");
					console.log(theFile);
					$("#user-header-file").val(theFile['name']);
					$("#user-header-file").attr("value",theFile['name']);
					//如果上传文件的名字跟之前不想等时，重新上传base
					if(theAllUserInfo.user_head != theFile['name']){
						$(".user-short").attr("value",this.result);
						$(".user-info-header").find("img").attr("src",this.result);
						$("#user-header-file-base").attr("value",this.result);
					}
				}
			})
			
			
			//获取对于的值
			$("#system-user-save").click(function(){
				var userInfArray = {};
				//获取用户列表上的信息
				$("#form-userInfo-add").find(".value-v").each(function(item){
					var userKey = $(this).attr('name');
					var userVal = $(this).val();
					if(userKey == 'user-header-file' || userKey == 'user-header-file-base'){
						userVal = $(this).attr("value");
					}
					userInfArray[userKey] = userVal;
				})
				//获取性别
				var userSexInfo = $("input[name='sex']:checked").val();
				userInfArray['user-sex'] = userSexInfo;
				
				//获取提交地址
				userInfArray['turl'] = "updateUserInfo";
				
				//获取提交用户
				userInfArray['user-username'] = theAllUserInfo.username;
				
				console.log("=============获取用户提交信息=================");
				console.log(userInfArray);		
				
				//向后台提交信息
				$.ajax({
					url:'../server/ajax/themember.php',
					data:userInfArray,
					type:'post',
					dataType:'json',
					success:function(data){
						console.log("===============更新用户信息返回数据==============");
						console.log(data);
					}
				})
			})
			
		}
	}
	
	//按需加载
	if(theAllUtil.theReg.selfControl('pathname','system-index')){
		theSystem.setIndex();
	}
	if(theAllUtil.theReg.selfControl('pathname','system-user')){
		theSystem.setUserInfo();		
	}
		
}

//会员管理系统
function memberControl(){
	that = this;
	that.theFile;
	var theUtil = new util();
	var theMember = {
		//获取用户列表
		getMemberList:function(){
			$.ajax({
				url:"../server/ajax/themember.php",
				data:{turl:'memberList'},
				type:'get',
				async:false,//需要同步数据处理将得到的值传递给全局
				dataType:'json',
				success:function(data){
					console.log("===========后端返回的用户列表数据=============");
					console.log(data);
					//that.memberListVal = data;
					
					//循环列表
					//组装html：返回用户列表				
					
					data.result.forEach(function(item){					
						var memberListHtml = '<tr class="text-c"><td><input type="checkbox" value="1" name=""></td><td>1</td><td><u style="cursor:pointer" class="text-primary" onclick="member_show(\'张三\',\'member-add.html?memberName='+item['username']+'\',\'10001\',\'360\',\'400\')">'+item['username']+'</u></td><td>'+item['sex']+'</td><td>'+item['tel']+'</td><td>'+item['email']+'</td><td class="text-l">'+item['city']+'</td><td>'+item['join_time']+'</td><td class="td-status"><span class="label label-success radius">'+item['rolename']+'</span></td><td class="td-manage"><a style="text-decoration:none" onClick="member_stop(this,\'10001\')" href="javascript:;" title="停用"><i class="Hui-iconfont">&#xe631;</i></a> <a title="编辑" href="javascript:;" onclick="member_edit(\'编辑\',\'member-add.html?memberName='+item['username']+'\',\'4\',\'\',\'510\')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a> <a style="text-decoration:none" class="ml-5"onClick="change_password(\'修改密码\',\'change-password.html?username='+item['username']+'\',\'10001\',\'600\',\'270\')" href="javascript:;" title="修改密码"><i class="Hui-iconfont">&#xe63f;</i></a> <a title="删除" href="javascript:;" onclick="member_del(this,\''+item['iid']+'\')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a></td></tr>';
					
						$(memberListHtml).appendTo(".member-list");
						
					})
									
				}
			})	
			//return that.memberListVal;
		},
		memberAdd:function(){
			//获取链接传递过来的参数
			var theRegUrl = theUtil.theReg.getUrlParamsReg('memberName');
			alert("链接参数为:"+theRegUrl);
			
			//获取用户的权限列表
			var theRoleList = theUtil.memberUtil.getMemberRole();
			
			//console.log("=========外部权限列表==============");
			//console.log(theRoleList);
			
			//遍历数组，组装选择的选择
			//由于theRoleList为json，不能进行遍历，需要通过JSON.parse()转换为javascript的对象
			JSON.parse(theRoleList).forEach(function(item,index){
				//console.log(item);
				var roleListHtml = '<option value="'+item['roleyw']+'">'+item['rolename']+'</option>';
				$(roleListHtml).appendTo(".role-select");
			})
				
			//如果参数为不存在的时候，为增加用户,如果存在时为编辑用户			
			if(!theRegUrl){
				//
				$(".user-put").on("click",function(){
					theUserInfoTableValue('add');
				})
			}
			else{
				//当参数存在时，根据参数获取用户的相关信息，并组装对应的html
				//根据公用模块获取用户数据
				var theMemberInfo = theUtil.memberUtil.getMemberInfo(theRegUrl);				
								
				
				//将图片名称存为全局变量
				that.header = theMemberInfo.result.user_head;
				console.log("内头像："+that.header);
				console.log("=============根据编辑用户名后端返回的用户信息===============");
				console.log(theMemberInfo);	
				var theMemberInfoVal = theMemberInfo.result;
				//组装对应的html
				$("input[name='username']").val(theMemberInfoVal.username);
				$("input[name='password']").val(theMemberInfoVal.password);
				$("input[name='theName']").val(theMemberInfoVal.the_name);
				$("input:radio[value='"+theMemberInfoVal.sex+"']").attr("checked","true");
				$("input[name='tel']").val(theMemberInfoVal.tel);
				$("input[name='email']").val(theMemberInfoVal.email);
				//$(".select").find("option[value="++"]").attr("selected",true);
				$(".introduction").val(theMemberInfoVal.user_introduction);
				$(".city-select").find("option[value='"+theMemberInfoVal.city+"']").attr("selected","selected");
				$(".role-select").find("option[value='"+theMemberInfoVal.role+"']").attr("selected","selected");
				
				$(".user-put").on("click",function(){
					theUserInfoTableValue('edit');
				})
			}
			

			//公共类，获取头像上传信息
			$(".user-head").change(function(){
				var theFile = this.files[0];
				console.log(theFile);
				//将theFile和生成的base64路径传给全局变量
				that.theFile = theFile;
				var reader = new FileReader();
				reader.readAsDataURL(theFile);
				reader.onload = function(e){
					var imgSrc = this.result;
					console.log(imgSrc);
					that.baseImg = imgSrc;
				}
			})
			
			//公共类，获取表格中的信息
			//select 为选择新增或者编辑的方式
			function theUserInfoTableValue(select){
				console.log("外头像："+that.header);
				var userArray = {};
				$(".user-value").each(function(key,item){
					var theKey = $(this).find(".value-v").attr("name");
					var theValue = $(this).find(".value-v").val();					
					if(theKey =="sex"){
						theValue = $("input[name='sex']:checked").val();					
					}
					if(theKey =="head"){
						if(!that.theFile){
							theValue = that.header;
						}
						else{
							theValue = that.theFile.name;
						}
						
					}
					userArray[theKey] = theValue;
													
				})
				
				//组装数组
				userArray['baseImg'] = that.baseImg;
				userArray['turl'] = "registerAdd";
				
				if(select =="add"){
					userArray['select'] = "add";
				}
				
				if(select =="edit"){
					userArray['select'] = "edit";
					userArray['oldUserName'] = theRegUrl;
				}				

				console.log(userArray)	

				//向后端发送相关数据
				$.ajax({
					url:'../server/ajax/themember.php',
					data:userArray,
					type:'post',
					dataType:'json',
					success:function(data){
						console.log("============后端提交会员注册返回==========");
						console.log(data);
						
					}
				})
			}
		}	
		
	}
	if(theAllUtil.theReg.selfControl('pathname','member-list')){
		theMember.getMemberList();	
	}
	if(theAllUtil.theReg.selfControl('pathname','member-add')){	
		theMember.memberAdd()
	}
}

//友情链接管理控制
function friendLinkControl(){
	that = this;
	var friendLinkManage = {
		friendAdd:function(){
			//获取链接是否带参数
			var theFriendLinkId = theAllUtil.theReg.getUrlParamsReg("friendLinkId");
			alert("友情链接参数为："+theFriendLinkId);
			//当存在参数时，说明为编辑页面
			if(theFriendLinkId){
				//向后端发出获取该友情链接的请求
				$.ajax({
					url:"../server/ajax/thefriendlink.php",
					data:{turl:"getFriendLinkInfo",friendId:theFriendLinkId},
					type:'get',
					dataType:'json',
					success:function(data){
						console.log("==========后端传递过来的友情链接详情===========");
						console.log(data);
						
						//对前端进行渲染
						$("#friend-title").val(data.result.ftitle);
						$("#friend-link").val(data.result.flink);
						$("#friend-introduction").val(data.result.fintroduction);
						//当选择的为图片的时候
						if(data.result.ftype == 1){
							$('#friend-link-checkbox').prop('checked',true);
							$(".theFriendInfoInput").css("display","block");
							that.friendImgName = data.result.fimage;
							$("#friendlink-file").val(that.friendImgName);
						} 
					}
				})
			}			
			
			//设置是否需要添加图片
			$("#friend-link-checkbox").click(function(){
				var theType = $(this).prop('checked');
				alert(theType);		
				//将是否添加图片的类型也添加给全局
				that.theType = theType;
				if(theType == true){
					//为选择时，添加图片框为显示
					$(".theFriendInfoInput").css("display","block");
				}
				else{
					$(".theFriendInfoInput").css("display","none");
				}
			})	

			//获取图片base64
			$(".friend-link-input").change(function(){
				var theFile = this.files[0];
				console.log(theFile);
				//将获取到名字赋予全局，用于数据的提交
				that.friendImgName = theFile.name;
				var reader = new FileReader();
				reader.readAsDataURL(theFile);//进行转换
				reader.onload = function(e){
					var friendImgBase = this.result;
					console.log(friendImgBase);
					//将获取到的base64数据传递给全局
					that.friendImgBase = friendImgBase;
				}			
			})
			
			//获取相关数据，并进行提交
			$('#friend-link-save').click(function(){
				alert(that.theType);
				//传建空对象
				var friendArray = {};
				$(".theFriendInfo").each(function(){	
					//获取表单是否显示
					var friendTyle = $(this).css("display");				
					var friendLinkKey = $(this).find(".value-v").attr("name");
					var friendLinkValue = $(this).find(".value-v").val();
					console.log(friendLinkKey + "的状态为："+ friendTyle);
					if(friendTyle =="block"){
						friendArray[friendLinkKey] = friendLinkValue;
					}
					console.log(friendArray);
				})
				
				
				//组建数组
				friendArray['turl'] = "addFriendLink";
				if(!that.theType || that.theType == false){
					friendArray['friend-type'] = 0;	
				}
				else{
					friendArray['friend-type'] = 1;
					friendArray['friend-img'] = that.friendImgName;
					friendArray['friend-base'] = that.friendImgBase;
				}
				
				alert(theFriendLinkId);
				
				//当存在参数时，类型为编辑，不存在时为添加
				if(theFriendLinkId){
					friendArray['friend-select'] = 'edit'
					friendArray['friend-id'] = theFriendLinkId;
					
				}
				else{
					friendArray['friend-select'] = 'add'					
				}
				
				//向后端发出请求
				$.ajax({
					url:'../server/ajax/thefriendlink.php',
					data:friendArray,
					type:'post',
					dataType:'json',
					success:function(data){
						console.log("==========后台传递的添加友情链接的数据==========");
						console.log(data);
						
						
					}
				})		
			
			})
						
		},
		
		//友情链接列表
		friendList:function(){
			//向后端提交友情链接列表请求
			$.ajax({
				url:'../server/ajax/thefriendlink.php',
				data:{turl:"friendLinkList",},
				type:"get",
				dataType:"json",
				success:function(data){
					console.log("============后端传递过来的友情链接的数据=============");
					console.log(data);
					//前端html渲染，friendlink-list.html
					data.result.forEach(function(item){
						//组装图片类型
						if(item['ftype'] == 0){
							item['ftype'] = '链接';						
						}
						else if(item['ftype'] == 1){
							item['ftype'] = '图片';							
						}
						
						var friendHtml = '<tr class="text-c"><td><input type="checkbox" value="1" name=""></td><td>'+item['fid']+'</td><td><u style="cursor:pointer" class="text-primary" onclick="member_show(\'张三\',\'friendlink-index.html?friendLinkId='+item['fid']+'\',\'10001\',\'360\',\'400\')">'+item['ftitle']+'</u></td><td>'+item['flink']+'</td><td>'+item['fintroduction']+'</td><td>'+item['fdate']+'</td><td>'+item['ftype']+'</td><td>'+item['fimage']+'</td><td class="td-manage"><a style="text-decoration:none" onClick="member_stop(this,\'10001\')" href="javascript:;" title="停用"><i class="Hui-iconfont">&#xe631;</i></a> <a title="编辑" href="javascript:;" onclick="member_edit(\'编辑\',\'member-add.html\',\'4\',\'\',\'510\')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a><a title="删除" href="javascript:;" onclick="member_del(this,'+item['fid']+')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a></td></tr>';
						
						$(friendHtml).appendTo('.friendlink-list');
					})					
									
				}
			})
		}
		
	}
	friendLinkManage.friendAdd();	
	friendLinkManage.friendList();
}

//邮箱控制管理
function emailControl(){
	var theEmail = {
		emailAdd:function(){
			//获取用户信息
			console.log("============邮箱中的用户信息============");
			console.log(theAllUserInfo);
			//组装邮箱
			$("#the-email").val(theAllUserInfo.result.email);
			
			$('.email-put').click(function(){
				//获取email的相关信息
				var emailArray = {};
				$('.email-value').each(function(key,item){
					var isBlock = $(this).css("display");
					console.log(isBlock)
					if(isBlock == "block"){
						var theKey = $(this).find(".value-v").attr("name");
						var theValue = $(this).find(".value-v").val();
						emailArray[theKey] = theValue;
					}
				})				
				
				//组装数据
				emailArray['turl'] = "addSendMail";
				emailArray['the-user'] = theAllUserInfo.result.username;
				
				console.log(emailArray);
				//向后端发出邮箱验证提交
				$.ajax({
					url:'../server/ajax/theemail.php',
					data:emailArray,
					type:"post",
					dataType:'json',
					success:function(data){
						console.log("============邮箱发送返回的数据============");
						console.log(data);
					}
					
				})
			})
		}	
	}
	theEmail.emailAdd();
}

//用户主页模块
function userIndexPage(){
	//that = this;
	var theUserInfo;
	this.userDate = {
		getUserDate:function(){
			//后端请求获取用户数据
			$.ajax({
				url:"../server/ajax/thelogin.php",
				data:{"turl":"indexUserInfo"},
				type:"get",
				dataType:"json",
				async:false,
				success:function(data){
					console.log("============后端返回的用户信息==============");
					console.log(data);	
					theUserInfo = data;
					//console.log("==========内========");
					//console.log(that.theUserInfo);
										
					//判断用户信息是否为游客，如果为游客，需要到邮箱进行验证					
					if(theUserInfo.result.userInfo.role == 'visitor'){
						layer.confirm('你还没进行用户验证，需要进行验证吗？',
						{
							btn: ['进行','不进行'] //按钮
						},
						 function(index){
							window.open("./member-email.html");			 
						 }),
						 function(){
							 layer.msg('下次再验证');
						 }
					}
				}
			})
			//return theUserInfo;
		},
		creatInfo:function(){
			userDate.getUserDate();
			console.log("================主页用户信息传递================");
			console.log(theUserInfo);
			//显示用户信息
			$(".loginUser").text(theUserInfo.result.userInfo.username);
			$(".loginIp").text(theUserInfo.result.userInfo.login_ip);
			$(".loginTime").text(theUserInfo.result.userInfo.login_time);
			//显示表格
			//console.log("=======表格============");
			//console.log(theUserInfo.result.userNum.total);
			$.each(theUserInfo.result.userNum.total,function(index,item){
				//组装成html
				var tdHtml = '<td class="'+index+'">'+item+'</td>';
				//console.log("组成:"+tdHtml);
				$(tdHtml).appendTo(".user-total");				
			})
			if(theUserInfo.result.userInfo.role == 'admin'){
				$.each(theUserInfo.result.userNum.today,function(index,item){
					var tdHtml = '<td class="'+index+'">'+item+'</td>';
					//console.log("组成:"+tdHtml);
					$(tdHtml).appendTo(".user-today");								
				})
			}

			
			//组装文章列表
			if(theUserInfo.result.articleArray.length == 0){
				var liHtml = "<div>暂无新文章</div>";
				$(liHtml).appendTo(".new-article-list-ul");
			}
			else{
				//$.each(theUserInfo.result.articleArray,function(index,item){				
				//	var liHtml = '<li><div class="article-img col-md-2"><img class="img-responsive" src="../upload/cover/'+item.article_img+'"></div><div class="article-info col-md-8"><h4>'+item.title+'</h4><div class="article-info-detail">'+item.article_short+'</div></div><div class="article-control col-md-2"></div></li>';	
				//	$(liHtml).appendTo(".new-article-list-ul");
				//})		
				
				//使用Datatables
				var tableHtml = '<table id="table_article" class="display"><thead><tr><th>文章id</th><th>文章标题</th><th>文章描述</th><th>日期</th><th>浏览量</th><th>操作</th></tr></thead></table>';
				$(tableHtml).appendTo(".new-article-list-ul");
				
				 $('#table_article').DataTable({
					  data: theUserInfo.result.articleArray,
						columns: [
							{ data: 'aid' },
							{ data: 'title' },
							{ data: 'article_short' },
							{ data: 'article_time' },
							{ data: 'article_view' },
							{ data : null},
						],
						 columnDefs: [{
							//   指定第最后一列

							targets: -1,
							render: function(data, type, row, meta) {
								if(theUserInfo.result.userInfo.role == 'admin'){
									return '<input type="button" data-artileid="'+row.aid+'" value="删除" class="articleDel"><input type="button" data-artileid="'+row.aid+'" class="articleEdit" value="编辑"><input type="button" data-artileid="'+row.aid+'" value="静态化" class="articleOb">';
								}
								else{
									return '<input type="button" data-artileid="'+row.aid+'" value="删除" class="articleDel"><input type="button" data-artileid="'+row.aid+'" class="articleEdit" value="编辑">';
								}
							}
						}]					
				 });
				 /*删除操作*/
				 $(".articleDel").click(function(){
					 var abs = this;
					 var theArticleId = $(this).data('artileid');
					 layer.confirm('确认要删除吗？',function(index){
						//此处请求后台程序，下方是成功后的前台处理……
						//console.log(abs);
						//$(item).parent().parents("tr").remove();
						//向后台提出删除请求
						$.ajax({
							url:'../server/ajax/thearticle.php',
							data:{'turl':'delArticle','article-id':theArticleId},
							type:'get',
							async:false,
							dataType:'json',
							success:function(data){
								if(data.status == 200){
									$(abs).parents("tr").remove();
									layer.msg('已删除!',{icon:1,time:1000});
								}
								else{
									layer.msg('删除失败!',{icon:1,time:1000});								
								}
							}
						})
					});
					 
				 })
				 
				 //编辑按钮
				 $(".articleEdit").click(function(){
					var theArticleId = $(this).data('artileid')
					window.open('article-add.html?editArticleId='+theArticleId);
					 
				 })
				 
				 //静态化按钮
				 $(".articleOb").click(function(){
					var theArticleId = $(this).data('artileid');
					//向后端发出静态化请求
					$.ajax({
						url:"../server/ajax/thearticle.php",
						data:{'turl':'oBarticle','article_id':theArticleId,'getOb':'ob'},
						type:'get',
						dataType:'json',
						success:function(data){
							console.log("==============单篇静态化后端请求返回数据==============");
							console.log(data);
							if(data.status == 200){
								alert("页面静态化成功");
							}
							else{
								alert("页面静态化失败");							
							}
						}					
					})
				 })
			}
			
			//组装FM列表
			if(theUserInfo.result.fmArray.length == 0){
				var liHtml = "<div>暂无新文章</div>";
				$(liHtml).appendTo(".new-fm-list-ul");				
			}
			else{
				//使用Datatables
				var tableHtml = '<table id="table_fm" class="display"><thead><tr><th>fmid</th><th>fm标题</th><th>fm描述</th><th>日期</th><th>操作</th></tr></thead></table>';
				$(tableHtml).appendTo(".new-fm-list-ul");
				
				//设置datatable
				$('#table_fm').DataTable({
					data: theUserInfo.result.fmArray,
					columns: [
						{ data: 'fid' },
						{ data: 'f_title' },
						{ data: 'f_short' },
						{ data: 'f_time' },
						{ data : null},
					],
					columnDefs: [{
						//   指定第最后一列
						targets: -1,
						render: function(data, type, row, meta) {
							return '<input type="button" data-fmid="'+row.fid+'" value="删除" class="fmDel"><input type="button" data-fmid="'+row.fid+'" class="fmEdit" value="编辑">';
						}
					}]					
				});				
				
				//点击删除
				$(".fmDel").click(function(){
					//获取参数
					var fmId = $(this).data('fmid');
					var abs = this;
					//var theArticleId = $(this).data('artileid');
					layer.confirm('确认要删除吗？',function(index){
					//向后端发出删除请求
						$.ajax({
							url:'../server/ajax/thefm.php',
							data:{'turl':'delFm','the-fm-id':fmId},
							type:"get",
							dataType:"json",
							success:function(data){
								console.log("==========删除fm返回后端数据============");
								console.log(data);
								if(data.status == 200){
									$(abs).parents("tr").remove();
									layer.msg('已删除!',{icon:1,time:1000});
								}
								else{
									layer.msg('删除失败!',{icon:1,time:1000});
								}
							}
						})
					})
				})
				
				//点击编辑
				$(".fmEdit").click(function(){
					//获取该fm的id
					var fmId = $(this).data('fmid');
					//打开编辑窗口
					window.open('article-add-FM.html?editFmId='+theArticleId);				
				})
			}
			
			//组装评论列表
			if(theUserInfo.result.commentArray.length == 0){
				var liHtml = "<div>暂无新评论</div>";
				$(liHtml).appendTo(".new-comment-list-ul");				
			}
			else{
				//使用Datatables
				var tableHtml = '<table id="table_comment" class="display"><thead><tr><th>评论id</th><th>评论文章</th><th>评论内容</th><th>评论用户</th><th>日期</th><th>操作</th></tr></thead></table>';
				$(tableHtml).appendTo(".new-commit-list-ul");
				
				//组装Datatables
				//设置datatable
				$('#table_comment').DataTable({
					data: theUserInfo.result.commentArray,
					columns: [
						{ data: 'cmid' },
						{ data: 'title' },
						{ data: 'cm_comment' },
						{ data: 'cm_name' },
						{ data: 'cm_time' },
						{ data : null},
					],
					columnDefs: [{
						//   指定第最后一列
						targets: -1,
						render: function(data, type, row, meta) {
							return '<input type="button" data-commentid="'+row.cmid+'" value="删除" class="commnetDel">';
						}
					}]					
				});					
				
				//删除评论
				$(".commnetDel").click(function(){
					//获取需删除的评论id
					var commentid = $(this).data('commentid');
					//获取参数
					//var fmId = $(this).data('fmid');
					var abs = this;
					//var theArticleId = $(this).data('artileid');
					layer.confirm('确认要删除吗？',function(index){
					//向后端发出删除请求
						$.ajax({
							url:'../server/ajax/thecomment.php',
							data:{'turl':'delComment','comment-id':commentid},
							type:"get",
							dataType:"json",
							success:function(data){
								console.log("==========删除fm返回后端数据============");
								console.log(data);
								if(data.status == 200){
									$(abs).parents("tr").remove();
									layer.msg('已删除!',{icon:1,time:1000});
								}
								else{
									layer.msg('删除失败!',{icon:1,time:1000});
								}
							}
						})
					})
				})
			}
			
			if(theUserInfo.result.userInfo.role == 'admin'){
				//组装用户列表
				if(theUserInfo.result.memberArray.length == 0){
					var liHtml = "<div>暂无新用户</div>";
					$(liHtml).appendTo(".new-member-list-ul");				
				}
				else{
					//使用Datatables
					var tableHtml = '<table id="table_member" class="display"><thead><tr><th>用户id</th><th>用户名</th><th>中文名</th><th>性别</th><th>email</th><th>电话</th><th>城市</th><th>操作</th></tr></thead></table>';
					$(tableHtml).appendTo(".new-member-list-ul");
					//组装Datatables
					//设置datatable
					$('#table_member').DataTable({
						data: theUserInfo.result.memberArray,
						columns: [
							{ data: 'iid' },
							{ data: 'username' },
							{ data: 'the_name' },
							{ data: 'sex' },
							{ data: 'email' },
							{ data: 'tel' },
							{ data: 'city' },
							{ data : null},
						],
						columnDefs: [{
							//   指定第最后一列
							targets: -1,
							render: function(data, type, row, meta) {
								return '<input type="button" data-memberid="'+row.iid+'" value="删除" class="memberDel">';
							}
						}]					
					});	

					//删除用户操作
					$(".memberDel").click(function(){
						//获取需删除的评论id
						var memberid = $(this).data('memberid');
						//获取参数
						//var fmId = $(this).data('fmid');
						var abs = this;
						//var theArticleId = $(this).data('artileid');
						layer.confirm('确认要删除吗？',function(index){
						//向后端发出删除请求
							$.ajax({
								url:'../server/ajax/themember.php',
								data:{'turl':'memberDel','memberId':memberid},
								type:"get",
								dataType:"json",
								success:function(data){
									console.log("==========删除fm返回后端数据============");
									console.log(data);
									if(data.status == 200){
										$(abs).parents("tr").remove();
										layer.msg('已删除!',{icon:1,time:1000});
									}
									else{
										layer.msg('删除失败!',{icon:1,time:1000});
									}
								}
							})
						})
					})		
				}				
			}		
		}		
	}
		
	userDate.creatInfo();
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
		},

		//获取相关的参数，按需进行调用
		selfControl:function(type ='all',name){
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
	}
	//theReg.getUrlParamsReg(name);
	//theReg.selfControl('pathname')
	
	//综合通用
	this.commUtil = {
		//字符串转成数组
		strChangeArr:function(theStr){
			var arr = theStr.split(",")	
			return arr;
		},
		
		//html转义以及反转义
		//反转义
		HTMLEncode:function(str){
			var theHtml ="";
			if(str.length == 0){
				return ""		
			}
			else{
				theHtml = str.replace(/&/g,"&amp;");
				theHtml = theHtml.replace(/</g,"&lt;");
				theHtml = theHtml.replace(/>/g,"&gt;");
				theHtml = theHtml.replace(/ /g,"&nbsp;");
				theHtml = theHtml.replace(/\'/g,"&#39;");
				theHtml = theHtml.replace(/\"/g,"&quot;");
				return theHtml; 
			}
		},
		HTMLDecode:function(str){
			var theDHtml = ""; 
			if(str.length == 0){
				return "";		
			}  
			else{
				theDHtml = str.replace(/&amp;/g,"&");
				theDHtml = theDHtml.replace(/&lt;/g,"<");
				theDHtml = theDHtml.replace(/&gt;/g,">");
				theDHtml = theDHtml.replace(/&nbsp;/g," ");
				theDHtml = theDHtml.replace(/&#39;/g,"\'");
				theDHtml = theDHtml.replace(/&quot;/g,"\"");
				return theDHtml; 									
			}
		}
		
	}
	
	
		
	//网络查询类
	//获取用户信息
	this.commUser = {
		getUserInfo:function(){
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
					that.userInfo = theDataArray;
				}
			})
			console.log("=========网络查询类获取用户信息（内）============");
			console.log(that.userInfo)
			return that.userInfo;
		},
		
		//根据验证信息来返回用户信息
		getUserInfoDetail:function(){
			$.ajax({
				url:"../server/ajax/thelogin.php",
				data:{turl:"loginYz"},
				type:"get",
				dataType:"json",
				async:false,
				success:function(data){
					console.log("=============后端获取的验证信息==============");
					console.log(data);
					that.userInfoDetail = data;
				}
			})
			return that.userInfoDetail;
		}								
	}
	//获取文章类型的数据返回
	this.commAricle = {
		//获取分类列表
		getArticleCategoryJson:function(selectType='article'){
			$.ajax({
				url:"../server/ajax/thecategory.php",
				type:"get",
				data:{turl:"listCategory",'the-category-type':selectType},
				dataType:"json",
				async:false,//需要同步数据处理将得到的值传递给that.categoryJson
				success:function(data){
					console.log("===============后端返回的文章静态化分类列表数据==========");
					console.log(data);
					that.categoryJson = data;
				}
			})
			return that.categoryJson;
		},
		
		//根据文章id返回详细的数据信息
		getEditArticleInfo:function(articleId){
			//向后端发出获取文章信息的请求
			$.ajax({
				url:"../server/ajax/thearticle.php",
				data:{turl:"getArticleInfo",articleId:articleId},
				type:'get',
				dataType:'json',
				async:false,//需要同步数据处理将得到的值传递给that.articleInfo
				success:function(data){
					console.log("==========获取从后端返回的文章信息==============");
					console.log(data);
					that.articleInfo = data;
				}
			})
			//console.log("==========aaa获取从后端返回的文章信息==============");
			//console.log(that.articleInfo);
			return that.articleInfo;
		},	

		//根据文章id获取对应的视频信息
		getEditArticleVideo:function(theArticleId){
			//向后端发出请求
			$.ajax({
				url:"../server/ajax/thevideo.php",
				data:{turl:"getArticleVideoArray",articleId:theArticleId},
				type:'get',
				dataType:'json',
				async:false,//需要同步数据处理将得到的值传递给that.articleVideo
				success:function(data){
					console.log("===========后端返回的video列表=========");
					console.log(data);
					that.articleVideo = data.result;
				}
			})
			return that.articleVideo;
		}
	}
	
	//获取用户类型返回
	this.memberUtil = {
		//获取用户返回的信息
		theMemberYz:function(){
			$.ajax({
				url:"../server/ajax/thelogin.php",
				data:{turl:"loginYz"},
				type:'get',
				dataType:'json',
				async:false,
				success:function(data){
					console.log("===============后端用户验证后返回的数据类型==============");
					console.log(data);
					that.memberInfo = data;
				}
			})
			return that.memberInfo;
		},
		//根据用户名获取用户信息(超级管理员操作)
		getMemberInfo:function(userName){
			$.ajax({
				url:"../server/ajax/thelogin.php",
				data:{turl:"userNameGetInfo",theUserName:userName},
				type:'get',
				dataType:'json',
				async:false,
				success:function(data){
					console.log("============后端返回的用户数据===============");
					console.log(data);
					that.memberInfo = data;
				}
			})
			return that.memberInfo;
		},
		
		//根据用户名获取封面信息
		getCoverInfo:function(){
			//获取全局的用户名
			console.log("============全局用户的信息（封面）============");
			console.log(theAllUserInfo)
			
			//向后端发出请求数据
			$.ajax({
				url:"../server/ajax/thecover.php",
				data:{turl:"coverList",username:theAllUserInfo.result.username},
				type:"get",
				dataType:"json",
				async:false,
				success:function(data){
					console.log("===================后端返回的封面信息==============");
					console.log(data);
					that.CoverInfo = data;
				}
			})
			
			return that.CoverInfo;
		},
		
		//获取用户的权限列表
		getMemberRole:function(){
			//向后端提出请求
			$.ajax({
				url:"../server/ajax/therole.php",
				data:{turl:"listRole"},
				type:'get',
				dateType:'json',
				async:false,
				success:function(data){
					console.log("============获取用户权限列表===============");
					console.log(data);
					that.roleArray = data;
				}
			})	
			return that.roleArray;
		}			
	}
	
	//获取分类类型返回
	this.categoryUtil = {	
		//selectType:选择返回的分类类型(默认为article)
		//
		getListCategory:function(selectType='article'){
			$.ajax({
				url:"../server/ajax/thecategory.php",
				type:'get',
				data:{
					turl:"listCategory",
					'the-category-type':selectType,
				},
				dataType:"json",
				async:false,
				success:function(data){
					console.log("================分类管理返回的数值===================");					
					console.log(data);	
					
					//html渲染
					//$.each(data,function(index,item){
					//	console.log("============菜单遍历展示=================");
					//	console.log(item);
					//	var category_html = '<option value="'+item.cid+'">'+item.categoryname+'</option>';
					//	$(category_html).appendTo("#category_select");
					//})
					
					that.categoryList = data;										
				}
			})
			
			console.log("==============后端获取的分类列表==================")
			console.log(that.categoryList);
			return that.categoryList
		}
	}	
}