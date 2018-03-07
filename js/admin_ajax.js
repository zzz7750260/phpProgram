$(document).ready(function(){
	adminMenuAjax();
	adminRoleAjax();
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
//角色管理
/////////////////////
function adminRoleAjax(){
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
						$listHtml = '<tr class="text-c"><td><input type="checkbox" value="" name=""></td><td>'+item.rid+'</td><td>'+item.rolename+'</td><td><a href="#">赵六</a>，<a href="#">钱七</a></td><td>'+item.rolems+'</td><td class="f-14"><a title="编辑" href="javascript:;" onclick="admin_role_edit(\'角色编辑\',\'admin-role-add.html\',\''+item.rid+'\')" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a> <a title="删除" href="javascript:;" onclick="admin_role_del(this,\''+item.rid+'\')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a></td></tr>';
						
						alert(item);
						$($listHtml).appendTo(".role-table-body");
						
					})					
				}
			})			
		},		
	}
	theRole.addRole();
	theRole.listRole();
}