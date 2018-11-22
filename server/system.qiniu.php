<?php 
include(dirname(dirname(__FILE__))."/qiniu/php-sdk-master/autoload.php");
include(dirname(__FILE__)."/system.util.php");

use Qiniu\Auth;
		// 引入上传类
use Qiniu\Storage\UploadManager;
class qiniuUtil{
	private $theWebRoot; //设置返回的根目录是七牛还是原站点
	
	//设置七牛生成的token
	//$policy:设置生成的返回凭证， 类型为数组;https://developer.qiniu.com/kodo/manual/1206/put-policy
	function setQiniuToken($policy){	
		// 需要填写你的 Access Key 和 Secret Key
		$accessKey = "pO4ylAHksduIAW5hg-AwCRZfaijwzuGyxhbSh45f";
		$secretKey = "3UDYeVG0LxWI38M9w5S9xgqdz367QSF_WOLmRQxO";
		$bucket = "new10mins";
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
	
	//文件上传
	//$thePath:文件上传的路径设置
	function uploadFile($thePath,$policy=''){
		$rootPath = $_SERVER['DOCUMENT_ROOT'];
		$filePath = $rootPath . $thePath;
		$savePath = substr($thePath,1); //七牛路径，截取掉前面的/
		//获取对应的token
		$theToken = $this->setQiniuToken($policy);
		//echo $theToken;
		// 初始化 UploadManager 对象并进行文件的上传。
		$uploadMgr = new UploadManager();
		// 调用 UploadManager 的 putFile 方法进行文件的上传。
		list($ret, $err) = $uploadMgr->putFile($theToken, $savePath, $filePath);
		//echo "\n====> putFile result: \n";
		if ($err !== null) {
			//var_dump($err);
			return $err;
		} else {
			//var_dump($ret);
			return $ret;
		}	
	}

	//设置是否使用七牛cdn
	//select : 设置选择的cdn
	function setQiniuUse($select=""){
		//echo "开始设置";
		$theUtil = new util();	
		switch($select){
			case 'qiniu':
				$this->theWebRoot = "http://cdn.10-mins.com";	
				break;
			default:
				$this->theWebRoot = $theUtil->webPath();
			
		}
		//echo "设置路径：".$this->theWebRoot;
	}
	
	function useQiniuCdnWeb(){
		//echo "路径:".$this->theWebRoot;
		if(!$this->theWebRoot){
			$theUtil = new util();
			$cdnWebUrl = $theUtil->webPath();
		}
		else{
			$cdnWebUrl = $this->theWebRoot;	
		}
		return $cdnWebUrl;
	}
}

//$qiniuUtil = new qiniuUtil();
//$policy = array(
 //   'callbackUrl' => 'http://api.example.com/qiniu/upload/callback',
    //'callbackBody' => 'key=$(key)&hash=$(etag)&bucket=$(bucket)&fsize=$(fsize)&name=$(x:name)',
//);

//echo $qiniuUtil->setQiniuToken('');
//echo $qiniuUtil->uploadFile('/mp3/Lemon Tree.mp3');
