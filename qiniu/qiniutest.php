<?php
require('./php-sdk-master/autoload.php');
// 引入鉴权类
use Qiniu\Auth;
// 引入上传类
use Qiniu\Storage\UploadManager;
// 需要填写你的 Access Key 和 Secret Key
$accessKey = getenv('pO4ylAHksduIAW5hg-AwCRZfaijwzuGyxhbSh45f');
$secretKey = getenv('3UDYeVG0LxWI38M9w5S9xgqdz367QSF_WOLmRQxO');
$bucket = getenv('a10mins');
// 构建鉴权对象
$auth = new Auth($accessKey, $secretKey);
// 生成上传 Token
$token = $auth->uploadToken($bucket,null,3600);

// 构建 UploadManager 对象
$uploadMgr = new UploadManager();

echo $token;

// 要上传文件的本地路径
$filePath = '../upload/user_cover/admin_1.jpg';
// 上传到七牛后保存的文件名
$key = 'admin-php-logo.png';
// 初始化 UploadManager 对象并进行文件的上传。
$uploadMgr = new UploadManager();
// 调用 UploadManager 的 putFile 方法进行文件的上传。
list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
echo "\n====> putFile result: \n";
if ($err !== null) {
    var_dump($err);
} else {
    var_dump($ret);
}