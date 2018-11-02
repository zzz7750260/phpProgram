<?php
	require('./php-sdk-master/autoload.php');
	
		//获取token	
		use Qiniu\Auth;
		use Qiniu\Storage\UploadManager;
		$accessKey = 'pO4ylAHksduIAW5hg-AwCRZfaijwzuGyxhbSh45f';
		$secretKey = '3UDYeVG0LxWI38M9w5S9xgqdz367QSF_WOLmRQxO';
		$bucket = 'a10mins';
		$auth = new Auth($accessKey, $secretKey);
		// 上传文件到七牛后， 七牛将文件名和文件大小回调给业务服务器.
		// 可参考文档: http://developer.qiniu.com/docs/v6/api/reference/security/put-policy.html
		//$policy = array(
		//	'callbackUrl' => 'https://www.10mins.com/qiniu/fileinfo.php',
		//	'callbackBody' => 'filename=$(fname)&filesize=$(fsize)'
		//);
		//$uptoken = $auth->uploadToken($bucket, null, 3600, $policy);
		
		$token = $auth->uploadToken($bucket);
		
		
		//echo $uptoken;
		//上传文件的本地路径
		
		$rootPath = $_SERVER["DOCUMENT_ROOT"];
		//$fileName = '/img/qiniu/bg.png';
		
		$filePath = $rootPath . $fileName;
		
		$key = substr($fileName,1);//利用substr截取掉前面的/
		echo "查看key：".$key;	
		
		//$filePath = 'https://www.10-mins.com/img/qiniu/bg.png';
		
		echo "路径:".$filePath;
		$uploadMgr = new UploadManager();
		list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
		echo "\n====> putFile result: \n";
		if ($err !== null) {
			var_dump($err);
		} else {
			var_dump($ret);
		}
	
	
	//inputToQiniu('/img/qiniu/bg.png');
	
	