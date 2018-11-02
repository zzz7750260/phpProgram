<?php
require_once  './php-sdk-master/autoload.php';
//header('Access-Control-Allow-Origin:*');

use Qiniu\Auth;

$bucket = 'a10mins';
$accessKey = 'pO4ylAHksduIAW5hg-AwCRZfaijwzuGyxhbSh45f';
$secretKey = '3UDYeVG0LxWI38M9w5S9xgqdz367QSF_WOLmRQxO';
$auth = new Auth($accessKey, $secretKey);


//$upToken = $auth->uploadToken($bucket);

$policy = array(
    'returnUrl' => 'https://www.10-mins.com/qiniu/fileinfo.php',
    'returnBody' => '{"fname": $(fname)}',
);
$upToken = $auth->uploadToken($bucket, null, 3600, $policy);

echo $upToken;
