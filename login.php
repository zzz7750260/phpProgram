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
        
        <form method="get" class="layui-form" action="./server/ajax/loginYz.php">
            <input name="username" placeholder="用户名"  type="text" lay-verify="required" class="layui-input tusername">
			<p class="usernameis"></p>
            <hr class="hr15">
            <input name="password" lay-verify="required" placeholder="密码"  type="password" class="layui-input tpassword">
			<p class="passwordis"></p>
            <hr class="hr15">
            <input value="登录" lay-submit lay-filter="login" style="width:100%;" type="submit" disabled="disabled" class="tlogin">
            <hr class="hr20" >
        </form>
    </div>
</body>
    <script type="text/javascript" src="./js/index.js"></script>
</html>
