<?php
//自动加载
if (is_file(__DIR__ . '/../autoload.php')) {
    require_once __DIR__ . '/../autoload.php';
}

//命名空间
use Aliyun\Sms;

//参数
$accessKeyId     = 'xxx';
$accessKeySecret = 'xxx';
$signName        = 'xxx';
$templateCode    = 'xxx';

//实例化并发送
$fun = new Sms($accessKeyId, $accessKeySecret, $signName, $templateCode);
$result = $fun->sendSms('1890346xxxx', '1234');
print_r($result);

