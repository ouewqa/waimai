<?php

class AlipayHelper
{
    public static function getRequestURL($out_trade_no, $total_fee, $title, $description, $key = null, $partner = null, $seller_email = null)
    {
        #AlipayProxy　类
        $alipay = Yii::app()->alipay;

        // If starting a guaranteed payment, use AlipayGuaranteeRequest instead
        $request = new AlipayDirectRequest();
        $request->out_trade_no = $out_trade_no; #订单号
        $request->subject = $title; #订单名称
        $request->body = $description; #订单描述
        $request->total_fee = $total_fee; #付款金额

        #改变alipay账户
        if ($key && $partner && $seller_email) {
            $alipay->changeAccount($key, $partner, $seller_email);
        }

        return $alipay->buildForm($request);
    }

    public static function notifyInterface()
    {
        $alipay = Yii::app()->alipay;
        return $alipay->verifyNotify();
    }

    public static function returnInterface()
    {
        $alipay = Yii::app()->alipay;
        return $alipay->verifyReturn();
    }
}