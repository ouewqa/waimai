<?php

/**
 * Created by PhpStorm.
 * User: Keen
 * Date: 14-8-14
 * Time: 下午4:28
 * To change this template use File | Settings | File Templates.
 */
class SMS
{
    public $mobile, $admin;

    public function __construct($mobile, Admin $admin)
    {
        $this->admin = $admin;
        $this->mobile = $mobile;
        $this->key = 'e941d29316af357e72aff81b8b1c84e3';
    }

    public function test($message = '短信测试')
    {
        return $this->send($message);
    }


    public function send($message, $type = 'V')
    {

        if ($this->admin->count_sms <= 0) {
            #todo 邮件通知给商家
            throw new CHttpException(500, '你的短信包已经用完，请充值！');
        }


        $message = strip_tags($message);
        $message .= '【微单】';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://sms-api.luosimao.com/v1/send.json");
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);
        curl_setopt($ch, CURLOPT_SSLVERSION, 3);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, 'api:key-' . $this->key);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array('mobile' => $this->mobile, 'message' => $message));
        $res = curl_exec($ch);
        curl_close($ch);


        $res = json_decode($res);

        if ($res->error === 0) {

            $this->admin->count_sms = $this->admin->count_sms - 1;
            $this->admin->save();

            //发送成功
            $log = new SmsLog();
            $log->setAttributes(array(
                    'admin_id' => $this->admin->id,
                    'type' => $type,
                    'mobile' => $this->mobile,
                    'content' => $message,
                    'dateline' => DATELINE,
                    'status' => 'Y',
            ));
            $log->save();

            return true;
        } else {
            throw new CHttpException(500, $res->msg);
        }
    }

    public function count()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://sms-api.luosimao.com/v1/status.json");
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);
        curl_setopt($ch, CURLOPT_SSLVERSION, 3);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, 'api:key-' . $this->key);
        $res = curl_exec($ch);
        curl_close($ch);
        //$res  = curl_error( $ch );
        $res = json_decode($res);

        if ($res->error === 0) {
            return $res->deposit;
        }
    }
}