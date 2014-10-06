<?php

class TenpayHelper
{
    public static function getRequestURL($out_trade_no, $total_fee, $title, $description)
    {
        #TenpayProxy　类
        $tenpay = Yii::app()->tenpay;
        return $tenpay->getRequestURL($out_trade_no, $total_fee, $title, $description);
    }

    public static function notifyInterface()
    {
        $tenpay = Yii::app()->tenpay;
        return $tenpay->notifyInterface();
    }

    public static function returnInterface()
    {
        $tenpay = Yii::app()->tenpay;
        return $tenpay->returnInterface();
    }
}