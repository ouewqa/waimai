<?php

/**
 * Created by JetBrains PhpStorm.
 * User: Keen
 * Date: 13-8-23
 * Time: 下午4:24
 * To change this template use File | Settings | File Templates.
 */
class webController extends CoreController
{
    public function init()
    {
        parent::init();
        Yii::app()->theme = 'web';
    }

    public function isRobot()
    {
        $ua = strtolower($_SERVER['HTTP_USER_AGENT']);
        $botchar = "/(bot|spider)/i";
        if (preg_match($botchar, $ua)) {
            return true;
        } else {
            return false;
        }
    }
}