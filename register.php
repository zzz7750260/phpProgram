<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>后台登录-X-admin2.0</title>
	<meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
	<link rel="stylesheet" href="./css/index.css">
	<link rel="stylesheet" href="./css/login.css">
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
   
</head>
<body class="login-bg">
    
    <div class="login">
        <div class="message">x-admin2.0-管理登录</div>
        <div id="darkbannerwrap"></div>
        
       
           <div class="register-k"> 
			   <label>用户名：</label>
			   <input name="username" placeholder="用户名"  type="text" lay-verify="required" class="layui-input rUsername value-v"> 
				<p class="usernameis"></p>
		   </div>		   			
            <hr class="hr15">
			
			<div class="register-k">
				<label>密码：</label>
				<input name="password" lay-verify="required" placeholder="密码"  type="password" class="layui-input rPassword value-v">
				<p class="passwordis"></p>
			</div>
            <hr class="hr15">
			
			<div class="register-k">
				<label>重复密码：</label>
				<input name="password-yz" lay-verify="required" placeholder="密码验证"  type="password" class="layui-input rPasswordYz value-v">
				<p class="passwordisYz"></p>
			</div>
            <hr class="hr15">

			<div class="register-k">
			   <label>电子邮箱：</label>
			   <input type="email" name="email" placeholder="电子邮箱"  type="text" lay-verify="required" class="layui-input rEmail value-v">
			   	<p class="eMailisYz"></p>
			</div>
			<hr class="hr15">
			
            <input value="注册" lay-submit lay-filter="login" style="width:100%;" type="submit" disabled="disabled" class="tlogin">
            <hr class="hr20" >
       
    </div>
</body>
    <script type="text/javascript" src="./js/index.js"></script>
</html>
