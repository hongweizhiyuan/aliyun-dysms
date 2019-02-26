<?php
// +----------------------------------------------------------------------
// | 阿里云短信发送方法
// +----------------------------------------------------------------------
// | Author: 子弹兄  Date:2019-02-24 Time:21:08
// +----------------------------------------------------------------------
namespace Yanghongwei\AliyunSms;

use Yanghongwei\AliyunSms\lib\SignatureHelper;

class Sms
{
    private $accessKeyId;
    private $accessKeySecret;
    private $signName;
    private $templateCode;

    /**
     * Sms constructor.
     * @author hongwei 2019-02-14
     * @param string $accessKeyId AK信息ID
     * @param string $accessKeySecret AK信息ID密码
     * @param string $signName 短信签名
     * @param string $templateCode 短信模板Code
     */
    public function __construct($accessKeyId, $accessKeySecret, $signName, $templateCode)
    {
        try {
            $accessKeyId    = trim($accessKeyId);
            $accessKeySecret    = trim($accessKeySecret);
            $signName    = trim($signName);
            $templateCode    = trim($templateCode);

            if (empty($accessKeyId)) {
                throw new \Exception("accessKeyId is empty");
            }
            if (empty($accessKeySecret)) {
                throw new \Exception("accessKeySecret is empty");
            }
            if (empty($signName)) {
                throw new \Exception("signName is empty");
            }
            if (empty($templateCode)) {
                throw new \Exception("templateCode is empty");
            }

            $this->accessKeyId = $accessKeyId;
            $this->accessKeySecret = $accessKeySecret;
            $this->signName = $signName;
            $this->templateCode = $templateCode;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * 公共接收方法
     * @author hongwei 2019-02-24
     * @param string $phone 短信接收号码
     * @param string $action 发送行为，单发SendSms，群发SendBatchSms
     * @param string $code 验证码
     * @return bool|\stdClass
     */
    public function request($phone, $action, $code)
    {
        $id     = $this->accessKeyId;
        $secret = $this->accessKeySecret;
        //step1:发送
        $security = false;                              //必填：是否启用https
        $domain   = 'dysmsapi.aliyuncs.com';            //短信发送网关
        //step2:参数
        $paramsOne = [
            "PhoneNumbers"  => $phone,                  //参数1: 短信接收号码（必填）
            "SignName"      => $this->signName,         //参数2: 短信签名（必填）
            "TemplateCode"  => $this->templateCode,     //参数3: 短信模板Code（必填）,
            "TemplateParam" => '{"code":'.$code.'}'     //参数4：验证码

            //'OutId'           => "12345",             //可选: 设置发送短信流水号
            //'SmsUpExtendCode' => "1234567",           //可选: 设置发送短信流水号
        ];
        //step3
        $paramsTwo = [
            "RegionId" => "cn-hangzhou",
            "Action"   => $action,
            "Version" => "2017-05-25",
        ];
        $params = array_merge($paramsOne, $paramsTwo);

        // 初始化SignatureHelper实例用于设置参数，签名以及发送请求
        $helper = new SignatureHelper();

        // 此处可能会抛出异常，注意catch
        $content = $helper->request($id, $secret, $domain, $params, $security);

        //返回
        return $content;
    }

    /**
     * 短信下发
     * 说明：相同的签名，发送一条或者多条内容给不同的手机号码
     * @author hongwei 2019-02-14
     * @param string $phones 手机号，支持多手机号，用逗号隔开
     * @param string $code 验证码
     * @return bool|\stdClass
     * @doc https://help.aliyun.com/document_detail/101414.html?spm=a2c4g.11186623.6.580.345230bb3OUcY9
     */
    public function sendSms($phones, $code)
    {
        try {
            $result = $this->request($phones, 'SendSms', $code);
        } catch (\Exception $e) {
            echo 'Message is:'.$e->getMessage(), '，Code is '.$e->getCode();
            exit;
        }
        return $result;
    }

    /**
     * 短信下发
     * 说明：发送多条不同的短信
     * @param string $phones 手机号
     * @param string $code 验证码
     * @doc https://help.aliyun.com/document_detail/102364.html?spm=a2c4g.11186623.6.579.345230bbIMjjvF
     */
    public function sendBatchSms($phones, $code)
    {
        //目前没有这需要，暂时不做了
    }
}