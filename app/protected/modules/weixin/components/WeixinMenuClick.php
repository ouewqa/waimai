<?php

/**
 * 本类处理所有微信菜单中的事件处理
 * 返回类型为一个数据　array('type'=> 'text', 'data' => 'abc')
 * Created by PhpStorm.
 * User: Keen
 * Date: 14-8-12
 * Time: 下午10:04
 * To change this template use File | Settings | File Templates.
 */
class WeixinMenuClick
{
    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * 催单功能
     * 仅催最后一个未完成的订单
     */
    public function cuidan()
    {
        $scope = OutputHelper::getTodayScope();
        $order = ShopOrder::model()->find(
                'weixin_id=:weixin_id AND status in(0,1) AND (dateline BETWEEN :begin AND :end)', array(
                        ':weixin_id' => $this->user->id,
                        ':begin' => $scope['begin'],
                        ':end' => $scope['end']
                )
        );

        if ($order) {

            #通知环节 todo
            $message = array(
                    'type' => 'text',
                    'data' => "订单编号：{$order->id}\n已催促商家尽快派送。",
            );

            #保存订单状态
            $order->status = 9;
            if (!$order->save()) {
                throw new CHttpException(500, serialize($order->getErrors()));
            }
        } else {
            $message = array(
                    'type' => 'text',
                    'data' => '你今天还没有下过单！'
            );
        }

        return $message;
    }

    public function dingdan()
    {
        $orders = ShopOrder::model()->findAll(
                array(
                        'condition' => 'weixin_id=:weixin_id',
                        'params' => array(
                                ':weixin_id' => $this->user->id,
                        ),
                        'limit' => 5,
                        'with' => 'shopDishes',
                        'order' => 'dateline DESC',
                )
        );

        if ($orders) {
            $message = array(
                    'type' => 'news',
                    'data' => array(),
            );

            foreach ($orders as $key => $order) {
                $message['data'][] = array(
                        'title' => '订单号：' . $order->id . "\n状态：" . ($order->status ? OutputHelper::getShopOrderStatusList($order->status) : '未受理') . '　' . OutputHelper::timeFormat($order->dateline),
                        'description' => '',
                        'picurl' => $key ? ImageHelper::thumb($order->image, 80, 80) : ImageHelper::setFullPath($order->image),
                        'url' => Yii::app()->createAbsoluteUrl('/weixin/profile/orderView', array(
                                        'id' => $order->id,
                                ))
                );
            }


        } else {
            $message = array(
                    'type' => 'text',
                    'data' => '您的订单记录为空！'
            );
        }


        return $message;
    }

} 