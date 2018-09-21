<?php
	include("../system.util.php");
	class theWeixin{
		//private $num; //声明查询到结果的个数
		
		//验证微信端传递过来的值是否正确
		function checkData(){
			//获取微信传递
			$timestamp = $_GET['timestamp'];
			$nonce = $_GET['nonce'];
			$token = '10mins';
			$signature = $_GET['signature'];			
			$echostr = $_GET['echostr'];
			//echo $timestamp;
			
			//设置数组并进行排序
			$theArray = array($timestamp, $nonce, $token);
			sort($theArray);
			
			//将数组设置成字符串并进行加密
			$tmpstr = implode('',$theArray);
			$shaTmpstr = sha1($tmpstr);	
			
			//第一次为微信服务器向我的服务器发送验证信息，会有一个echostr传递过来		
			if($shaTmpstr == $signature && $echostr){
				echo $echostr;
				exit;		
			}
			//如果没有$echostr，说明不是微信服务器的验证信息，而是用户通过手机端的推送信息
			else{		
				//调用接受回复信息接口
				$this->reponseMsg();
			}
			
		}
		
		
		//接受事件推送并进行回复
		function reponseMsg(){
			//获取到推送过来的信息(xml)			
			$postArr = $GLOBALS['HTTP_RAW_POST_DATA'];//为xml的格式
			/*
			<xml>
				<ToUserName>< ![CDATA[toUser] ]></ToUserName>
				<FromUserName>< ![CDATA[FromUser] ]></FromUserName>
				<CreateTime>123456789</CreateTime>
				<MsgType>< ![CDATA[event] ]></MsgType>
				<Event>< ![CDATA[subscribe] ]></Event>
			</xml>
			*/
			
			//将接受到的信息以xml的方式存储在wx_test中
			//调用公共类：获取存储的路径
			$theUtil = new util();
			$thePath = $theUtil->physicalPath('/wx_test/require.xml');
			//存储到对应的文件中
			file_put_contents($thePath,$postArr);
					
			
			//对该格式进行处理,将xml转为对象
			$postObj = simplexml_load_string($postArr);
			// $postObj->ToUserName 的方式获取对于的值
			
			//当事件为event时，通过strtolower将值全变为小写
			if(strtolower($postObj->MsgType == "event")){
				//当为关注订阅的事件时
				if(strtolower($postObj->Event == 'subscribe')){
					//自动回复消息
					//设置参数
					$toUser = $postObj->FromUserName;
					$fromUser = $postObj->ToUserName;
					$time = time();
					$msgType = 'text';
					$content = '欢迎关注我的微信公众账号';
					$template="<xml>
								<ToUserName><![CDATA[%s]]></ToUserName>
								<FromUserName><![CDATA[%s]]></FromUserName>
								<CreateTime>%s</CreateTime>
								<MsgType><![CDATA[%s]]></MsgType>
								<Content><![CDATA[%s]]></Content>
								</xml>";
					//利用sprintf()将各个参数传递到模板中
					$info = sprintf($template,$toUser,$fromUser,$time,$msgType,$content);
					//输出
					echo $info;				
				}
											
			}	
			
		
			//当获取到的信息为文字时
			if(strtolower($postObj->MsgType == "text")){
				//查询该内容是否在category中
				$num = $this->checkWordNum("category",$postObj->Content);
				
				//测试：输出$num
				//$thePath = $theUtil->physicalPath('/wx_test/num.txt');
				//存储到对应的文件中
				//file_put_contents($thePath,$num);
								
				
				//结果大于0说明存在结果
				if($num > 0){
					//category输出多图文
					$info = $this->setResponseInfo('duotuwen','category');
					//echo $info;
				}
				else{
					//查询该内容是否在article中
					$num = $this->checkWordNum("article",$postObj->Content);
					//如果结果大于0，说明存在
					if($num >0){
						$info = $this->setResponseInfo("duotuwen","article");
						echo $info;
					}
					else{
						//如果不存在就以文字形式回复
						//查询内容是否存在微信的文字中
						$num = $this->checkWordNum("wx_text",$postObj->Content);
						if($num > 0){
							$info = $this->setResponseInfo('wenzhi');
						}
						else{
							$info = $this->setResponseInfo('wuxinxi');
						}									
					}
					
				}
				
				echo $info;	
			}		

			//当接受到的为图片信息时
			if(strtolower($postObj->MsgType == "image")){
				//暂时不通过数据库
										
				$info = $this->setResponseInfo('tupian');
				
				//测试：输出图片$info
				$thePath = $theUtil->physicalPath('/wx_test/info.xml');
				//存储到对应的文件中
				file_put_contents($thePath,$info);										
				echo $info;
			}			
		}
		
		
		//封装类：封装回复类型
		//封装接收回复的信息，并针对不同的类型的信息进行不同类型的回复
		//$theMsgType : 接收信息类型
		//$theDataTable : 如果为图文时，$theDataTable为需要查询的数据库
		function setResponseInfo($theMsgType,$theDataTable=''){
			//获取传递过来的信息
			$reqPostArr = $GLOBALS['HTTP_RAW_POST_DATA'];//为xml的格式
			$reqPostObj = simplexml_load_string($reqPostArr);  //将xml转为对象
			/*
				<ToUserName>< ![CDATA[toUser] ]></ToUserName>
				<FromUserName>< ![CDATA[FromUser] ]></FromUserName>
				<CreateTime>123456789</CreateTime>
				<MsgType>< ![CDATA[event] ]></MsgType>
				<Event>< ![CDATA[subscribe] ]></Event>
			*/			
					
			//调用公共类：获取网络路径
			$theUtil = new util();
			
			switch($theMsgType){
				case 'wenzhi':
				//获取当中的信息中的内容,查询text表中存在的词
					$toUser =  $reqPostObj->ToUserName;
					$fromUser = $reqPostObj->FromUserName;
					$theWord = $reqPostObj->Content;
					$theTime = time();
					$msgType = "text";
					
					$checkArraySql = "select * from wx_text where wx_require_word = '$theWord'";
					$checkArraySql_db = mysql_query($checkArraySql);
					$checkArray = array();
					while($checkArraySql_db_array = mysql_fetch_assoc($checkArraySql_db)){
						$checkArray = $checkArraySql_db_array;
					}
					//提取回复信息
					$theResponseWord = $checkArray['wx_response_word'];
					
					//设置回复模板
					$template = '';
					$template = "<xml>
								<ToUserName><![CDATA[%s]]></ToUserName>
								<FromUserName><![CDATA[%s]]></FromUserName>
								<CreateTime>%s</CreateTime>
								<MsgType><![CDATA[%s]]></MsgType>
								<Content><![CDATA[%s]]></Content>
								</xml>";
					$info = sprintf($template,$fromUser,$toUser,$theTime,$msgType,$theResponseWord);
					echo $info;
					break;
					
					case 'duotuwen':
						$theWord = $reqPostObj->Content;
						$toUser =  $reqPostObj->ToUserName;
						$fromUser = $reqPostObj->FromUserName;
						$msgType = "news";
						$theTime = time();
						
						//当查询的表为category时
						if($theDataTable == "category"){							
							$theCheckSqlDuotuwenSql = "select a.*, b.* from category as a join article as b on a.cid = b.category_id where a.categoryname = '$theWord'";
						}
						
						if($theDataTable == "article"){							
							$theCheckSqlDuotuwenSql = "select a.*, b.* from article as a join category as b on a.category_id = b.cid where title like '%$theWord%'";
						}		
						
						$theCheckSqlDuotuwenSql_db = mysql_query($theCheckSqlDuotuwenSql);
						$theCheckSqlDuotuwenArray = array();
						while($theCheckSqlDuotuwenSql_db_array = mysql_fetch_assoc($theCheckSqlDuotuwenSql_db)){
							$theCheckSqlDuotuwenArray[] = $theCheckSqlDuotuwenSql_db_array;
						}
						
						//获取数组个数
						$theCheckSqlDuotuwenNum = count($theCheckSqlDuotuwenArray);
						
						//组装template
						$template = '';
						$template = "<xml>
									<ToUserName><![CDATA[%s]]></ToUserName>
									<FromUserName><![CDATA[%s]]></FromUserName>
									<CreateTime>%s</CreateTime>
									<MsgType><![CDATA[%s]]></MsgType>
									<ArticleCount>".$theCheckSqlDuotuwenNum."</ArticleCount><Articles>";
									
						//遍历数组，将值输入到模板中
						
						foreach($theCheckSqlDuotuwenArray as $theCheckSqlDuotuwenKey => $theCheckSqlDuotuwenValue){
							$template .= "<item><Title><![CDATA[".$theCheckSqlDuotuwenValue['title']."]]></Title>
							<Description><![CDATA[".$theCheckSqlDuotuwenValue['article_short']."]]></Description>
							<PicUrl><![CDATA[".$theUtil->wxPath('/upload/cover/').$theCheckSqlDuotuwenValue['article_img']."]]></PicUrl>
							<Url><![CDATA[".$theUtil->wxPath('/article/').$theCheckSqlDuotuwenValue['categoryyw']."/".$theCheckSqlDuotuwenValue['aid'].".html]]></Url>
							</item>";						
						}
						$template .= "</Articles></xml>";
						
						$info = sprintf($template,$fromUser,$toUser,$theTime,$msgType);
					break;
					
					case "tupian":						
						$toUser = $reqPostObj->ToUserName;
						$fromUser = $reqPostObj->FromUserName;
						$msgType = "image";
						$theTime = time();
						$theMediaId = $reqPostObj->MediaId;
						$template = '';
						$template = "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[%s]]></MsgType>
						<Image><MediaId><![CDATA[%s]]></MediaId></Image>
						</xml>";					
						$info = sprintf($template,$fromUser,$toUser,$theTime,$msgType,$theMediaId);
					break;	
																										
					case 'wuxinxi':
						$toUser =  $reqPostObj->ToUserName;
						$fromUser = $reqPostObj->FromUserName;
						$theTime = time();
						$msgType = "text";
						$theResponseWord = "暂无对应的回复";
						$template = "<xml>
									<ToUserName><![CDATA[%s]]></ToUserName>
									<FromUserName><![CDATA[%s]]></FromUserName>
									<CreateTime>%s</CreateTime>
									<MsgType><![CDATA[%s]]></MsgType>
									<Content><![CDATA[%s]]></Content>
									</xml>";
						$info = sprintf($template,$fromUser,$toUser,$theTime,$msgType,$theResponseWord);
				
			}
			return $info;
		}
		
		//封装类：封装查询数据库中是否有对应的信息存在
		//$theDataTable：对应数据库存在的表格;
		//$theWord：需要查询的数据词
		function checkWordNum($theDataTable,$theWord){
			switch($theDataTable){
				case "wx_text":
					$theCheckSql = "select * from wx_text where wx_require_word like '%$theWord%'";
					break;
				case "category":
					$theCheckSql = "select * from category where categoryname = '$theWord'";
					break;
				case "article":
					$theCheckSql = "select * from article where title like '%$theWord%'";
					break;
				case "page":
					$theCheckSql = "select * from page where ptitle like '%$theWord%'";
			}
			
			$theCheckSql_db = mysql_query($theCheckSql);
			$theCheckSql_db_num = mysql_num_rows($theCheckSql_db);
			return $theCheckSql_db_num;
		}
		
		//封装类，设置curl获取
		function curl_http($theUrl){
			//初始化url
			$ch = curl_init();
			//设置curl的参数
			curl_setopt($ch, CURLOPT_URL,$theUrl);
			//curl_setopt($ch,CURLOPT_HEADER, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);		
			//针对https请求时，需要验证ssl的证书，需要设置跳过验证
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
			//调用接口
			$res = curl_exec($ch);
			//关闭curl
			//判断是否存在错误
			if(curl_errno($ch)){
				var_dump(curl_errno($ch));
				
			}
			curl_close($ch);			
			//print_r($res);
			//将得到的json转换为数组
			//$arr = json_decode($res,true);
			
			//var_dump($arr);
			return $res;  //返回结果					
		}
		
		//获取微信的AccessToken
		function getWxAccessToken(){
			//设置appid
			$theAppId = "wx0a1f3e33314d55e0";
			//设置appSecret
			$theAppSecret = "d7e71ee8b4bf6c9d518e2ae0b8f85217";
			
			//设置请求的url
			$theUrl = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$theAppId."&secret=".$theAppSecret;
			
			//通过curl_http的方法获取到对应的数组
			$res = $this->curl_http($theUrl);
			print_r($res);
		}								
		function theReturn($turl){
			if($turl == "checkData"){
				$this->checkData();			
			}
			if($turl == "getWxAccessToken"){
				$this->getWxAccessToken();
			}
		}				
	}
?>