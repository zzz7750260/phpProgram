<?php 
include("../qiniu/php-sdk-master/autoload.php");
use Qiniu\Auth;
		// 引入上传类
use Qiniu\Storage\UploadManager;
class qiniuUtil{
	
	//设置七牛生成的token
	//$policy:设置生成的返回凭证， 类型为数组;https://developer.qiniu.com/kodo/manual/1206/put-policy
	function setQiniuToken($policy){	
		// 需要填写你的 Access Key 和 Secret Key
		$accessKey = "pO4ylAHksduIAW5hg-AwCRZfaijwzuGyxhbSh45f";
		$secretKey = "3UDYeVG0LxWI38M9w5S9xgqdz367QSF_WOLmRQxO";
		$bucket = "a10mins";
		// 构建鉴权对象
		$auth = new Auth($accessKey, $secretKey);
		if(!$policy){
			// 生成上传 Token
			$token = $auth->uploadToken($bucket);
		}
		else{
			$token = $auth->uploadToken($bucket, null, $policy, true);
		}
		return $token;
	}
		
}

$qiniuUtil = new qiniuUtil();
$policy = array(
    'callbackUrl' => 'http://api.example.com/qiniu/upload/callback',
    //'callbackBody' => 'key=$(key)&hash=$(etag)&bucket=$(bucket)&fsize=$(fsize)&name=$(x:name)',
);

echo $qiniuUtil->setQiniuToken('');