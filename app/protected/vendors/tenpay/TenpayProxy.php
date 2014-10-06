<?php

class TenpayProxy extends CComponent
{

    public
            $key,
            $partner,
            $return_url,
            $notify_url,
            $seller_id;

    public function init()
    {
        Yii::import('application.vendors.tenpay.class.*');

        $this->return_url = Yii::app()->request->hostInfo . $this->return_url;
        $this->notify_url = Yii::app()->request->hostInfo . $this->notify_url;
    }

    /**
     * @param $out_trade_no 订单号
     * @param $total_fee    总金额　100.01
     * @param $title        商品名称
     * @param $description  商品描述
     * @return string
     */
    public function getRequestURL($out_trade_no, $total_fee, $title, $description)
    {
        $trade_mode = 1;

        #金额单位转换，换成分
        $total_fee = $total_fee * 100;

        /* 创建支付请求对象 */
        $reqHandler = new RequestHandler();
        $reqHandler->init();
        $reqHandler->setKey($this->key);
        $reqHandler->setGateUrl("https://gw.tenpay.com/gateway/pay.htm");

        //----------------------------------------
        //设置支付参数
        //----------------------------------------
        $reqHandler->setParameter("partner", $this->partner);
        $reqHandler->setParameter("out_trade_no", $out_trade_no);
        $reqHandler->setParameter("total_fee", $total_fee); //总金额
        $reqHandler->setParameter("return_url", $this->return_url); //交易完成后跳转的URL
        $reqHandler->setParameter("notify_url", $this->notify_url); //接收财付通通知的URL
        $reqHandler->setParameter("body", $description); //商品描述
        $reqHandler->setParameter("bank_type", "DEFAULT"); //银行类型，默认为财付通
        //用户ip
        $reqHandler->setParameter("spbill_create_ip", $_SERVER['REMOTE_ADDR']); //客户端IP
        $reqHandler->setParameter("fee_type", "1"); //币种
        $reqHandler->setParameter("subject", $title); //商品名称，（中介交易时必填）

        //系统可选参数
        $reqHandler->setParameter("sign_type", "MD5"); //签名方式，默认为MD5，可选RSA
        $reqHandler->setParameter("service_version", "1.0"); //接口版本号
        $reqHandler->setParameter("input_charset", "utf-8"); //字符集
        $reqHandler->setParameter("sign_key_index", "1"); //密钥序号

        //业务可选参数
        $reqHandler->setParameter("attach", ""); //附件数据，原样返回就可以了
        $reqHandler->setParameter("product_fee", ""); //商品费用
        $reqHandler->setParameter("transport_fee", "0"); //物流费用
        $reqHandler->setParameter("time_start", date("YmdHis")); //订单生成时间
        $reqHandler->setParameter("time_expire", ""); //订单失效时间
        $reqHandler->setParameter("buyer_id", ""); //买方财付通帐号
        $reqHandler->setParameter("goods_tag", ""); //商品标记
        $reqHandler->setParameter("trade_mode", $trade_mode); //交易模式（1.即时到帐模式，2.中介担保模式，3.后台选择（卖家进入支付中心列表选择））
        $reqHandler->setParameter("transport_desc", ""); //物流说明
        $reqHandler->setParameter("trans_type", "1"); //交易类型
        $reqHandler->setParameter("agentid", ""); //平台ID
        $reqHandler->setParameter("agent_type", ""); //代理模式（0.无代理，1.表示卡易售模式，2.表示网店模式）
        $reqHandler->setParameter("seller_id", ""); //卖家的商户号


        //请求的URL
        return $reqHandler->getRequestURL();
    }

    /**
     * 补单通知接口，返回真假
     * @return bool
     */
    public function notifyInterface()
    {
        $result = false;

        /* 创建支付应答对象 */
        $resHandler = new ResponseHandler();
        $resHandler->setKey($this->key);

        //判断签名
        if ($resHandler->isTenpaySign()) {

            //通知id
            $notify_id = $resHandler->getParameter("notify_id");
            //商户订单号
            $out_trade_no = $resHandler->getParameter("out_trade_no");
            //财付通订单号
            $transaction_id = $resHandler->getParameter("transaction_id");
            //金额,以分为单位
            $total_fee = $resHandler->getParameter("total_fee");
            //如果有使用折扣券，discount有值，total_fee+discount=原请求的total_fee
            $discount = $resHandler->getParameter("discount");
            //支付结果
            $trade_state = $resHandler->getParameter("trade_state");
            //交易模式,1即时到账
            $trade_mode = $resHandler->getParameter("trade_mode");


            if ("1" == $trade_mode) {

                $order = SiteProductOrder::model()->findByOrderSn($out_trade_no);

                #0为成功
                if ("0" == $trade_state) {
                    if ($order && $order->status != 'Y') {
                        $order->transaction_id = $transaction_id;
                        $order->status = 'Y';
                        if ($order->save()) {
                            $result = true;
                        } else {
                            $result = false;
                        }
                    }

                } else {
                    //不成功处理
                    if ($order && $order->status != 'Y') {
                        $order->transaction_id = $transaction_id;
                        $order->status = 'F';
                        if ($order->save()) {
                            $result = true;
                        } else {
                            $result = false;
                        }
                    }

                }
            } elseif ("2" == $trade_mode) {
                if ("0" == $trade_state) {
                    #echo "<br/>" . "中介担保支付成功" . "<br/>";
                } else {
                    //当做不成功处理
                    # echo "<br/>" . "中介担保支付失败" . "<br/>";
                }
            }

        } else {
            /*echo "<br/>" . "认证签名失败" . "<br/>";
            echo $resHandler->getDebugInfo() . "<br>";*/
        }

        return $result;
    }


    /**
     *
     * @return bool
     */
    public function returnInterface()
    {
        $result = false;


        /* 创建支付应答对象 */
        $resHandler = new ResponseHandler();
        $resHandler->setKey($this->key);

        //判断签名
        if ($resHandler->isTenpaySign()) {

            //通知id
            $notify_id = $resHandler->getParameter("notify_id");
            //商户订单号
            $out_trade_no = $resHandler->getParameter("out_trade_no");
            //财付通订单号
            $transaction_id = $resHandler->getParameter("transaction_id");
            //金额,以分为单位
            $total_fee = $resHandler->getParameter("total_fee");
            //如果有使用折扣券，discount有值，total_fee+discount=原请求的total_fee
            $discount = $resHandler->getParameter("discount");
            //支付结果
            $trade_state = $resHandler->getParameter("trade_state");
            //交易模式,1即时到账
            $trade_mode = $resHandler->getParameter("trade_mode");

            $order = SiteProductOrder::model()->findByOrderSn($out_trade_no);

            if ("1" == $trade_mode) {
                if ("0" == $trade_state) {

                    if ($order && $order->status != 'Y') {
                        $order->transaction_id = $transaction_id;
                        $order->status = 'Y';
                        if ($order->save()) {
                            $result = $order->id;
                        } else {
                            $result = false;
                        }
                    }

                } else {
                    if ($order && $order->status != 'Y') {
                        $order->status = 'F';
                        $order->transaction_id = $transaction_id;
                        if ($order->save()) {
                            $result = $order->id;;
                        } else {
                            $result = false;
                        }
                    }
                }
            } elseif ("2" == $trade_mode) {
                if ("0" == $trade_state) {
                } else {
                }
            }

        } else {
            /* echo "<br/>" . "认证签名失败" . "<br/>";
             echo $resHandler->getDebugInfo() . "<br>";*/
        }

        return $result;
    }

}