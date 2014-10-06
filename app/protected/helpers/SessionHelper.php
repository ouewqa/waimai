<?php

/*

Yii::app()->session->clear() 移去所有session变量，然后，调用
Yii::app()->session->destroy() 移去存储在服务器端的数据。

'session'=>array(
        'autoStart'=>false(/true),
   'sessionName'=>'Site Access',
   'cookieMode'=>'only',
   'savePath'='/path/to/new/directory',
),*/

class SessionHelper
{
    public static function set($key, $value)
    {
        Yii::app()->session[$key] = $value;
    }

    public static function get($key)
    {
        return isset(Yii::app()->session[$key]) ? Yii::app()->session[$key] : null;
    }

    public static function delete($key)
    {
        unset(Yii::app()->session[$key]);
    }

    public static function clear()
    {
        Yii::app()->session->clear();
    }

    public static function destroy()
    {
        Yii::app()->session->destroy();
    }
}