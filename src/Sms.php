<?php
// +----------------------------------------------------------------------
// | 阿里云短信发送方法
// +----------------------------------------------------------------------
// | Author: 子弹兄  Date:2019-02-24 Time:21:08
// +----------------------------------------------------------------------
namespace Aliyun;

use Aliyun\lib\SignatureHelper;

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
     * @return bool|\stdClass
     */
    public function request($phone, $action, $code)
    {
        $id = $this->accessKeyId;
        $secret = $this->accessKeySecret;
        //step1:发送
        $security = false;  //必填：是否启用https
        $domain = 'dysmsapi.aliyuncs.com';
        //step2:参数
        $paramsOne =[
            "PhoneNumbers" => $phone,   //必填: 短信接收号码
            "SignName" => $this->signName,  //必填: 短信签名
            "TemplateCode" => $this->templateCode,   //必填: 短信模板Code
            "TemplateParam" => [
                "code" => $code,
                "product" => "abc"  //这里必须是英文字母不知道为什么，还必须得写
            ],
            //'OutId' => "12345", //可选: 设置发送短信流水号
            //'SmsUpExtendCode' => "1234567", //可选: 设置发送短信流水号
        ];
        // *** 需用户填写部分结束, 以下代码若无必要无需更改 ***
        if(!empty($paramsOne["TemplateParam"]) && is_array($paramsOne["TemplateParam"])) {
            $paramsOne["TemplateParam"] = json_encode($paramsOne["TemplateParam"], JSON_UNESCAPED_UNICODE);
        }
        //step3
        $paramsTwo = [
            "RegionId" => "cn-hangzhou",
            "Action" => $action,
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
     * 说明：发送一条或者多条内容相同的短信
     * @author hongwei 2019-02-14
     * @param string $phones 手机号
     * @param string $code 验证码
     * @return bool|\stdClass
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
     * todo 目前有问题，明天再写
     * 说明：发送多条不同的短信
     * @param string $phones 手机号
     * @param string $code 验证码
     * @return bool|\stdClass
     */
    public function sendBatchSms($phones, $code)
    {
        try {
            $result = $this->request($phones, 'SendBatchSms', $code);
        } catch (\Exception $e) {
            echo 'Message is:'.$e->getMessage(), '，Code is '.$e->getCode();
            exit;
        }
        return $result;
    }
}