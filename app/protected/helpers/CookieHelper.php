<?php

class CookieHelper
{
    public static function has($key)
    {
        return !empty(Yii::app()->request->cookies['WX_' . $key]);
    }

    public static function set($key, $value = 1, $dateline = null)
    {
        $domain = parse_url(Yii::app()->request->hostInfo);

        $expire = $dateline ? $dateline : (time() + 86400 * 30);
        $domain = $domain['host'];

        $cookie = new CHttpCookie('WX_' . $key, $value, array(
                'expire' => $expire,
                'domain' => '',
        ));


        Yii::app()->request->cookies['WX_' . $key] = $cookie;
    }

    /**
     * 获取cookie　如果$key为空，则获取所有
     * @param null $key
     * @param null $default
     * @return CCookieCollection|CHttpCookie[]|null|string
     */
    public static function get($key = null, $default = null)
    {
        if ($key) {
            $result = isset(Yii::app()->request->cookies['WX_' . $key]) ? Yii::app()->request->cookies['WX_' . $key]->value : $default;
        } else {
            $result = Yii::app()->request->cookies;
        }
        return $result;
    }


    public static function del($key, $useExt = true)
    {
        if ($useExt) {
            $key = 'WX_' . $key;
        }
        unset(Yii::app()->request->cookies[$key]);
    }

    /**
     * 清空缓存
     * @param string $find 正则限制，包含 $find 的才会被清空
     */
    public static function clear($find = '')
    {
        $cookies = self::get();
        foreach ($cookies as $key => $value) {
            if ($find) {
                if (strpos($value->name, $find) === false) {
                    continue;
                }
            }
            //echo $value->name, '<br />';
            self::del($value->name, false);
        }
    }
}