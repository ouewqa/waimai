<?php

/**
 * Created by PhpStorm.
 * User: Keen
 * Date: 14-3-24
 * Time: 上午11:04
 * To change this template use File | Settings | File Templates.
 */
class NotificationHelper
{
    public static function send($from_weixin_id, $to_weixin_id, $type, $message)
    {
        /*$notification = WeixinNotification::model()->find(
                'to_weixin_id=:to_weixin_id AND from_weixin_id=:from_weixin_id AND type=:type AND message=:message', array(
                    ':to_weixin_id' => $to_weixin_id,
                    ':from_weixin_id' => $from_weixin_id,
                    ':type' => $type,
                    ':message' => $message,
                )
        );

        if(!$notification){

        }*/

        $notification = new WeixinNotification();
        $notification->attributes = array(
                'to_weixin_id' => $to_weixin_id,
                'from_weixin_id' => $from_weixin_id,
                'type' => $type,
                'message' => $message,
                'dateline' => time(),
        );

        if ($notification->save()) {
            $result = true;
        } else {
            $result = false;
        }
    }
} 