<?php
// @codingStandardsIgnoreFile
//require_once __DIR__ . '/../autoload.php';
//phpinfo();

require('../autoload.php');

use Qiniu\Auth;

$accessKey = getenv('pO4ylAHksduIAW5hg-AwCRZfaijwzuGyxhbSh45f');
$secretKey = getenv('3UDYeVG0LxWI38M9w5S9xgqdz367QSF_WOLmRQxO');
$testAuth = new Auth($accessKey, $secretKey);

$bucketName = 'a10mins';
$key = 'php-logo.png';
$key2 = 'niu.jpg';

$token = $testAuth->uploadToken($bucketName);
echo $token;




$bucketNameBC = 'phpsdk-bc';
$bucketNameNA = 'phpsdk-na';

$dummyAccessKey = 'abcdefghklmnopq';
$dummySecretKey = '1234567890';
$dummyAuth = new Auth($dummyAccessKey, $dummySecretKey);

//cdn
$timestampAntiLeechEncryptKey = getenv('QINIU_TIMESTAMP_ENCRPTKEY');
$customDomain = "http://phpsdk.qiniuts.com";

$tid = getenv('TRAVIS_JOB_NUMBER');
if (!empty($tid)) {
    $pid = getmypid();
    $tid = strstr($tid, '.');
    $tid .= '.' . $pid;
}

function qiniuTempFile($size)
{
    $fileName = tempnam(sys_get_temp_dir(), 'qiniu_');
    $file = fopen($fileName, 'wb');
    if ($size > 0) {
        fseek($file, $size - 1);
        fwrite($file, ' ');
    }
    fclose($file);
    return $fileName;
}
