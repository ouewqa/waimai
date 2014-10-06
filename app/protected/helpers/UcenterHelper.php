<?php
include_once 'ucenter_client.php';

class UcenterHelper
{
    /**
     * 站内短信发送
     * @param $uid
     * @param $touid
     * @param $subject
     * @param $message
     * @return mixed
     */
    public static function pm($uid, $touid, $subject, $message)
    {
        $result = uc_pm_send($uid, $touid, $subject, $message, true);

        $array = array(
                '0' => '发送失败',
                '-1' => '超过两人会话的最大上限',
                '-2' => '超过两次发送短消息时间间隔',
                '-3' => '不能给非好友批量发送短消息(已废弃)',
                '-4' => '目前还不能使用发送短消息功能（注册多少日后才可以使用发短消息限制）',
                '-5' => '超过群聊会话的最大上限',
                '-6' => '在忽略列表中',
                '-7' => '超过群聊人数上限',
                '-8' => '不能给自己发短消息',
                '-9' => '收件人为空',
                '-10' => '发起群聊人数小于两人',
        );

        return $result > 0 ? $result : $array[$result];
    }

    /**
     * 站长通知
     * @param $touid
     * @param $subject
     * @param $message
     * @return mixed
     */
    public static function notice($touid, $subject, $message)
    {
        return self::pm(1, $touid, $subject, $message);
    }


    public static function uc_get_user($username, $isuid = 0)
    {
        return uc_get_user($username, $isuid);
    }

    /**
     * 修改用户
     * @param $username
     * @param $oldpw
     * @param $newpw
     * @param $email
     * @param int $ignoreoldpw
     * @param string $questionid
     * @param string $answer
     * @return mixed
     */
    public static function uc_user_edit($username, $oldpw, $newpw, $email, $ignoreoldpw = 0, $questionid = '', $answer = '')
    {
        return uc_user_edit($username, $oldpw, $newpw, $email, $ignoreoldpw, $questionid, $answer);
    }

    /**
     * 用户注册
     * @param $username
     * @param $password
     * @param $email
     * @param string $questionid
     * @param string $answer
     * @param string $regip
     * @return mixed
     */
    public static function uc_user_register($username, $password, $email, $questionid = '', $answer = '', $regip = '')
    {
        return uc_user_register($username, $password, $email, $questionid, $answer, $regip);
    }

    /**
     * 同步登出
     * @return string
     */
    public static function uc_user_synlogout()
    {
        return uc_user_synlogout();
    }

    /**
     * 同步登陆
     * @param $uid
     * @return string
     */
    public static function  uc_user_synlogin($uid)
    {
        return uc_user_synlogin($uid);
    }

    public static function uc_user_checkname($username)
    {
        return uc_user_checkname($username);
    }

    public static function uc_user_checkemail($email)
    {
        return uc_user_checkemail($email);
    }

    public static function uc_user_login($username, $password, $isuid = 0, $checkques = 0, $questionid = '', $answer = '')
    {
        return uc_user_login($username, $password, $isuid, $checkques, $questionid, $answer);
    }
}