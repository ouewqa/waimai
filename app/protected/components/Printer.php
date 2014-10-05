<?php

/**
 * Created by PhpStorm.
 * User: Keen
 * Date: 14-8-14
 * Time: 下午4:28
 * To change this template use File | Settings | File Templates.
 */
interface Printer
{
    public function __construct($shop_id, $device_no, $device_key);

    public function send($message);

    public function checkStatus();

}