<?php

class PushHelper
{
    public
            $shop, #店铺实例
            $pusher, # 推送器实例
            $buildDataProcess; # 格式化类型


    public function __construct(Shop $shop)
    {

        #判断是否继续执行

        $this->shop = $shop;


        if ($this->shop->push_method == 'sms') {

            $account = Admin::model()->findByPk($shop->weixinAccount->admin_id);
            $this->pusher = new SMS($this->shop->mobile, $account);

            if (!$this->shop->mobile || !$this->pusher) {
                throw new CHttpException(500, '手机号码为空');
            }


        } else if ($this->shop->push_method == 'printer') {

            if (!$this->shop->push_device || !$this->shop->push_device_no || !$this->shop->push_device_key) {
                throw new CHttpException(500, '设备信息填写不全！');
            }

            switch ($this->shop->push_device) {
                case 'feie':
                    $this->pusher = new PrinterFeiE($this->shop->id, $this->shop->push_device_no, $this->shop->push_device_key);
                    break;

                default:
                    throw new CHttpException(500, '暂时不支持该设备！');
            }
        } else {
            throw new CHttpException(500, '未指定推送类型');
        }

        $this->buildDataProcess = 'buildData' . ucfirst($this->shop->push_method);
    }

    public function send($data)
    {
        if ($this->shop->push_method == 'sms') {
            return $this->pusher->send($data);
        } else {
            return $this->pusher->send($data, $this->shop->number_of_copies);
        }
    }

    public function checkStatus()
    {
        return $this->pusher->checkStatus();
    }

    public function diy($data)
    {
        return $this->pusher->diy($data);
    }

    public function test($message)
    {
        return $this->pusher->test($message);
    }


    /**
     * 构建打印数据消息
     * @param ShopOrder $shopOrder
     * @throws CHttpException
     * @return array
     */
    public function buildData(ShopOrder $shopOrder)
    {

        if (!$this->shop || !$shopOrder) {
            throw new CHttpException(400, '不存在该订单');
        }

        $address = UsedAddresses::model()->findByPk($shopOrder->used_addresses_id);
        $orderItems = ShopOrderItem::model()->with('shopDish')->findAll('shop_order_id=:shop_order_id', array(
                ':shop_order_id' => $shopOrder->id,
        ));

        try {
            $result = $this->{$this->buildDataProcess}($shopOrder, $address, $orderItems);
        } catch (Exception $e) {
            throw new CHttpException(500, $e->getMessage());
        }

        return $result;
    }


    /**
     * 短信内容
     * @param $shopOrder
     * @param $address
     * @param $orderItems
     * @return string
     */
    public function buildDataSms($shopOrder, $address, $orderItems)
    {
        $data = sprintf('单号：%d
时间：%s
地址：%s
派送：%s
%s
菜品：',
                $shopOrder->id,
                date('m-d H:i'),
                $address->address . $address->realname,
                $shopOrder->delivery_time,
                $shopOrder->comment ? '留言：' . $shopOrder->comment : ''
        );

        $items = array();
        $temp_money = 0;
        foreach ($orderItems as $item) {

            $items[] = sprintf('%s(%s)[%d]',
                    $item->shopDish->name,
                    $item->shopDish->price,
                    $item->number
            );

            $temp_money += intval($item->shopDish->price) * $item->number;
        }


        if ($temp_money < $this->shop->minimum_charge) {

            $items[] = sprintf(PHP_EOL . '%s(%s)',
                    '送餐费',
                    $this->shop->express_fee
            );
        }

        $data .= implode(PHP_EOL, $items);

        $data .= PHP_EOL . '总金额：' . $shopOrder->money . '元';

        return $data;
    }

    public function buildDataPrinter($shopOrder, $address, $orderItems)
    {
        $data = array(
                'name' => $this->shop->name,
                'comment' => $shopOrder->comment,
                'address' => $address->district . $address->address . $address->realname,
                'mobile' => $address->mobile,
                'dateline' => date('Y-m-d H:i:s'),
                'money' => $shopOrder->money . ' 元' .
                        "<BR>--------------------------------<BR>订单号：" . $shopOrder->id .
                        "<BR>用户ID：" . $shopOrder->weixin_id .
                        "<BR>送餐时间：" .
                        $shopOrder->delivery_time .
                        "<BR>付款方式：" . $shopOrder->paymentMethod->name,
                'qrcode' => '',
        );

        $data['dateline'] .= '<BR>--------------------------------<BR>本店地址：' .
                $this->shop->address .
                '<BR>本店电话：' . $this->shop->telephone .
                '<BR>--------------------------------';

        if ($shopOrder->scene_id) {
            $qrCode = WeixinQrcode::model()->findBySceneId($shopOrder->scene_id);
            if ($qrCode) {
                $data['dateline'] .= '<BR>扫描以下二维码，即可确认收货。';
                $data['qrcode'] = $qrCode->path;
            }
        }


        $temp_money = 0;
        foreach ($orderItems as $item) {

            $data['items'][] = array(
                    'name' => $item->shopDish->name,
                    'price' => $item->shopDish->price,
                    'number' => $item->number,
                    'money' => intval($item->shopDish->price) * $item->number,
            );

            $temp_money += intval($item->shopDish->price) * $item->number;
        }


        if ($temp_money < $this->shop->minimum_charge) {
            $data['items'][] = array(
                    'name' => '送餐费',
                    'price' => $this->shop->express_fee,
                    'number' => 1,
                    'money' => $this->shop->express_fee,
            );
        }
        return $data;
    }

}