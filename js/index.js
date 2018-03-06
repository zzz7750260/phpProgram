$(document).ready(function(){
	loginYZ();
	
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