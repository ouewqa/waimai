<?php

/**
 * Created by PhpStorm.
 * User: Keen
 * Date: 14-4-29
 * Time: 下午4:22
 * To change this template use File | Settings | File Templates.
 */
class EmailHelper
{

    public static function send($config)
    {
        $config = CMap::mergeArray(array(
                'email',
                'subject',
                'data' => array(
                        'title' => '',
                        'content' => '',
                ),
                'layout' => 'mail',
                'view' => 'email',
                'debug' => YII_DEBUG,
        ), $config);

        $email = 'mail' . ((time() % 10) + 1) . '@bo-u.cn';
        //$email = 'admin@ribenyu.cn';

        $mail = new YiiMailer;

        $mail->IsSMTP();
        $mail->SMTPDebug = $config['debug'];
        $mail->Host = "smtp.exmail.qq.com";
        $mail->Port = 25;
        $mail->SMTPAuth = true;
        $mail->Username = $email;
        $mail->Password = "sendmail123456";
        $mail->setFrom($email);


        $mail->setSubject($config['subject']);
        $mail->setTo($config['email']);

        $mail->setLayout($config['layout']);
        $mail->setView($config['view']);
        $mail->setData($config['data']);

        if ($mail->send()) {
            $result = true;
        } else {
            $result = false;
        }

        return $result;
    }
} 