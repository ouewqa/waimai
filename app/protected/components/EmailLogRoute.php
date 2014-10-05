<?php

/**
 * Created by PhpStorm.
 * User: Keen
 * Date: 14-4-29
 * Time: 下午3:16
 * To change this template use File | Settings | File Templates.
 */
class EmailLogRoute extends CEmailLogRoute
{
    public $subject, $receiver, $message;

    protected function sendEmail($email, $subject, $message)
    {
        if (!YII_DEBUG) {
            EmailHelper::send(array(
                    'email' => $email,
                    'subject' => $this->subject,
                    'data' => array(
                            'title' => $this->subject,
                            'content' => '<pre>'.($message) .'</pre>',
                    ),
            ));
        }

    }
}