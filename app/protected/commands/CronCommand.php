<?php

/**
 * 每月一号执行一次
 * Created by PhpStorm.
 * User: Keen
 * Date: 14-3-5
 * Time: 下午5:36
 * To change this template use File | Settings | File Templates.
 * windows useage yiic.bat Email emailVerify
 * Linux useage yiic Email emailVerify
 */
class CronCommand extends CConsoleCommand
{

    public function actionTest()
    {
        echo 'TEST ABC';
    }


    /**
     * 每月底清空请求数
     */
    public function actionCleanRequestCount()
    {
        #请求数清空
        WeixinAccount::model()->updateAll(
                array(
                        'count_request' => 0
                )

        );
    }

    /**
     * 重新推送
     * 推送频率，每10分钟一次
     */
    public function actionPrinterRePush()
    {
        $model = PrintLog::model()->findAll(array(
                'with' => 'shop',
                'condition' => 'status=:status AND times<:time',
                'params' => array(
                        ':status' => 'N',
                        ':times' => '3',
                ),
                'order' => 'repush_time ASC',
                'limit' => 30,
        ));

        foreach ($model as $key => $log) {

            $printer = new PushHelper($log->shop);
            $result = $printer->send($log->content);

            if ($result) {
                $log->status = 'Y';
            } else {
                $log->times += 1;
            }
            $log->save();
        }
    }


    /**
     * 设备检测
     * 检测频率，每小时一次
     */
    public function actionDeviceCheck()
    {
        $model = Shop::model()->findAll(array(
                'condition' => 'status=:status AND push_method=:push_method AND last_device_check_time>:last_device_check_time AND push_device IS NOT NULL AND push_device_no IS NOT NULL AND push_device_key IS NOT NULL',
                'params' => array(
                        ':status' => 'Y',
                        ':push_method' => 'printer',
                        ':last_device_check_time' => time() - 3600,
                ),
                'limit' => 10,
                'order' => 'last_device_check_time ASC',
        ));

        foreach ($model as $key => $shop) {
            $printer = new PushHelper($shop);
            $result = $printer->checkStatus();

            if (!$result) {
                $account = Admin::model()->findByPk($shop->weixinAccount->admin_id);
                $sms = new SMS($shop->mobile, $account);
                if ($sms) {
                    $sms->send('your printer is off line now, please check it!');
                }

            }

            $shop->last_device_check_time = time();
            $shop->save();
        }
    }

    /**
     * 会员状态检测
     * 检测频率，每天0:00检测一次
     */
    public function actionMemberStatusCehck()
    {
        $model = Admin::model()->findAll(array(
                'condition' => 'expire<:expire AND status:status',
                'params' => array(
                        ':expire' => time(),
                        ':status' => 'Y',
                )
        ));

        foreach ($model as $key => $member) {
            WeixinAccount::model()->updateAll(
                    array(
                            'status' => 'N',
                    ),
                    'weixin_account_id=:weixin_account_id',
                    array(
                            ':weixi_account_id' => $member->id
                    )

            );
        }
    }

    /**
     * 账号即将过期通知
     * 通知频率：每天0:00检测一次
     */
    public function actionMemberExpireNotification()
    {
        $model = Admin::model()->findAll(array(
                'condition' => 'expire BETWEEN :begin AND :end AND status:status',
                'params' => array(
                        ':begin' => time() - 86400 * 7,
                        ':end' => time() - 86400 * 6,
                        ':status' => 'Y',
                ),
                'limit' => 10,
                'order' => 'expire ASC'
        ));

        foreach ($model as $key => $account) {
            #todo maybe use sms
            $sms = new SMS($account->mobile, $account);
            if ($sms) {
                $sms->send('your account will expire in next week');
            }

        }
    }


}