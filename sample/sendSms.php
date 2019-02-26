<?php
//自动加载
if (is_file(__DIR__ . '/../autoload.php')) {
    require_once __DIR__ . '/../autoload.php';
}

//命名空间


//参数
$accessKeyId     = 'LTAIrvHQztFVfYzL';
$accessKeySecret = 'rcGXcujTVYbJQAjP5j5dC4kWfeAGFM';
$signName        = '山西岐伯';
$templateCode    = 'SMS_100985112';

//实例化并发送
$fun = new \Yanghongwei\AliyunSms\Sms($accessKeyId, $accessKeySecret, $signName, $templateCode);
$result = $fun->sendSms('18903467858', '1234');
print_r($result);
print_r($result);

