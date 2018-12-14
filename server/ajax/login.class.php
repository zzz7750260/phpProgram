<?php 
session_start();
include('../system.util.php');
class theLogin{	
	//获取的参数(构造函数)
	//function __construct(){				
	//}
	
	function loginUsernameAjax(){
		//echo $theUserName;
		//echo $thePassWord;
		$theUserName = $_GET['username'];
		$thePassWord = $_GET['password'];
		//echo $theUserName;
		$sql = "select * from member where username = '$theUserName'";
		$sql_db = mysql_query($sql);

		$num = mysql_num_rows($sql_db);

		echo $num;				
		return $num;	
	}
	
	
	
	function loginYz(){
		$theUserName = $_SESSION['username'];
		$thePassWord = $_SESSION['password'];
		
		$sql = "select * from member where username = '$theUserName' and password = '$thePassWord'";
		$sql_db = mysql_query($sql);

		$num = mysql_num_rows($sql_db);
		
		//echo $num;
		//return $num;
		/*
		$hostUrl = $_SERVER['HTTP_HOST'];

		echo $num."<br/>";
		echo $hostUrl;

		$theURL = "http://".$hostUrl."/";
		
		if($num == 0){
			echo "登录密码错误!";
			echo '<script>setTimeout("window.location.href="'.$theURL .'loginYz.php",3000)';
		}
		else{
			echo "登录成功";
			echo '<script>setTimeout(function(){window.location.href="'.$theURL .'/admin/"},6000)</script>';
		}
		*/		
		
		//设置返回信息
		$loginYzReturnArray = array(); 
		
		//获取是否存在传递过来的用户名或者session_token，说明是从登录的地方过来的
		if(!$_SESSION['username'] || !$_SESSION['session_token']){
			$loginYzReturnArray = array(
				status => 400,
				msg => '用户不合法',
				result => ''
			);							
		}
		else{
			//当存在session_token时，为了防止session_token被盗取，需要根据session_token获取一次用户信息，如果和登录的用户名相同时，为正确					
			$theToken = $_SESSION['session_token'];
			$yzSqlArray = array();
			$yzSql = "select * from member where session_token = '$theToken'";
			$yzSql_db = mysql_query($yzSql);
			while($yzSql_db_array = mysql_fetch_assoc($yzSql_db)){
				$yzSqlArray = $yzSql_db_array;		
			}	
			//如果获取到的用户名和session的用户名相同时，说明为同一个用户登录
			if($_SESSION['username'] == $yzSqlArray['username']){
				$loginYzReturnArray = array(
					status => 200,
					msg => '用户为合法用户',
					result => $yzSqlArray
				);				
			}
			else{
				$loginYzReturnArray = array(
					status => 500,
					msg => '用户账户或密码不正确',
					result => ''
				);								
			}			
		}
		//返回json的数组
		$loginYzReturnJson = json_encode($loginYzReturnArray);
		print_r($loginYzReturnJson);
	}
	
	//后端验证登录（ajax）
	function loginYzBack(){
		$theUserName = $_POST['theUserName'];
		$thePassword = $_POST['thePassword'];
		
		//检测用户用户名和密码是否有对应的存在
		$isHaveUserSql = "select * from member where username = '$theUserName' and password = '$thePassword'";
		$isHaveUserSql_db = mysql_query($isHaveUserSql);
		$isHaveUserSql_db_num = mysql_num_rows($isHaveUserSql_db);
		if($isHaveUserSql_db_num>0){
			$_SESSION['username'] = $theUserName;
			$_SESSION['password'] = $thePassword;
			$theUtil = new util();
			$theToken = $theUtil->setSessionToken(8);
			
			//获取最后登录的时间
			$loginTime = date("Y-m-d h:i:sa");			
			
			//获取登录的ip
			$theUtil = new util();
			$loginIp = $theUtil->getUserIp();
			
			
			//更改数据库
			$updataUserToken = "update member set session_token = '$theToken',login_ip = '$loginIp',login_time = '$loginTime' where username = '$theUserName' and password = '$thePassword'";
			$updataUserToken_db = mysql_query($updataUserToken);
			
			if($updataUserToken_db){
				$_SESSION['session_token'] = $theToken;
			}
			
			//进行用户名验证
			$this->loginYz();
		}
		else{
			$loginYzReturnArray = array(
				status => 600,
				msg => '用户账户或密码不正确',
				result => ''
			);				
			$loginYzReturnJson = json_encode($loginYzReturnArray);
			print_r($loginYzReturnJson);
		}
	}
	
	
	//获取用户的信息
	function getUserInfo(){
		$theUserNames = $_SESSION['username'];
		$thePassWords = $_SESSION['password'];
		$userInfoArray = array();
		//echo $theUserNames;
		$user_sql = "select a.*,b.* from member as a left join role as b on a.role = b.roleyw where a.username = '$theUserNames' and a.password = '$thePassWords'";
		$user_sql_db = mysql_query($user_sql);
		while($user_sql_db_array = mysql_fetch_assoc($user_sql_db)){
			$userInfoArray = $user_sql_db_array;	
		}
		//print_r($userInfoArray);
		//echo "<br/>";
		//转为json数据
		$userJson = json_encode($userInfoArray);
		print_r($userJson);
	}
	
	//根据用户名获取用户信息
	function userNameGetInfo(){
		//echo "aaa";
		$theUserName = $_GET['theUserName'];
		$userNameInfoSql = "select * from member where username = '$theUserName'";
		$userNameInfoSql_db = mysql_query($userNameInfoSql);
		if($userNameInfoSql_db){
			$userNameInfoArray = array();
			while($userNameInfoSql_db_array = mysql_fetch_assoc($userNameInfoSql_db)){
				$userNameInfoArray = $userNameInfoSql_db_array;		
			}
			//组装返回数组
			$returnMemberArray = array(
				status => 200,
				msg => "返回用户信息成功",
				result => $userNameInfoArray
			); 
		
		}
		else{
			//组装返回数组
			$returnMemberArray = array(
				status => 400,
				msg => "该用户信息不存在",
				result => ''
			);					
		}
		//将数组转成json返回给前端
		$returnMemberJson = json_encode($returnMemberArray);
		print_r($returnMemberJson);
	}
	
	//根据传进来的权限放回对应的菜单数据
	function getMenu($therole,$thepid){		
		//$menuList = array();
		//$menuListChild = array();
		$menuRoleSql = "select * from menu where menurole like '%$therole%' and mpid = '$thepid'";
		$menuRoleSql_db = mysql_query($menuRoleSql);
		$i = 0;
		while($menuRoleSql_db_arr = mysql_fetch_assoc($menuRoleSql_db)){
			$menuList[$i] = $menuRoleSql_db_arr;
			//$trole = $menuList[$i]['menurole'];
			$tpid = $menuList[$i]['mid'];
			//echo "角色:".$trole."<br>";
			//echo "id:".$tpid."<br>";;					
			$menuList[$i]['child'] = $this->getMenu($therole,$tpid);;
			$i++;
		}
		//print_r($menuList);
		return $menuList;		
	}
	
	
	//用户登出
	function loginOut(){
		//$_SESSION['username'] == '';
		//$_SESSION['password'] == '';
		//$_SESSION['session_token'] == '';
		$_SESSION = array();
		//组装返回前端数组
		$returnLoginArray = array(
			status => 200,
			msg => '用户退出成功',
			result => ''
		);
		
		$returnLoginJson = json_encode($returnLoginArray);
		print($returnLoginJson);
	}
		
	//前端页面用户的返回信息
	function webUserInfo(){
		$theToken = $_SESSION['session_token'];
		//根据token获取相关的用户信息
		$getUserInfoSql = "select username,the_name,sex,email,tel,join_time,user_head,user_introduction from member where session_token = '$theToken'";
		$getUserInfoSql_db = mysql_query($getUserInfoSql);
		if($getUserInfoSql_db){
			$getUserInfoSql_db_array = mysql_fetch_assoc($getUserInfoSql_db);
			//组装返回前端用户信息
			$returnUserInfoArray = array(
				status => 200,
				msg => '用户信息返回成功',
				result => $getUserInfoSql_db_array
			);
		}
		else{
			//组装返回前端用户信息
			$returnUserInfoArray = array(
				status => 400,
				msg => '用户信息返回失败',
				result => ''
			);			
		}
		$returnUserInfoJson = json_encode($returnUserInfoArray);
		print_r($returnUserInfoJson);		
	}
	
	
	//后端获取用户主页返回的信息
	function indexUserInfo(){
		$theToken = $_SESSION['session_token'];
		//根据token获取用户信息
		$theUserInfoSql = "select * from member where session_token ='$theToken'";
		$theUserInfoSql_db = mysql_query($theUserInfoSql);
		$theUserInfoSql_db_info = mysql_fetch_assoc($theUserInfoSql_db);		
		
		$userInfo = array();
	
		$theDate = date("Y-m-d");
						
		if($theUserInfoSql_db_info['role'] == 'admin'){
			$theUserInfoRole = $theUserInfoSql_db_info['role'];
			$theUserInfoArticleArray = array();
			$theUserInfoArticleSql = "select * from article where article_time like '%$theDate%'";
			$theUserInfoArticleSql_db = mysql_query($theUserInfoArticleSql);
			while($theUserInfoArticleSql_db_array = mysql_fetch_assoc($theUserInfoArticleSql_db)){
				$theUserInfoArticleArray[] = $theUserInfoArticleSql_db_array;	
			}
			
			$theUserFmArray = array();
			$theUserFmArraySql = "select * from fm where f_time like '%$theDate%'";
			$theUserFmArraySql_db = mysql_query($theUserFmArraySql);
			while($theUserFmArraySql_db_array = mysql_fetch_assoc($theUserFmArraySql_db)){
				$theUserFmArray[] = $theUserFmArraySql_db_array;	
			}
			
			$theArticleComment = array();		
			$theArticleCommentSql = "select a.title,b.* from article as a left join comment as b on a.aid = b.cmtid where b.cm_time like '%$theDate%'";
			$theArticleCommentSql_db = mysql_query($theArticleCommentSql);
			while($theArticleCommentSql_db_array = mysql_fetch_assoc($theArticleCommentSql_db)){
				$theArticleComment[] = $theArticleCommentSql_db_array;
			}
			
			$theMemberArray = array();
			$theMemberArraySql = "select * from member where join_time like '%$theDate%'";
			$theMemberArraySql_db = mysql_query($theMemberArraySql);
			while($theMemberArraySql_db_array = mysql_fetch_assoc($theMemberArraySql_db)){
				$theMemberArray[] = $theMemberArraySql_db_array;
			}
			
			$userInfo = array(
				status => 200,
				msg => '用户信息返回成功',
				result => array(
					'userNum' => array(
						'total' => array(
							'video' => $this->userTotalNum('article'),
							'fm' => $this->userTotalNum('fm'),
							'comment' => $this->userTotalNum('comment'),
							'member' => $this->userTotalNum('member'),
						),
						'today' => array(
							'video' => $this->todayNum('article','article_time',$theDate),
							'fm' => $this->todayNum('fm','f_time',$theDate),
							'comment' => $this->todayNum('comment','cm_time',$theDate),
							'member' => $this->todayNum('member','join_time',$theDate),
						),
					),
					'userInfo' => $theUserInfoSql_db_info,
					'articleArray' => $theUserInfoArticleArray,
					'fmArray' => $theUserFmArray,
					'commentArray' => $theArticleComment,
					'memberArray' => $theMemberArray,
				),
			);
		}
		else{
			$theUserInfoName = $theUserInfoSql_db_info['username'];
			$theUserInfoArticleArray = array();
			$theUserInfoArticleSql = "select * from article where article_author = '$theUserInfoName' order by aid DESC";
			$theUserInfoArticleSql_db = mysql_query($theUserInfoArticleSql);
			while($theUserInfoArticleSql_db_array = mysql_fetch_assoc($theUserInfoArticleSql_db)){
				$theUserInfoArticleArray[] = $theUserInfoArticleSql_db_array;	
			}			
			
			$theUserFmArray = array();
			$theUserFmArraySql = "select * from fm where f_admin = '$theUserInfoName' order by fid DESC";
			$theUserFmArraySql_db = mysql_query($theUserInfoArticleSql);
			while($theUserFmArraySql_db_array = mysql_fetch_assoc($theUserInfoArticleSql_db)){
				$theUserFmArray[] = $theUserFmArraySql_db_array;	
			}
			
			//查询相关评论
			$theArticleComment  = array();
			$theArticleCommenSql = "select a.title,b.* from article as a left join comment as b on a.aid = b.cmtid where a.article_author = '$theUserInfoName' order by b.cmid DESC";		
			$theArticleCommenSql_db = mysql_query($theArticleCommenSql);
			
			while($theArticleCommenSql_db_array = mysql_fetch_assoc($theArticleCommenSql_db)){
				$theArticleComment[] = $theArticleCommenSql_db_array;
			}
			
			$userInfo = array(
				status => 200,
				msg => '用户信息返回成功',
				result => array(
					'userInfo' => $theUserInfoSql_db_info,
					'articleArray' => $theUserInfoArticleArray,
					'fmArray' => $theUserFmArray,
					'commentArray' => $theArticleComment,
					'userNum' => array(
						'total' => array(
							'video' => $this->userMemberTotalNum('article','article_author',$theUserInfoRole),
							'fm' => $this->userMemberTotalNum('fm','f_admin',$theUserInfoRole),
							//'comment' => $this->userMemberTotalNum('comment',''),						
						),
					)
				),
			);
		}
		//将数组转为json返回给前端
		$userInfoJson = json_encode($userInfo);
		print_r($userInfoJson);
	}
	
	//公共类：查询相关的返回总数量
	//$selectDb:选择查询的数据库
	function userTotalNum($selectDb){
		$totalNumSql = "select * from $selectDb where 1 =1";
		$totalNumSql_db = mysql_query($totalNumSql);
		$totalNum = mysql_num_rows($totalNumSql_db);
		return $totalNum;
	}
	
	//公共类：查询用户的返回总数量
	//$selectDb:选择查询的数据库
	//$dbKey:选择查询的字段
	//$userName:选择需查询的用户名
	function userMemberTotalNum($selectDb,$dbKey,$userName){
		$totalNumSql = "select * from $selectDb where $dbKey = '$userName'";
		$totalNumSql_db = mysql_query($totalNumSql);
		$totalNum = mysql_num_rows($totalNumSql_db);
		return $totalNum;
	}

	//公共类：查询当天新增的数量
	//$theDate:查询的时间
	//$selectDb:选择查询的数据库
	//$dbData:数据库的时间字段
	function todayNum($selectDb,$dbData,$theDate){
		$todayNumSql = "select * from $selectDb where $dbData like '%$theDate%'";
		$todayNumSql_db = mysql_query($todayNumSql);
		$todayNums = mysql_num_rows($todayNumSql_db);
		return $todayNums;
	}
			
	//根据传进来的页面参数调用
	function theReturn($theUrl){
		if($theUrl =='loginusername'){
			$this->loginUsernameAjax();//$this 非常重要，$this 相当于当前的class
		}
		if($theUrl =='themenu'){
			$therole = $_GET['getrole'];
			$thepid = $_GET['getpid'];
			//echo $therole;
			//echo $thepid;
			//print_r($this-> getMenu($therole,$thepid));					
			//返回json数据
			//echo "<br/>";
			$menujson = json_encode($this-> getMenu($therole,$thepid));
			print_r($menujson);
		}
		
		if($theUrl == 'theUser'){
			$this->getUserInfo();		
		}
		
		if($theUrl == 'loginYz'){
			$this->loginYz();
		}
		if($theUrl == 'userNameGetInfo'){
			$this->userNameGetInfo();		
		}
		if($theUrl == 'loginOut'){
			$this->loginOut();			
		}
		if($theUrl == 'loginYzBack'){
			$this->loginYzBack();
		}
		if($theUrl == 'indexUserInfo'){
			$this->indexUserInfo();
		}
		if($theUrl == 'webUserInfo'){
			$this->webUserInfo();
		}
	}
}


