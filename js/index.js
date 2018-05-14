$(document).ready(function(){
	loginYZ();
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
		}	
	}
	theClick.searchClick()
	
}