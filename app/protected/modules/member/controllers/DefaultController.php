<?php

class DefaultController extends BackendController
{


    public function actionIndex($type = 'notification')
    {

        #请求数
        $accounts = WeixinAccount::model()->findAll('admin_id=:admin_id', array(
                ':admin_id' => Yii::app()->user->id,
        ));

        $count_request = 0;
        foreach ($accounts as $key => $value) {
            $count_request += $value->count_request;
        }

        #通知
        $notifications = new CActiveDataProvider('SiteNotification', array(
                'criteria' => array(
                        'condition' => 'expire IS NULL OR expire>:expire',
                        'params' => array(
                                ':expire' => time(),
                        )
                ),
                'sort' => array(
                        'defaultOrder' => 't.id DESC', //默认排序
                ),
                'pagination' => array(
                        'pageSize' => 10,
                )
        ));

        #公告

        $help = new CActiveDataProvider('SiteHelp', array(
                'criteria' => array(
                        'condition' => 'status=:status',
                        'params' => array(
                                ':status' => 'Y',
                        )
                ),
                'sort' => array(
                        'defaultOrder' => 't.id ASC', //默认排序
                ),
                'pagination' => array(
                        'pageSize' => 10,
                )
        ));

        $this->setPageTitle('会员中心');

        $this->render('index', array(
                'type' => $type,
                'count_request' => $count_request,
                'notifications' => $notifications,
                'help' => $help,
        ));
    }

    /**
     * 控制面板，统计数据
     */
    public function actionDashboard()
    {


        #请求数
        $accounts = WeixinAccount::model()->findAll('admin_id=:admin_id', array(
                ':admin_id' => Yii::app()->user->id,
        ));

        $count_request = 0;
        foreach ($accounts as $key => $value) {
            $count_request += $value->count_request;
        }


        $monthScope = OutputHelper::getThisMonthScope();


        $criteria = new CDbCriteria();
        $criteria->select = 'COUNT(id) AS id, SUM(money) AS money, FROM_UNIXTIME(dateline, "%Y-%m-%d") AS dateline';
        $criteria->group = 'FROM_UNIXTIME(`dateline`, "%Y-%m-%d")';
        $criteria->order = 'dateline ASC';
        $criteria->condition = 'shop_id=:shop_id AND status>=:status';
        $criteria->params = array(
                ':status' => 20,
                ':shop_id' => $this->shop->id,
        );


        $criteria->addBetweenCondition('dateline', $monthScope['begin'], $monthScope['end']);

        $shopOrder = ShopOrder::model()->findAll($criteria);

        #订单数据
        $orderData = array(
                'day' => array(),
                'order' => array(),
                'money' => array(),
        );


        foreach ($shopOrder as $key => $value) {
            $orderData['day'][] = $value->dateline;
            $orderData['order'][] = intval($value->id);
            $orderData['money'][] = floatval($value->money);
        }


        #关注数据
        $criteria = new CDbCriteria();
        $criteria->select = 'COUNT(id), FROM_UNIXTIME(dateline, "%Y-%m-%d") AS dateline';
        $criteria->group = 'FROM_UNIXTIME(`dateline`, "%Y-%m-%d")';
        $criteria->order = 'dateline ASC';
        $criteria->condition = 'weixin_account_id=:weixin_account_id';
        $criteria->params = array(
                ':weixin_account_id' => $this->account->id,
        );


        $criteria->addBetweenCondition('dateline', $monthScope['begin'], $monthScope['end']);

        $weixin = Weixin::model()->findAll($criteria);

        $subscribeData = array(
                'day' => array(),
                'people' => array(),
        );


        foreach ($weixin as $key => $value) {
            $subscribeData['day'][] = $value->dateline;
            $subscribeData['people'][] = intval($value->id);
        }


        $this->setPageTitle('控制面板');

        $this->render('dashboard', array(
                'count_request' => $count_request,
                'orderData' => $orderData,
                'subscribeData' => $subscribeData,
        ));
    }



    public function actionSetting()
    {
        $model = Admin::model()->findByPk(Yii::app()->user->id);

        if (isset($_POST['Admin'])) {

            $oldModel = Admin::model()->findByPk(Yii::app()->user->id);

            $model->attributes = $_POST['Admin'];

            if ($model->email != $oldModel->email) {
                $model->email_is_verify = 'N';
                $model->sendEmailVerify($model->email);
            }

            if ($model->mobile != $oldModel->mobile) {
                $model->mobile_is_verify = 'N';
            }

            if ($model->save()) {


            }


        }

        $this->setPageTitle('基本信息设置');

        $this->render('setting', array(
                'model' => $model
        ));
    }

    public function actionVerify($type = 'email')
    {
        $model = $this->user;

        if (!$model->email) {
            $this->error('你需要先设置你的邮箱地址', array('/member/default/setting'));
        }

        if (!$model->mobile) {
            $this->error('你需要先设置你的手机号码', array('/member/default/setting'));
        }

        #验证验证码
        if (Yii::app()->request->isAjaxRequest && isset($_POST['code'])) {

            $code = VerificationCode::model()->checkCode(
                    $type,
                    trim($_POST['code'])
            );

            if ($code) {
                $model->{$type . '_is_verify'} = 'Y';
                if (!$model->save()) {
                    $result = array(
                            'status' => false,
                            'msg' => '验证失败。',
                            'data' => serialize($model->getErrors()),
                    );
                } else {
                    $result = array(
                            'status' => true,
                            'msg' => '验证成功。'
                    );
                    $code->status = 'Y';
                    $code->save();
                }
            } else {
                $result = array(
                        'status' => false,
                        'msg' => '验证失败，验证码错误或已过期，请重新获取。'
                );
            }
            $this->jsonout($result);
        }


        if ($type == 'mobile') {
            $this->setPageTitle('手机号码验证');
        } else if ($type == 'email') {
            $this->setPageTitle('电子邮箱验证');
        } else {

        }


        $this->render('verify', array(
                'model' => $model,
                'type' => $type
        ));
    }

    /**
     * 发送验证码
     * @param string $type 　发送类型
     * @param $target 发送到哪里
     */
    public function actionSendVerifyCode($type = 'email', $target = null)
    {


        if (Yii::app()->request->isAjaxRequest) {

            $model = Admin::model()->findByPk(Yii::app()->user->id);


            switch ($type) {
                case 'email':
                    $result = $model->sendEmailVerify();
                    break;

                case 'mobile':
                    $result = $model->sendMobileVerify();
                    break;
            }

            if ($result) {
                $result = array(
                        'status' => true,
                        'msg' => '验证码发送成功，请查收！',
                );
            } else {
                $result = array(
                        'status' => false,
                        'msg' => '验证码发送失败，请重试！',
                );
            }

            $this->jsonout($result);
        }
    }

    public function actionChangePassword()
    {

        $model = new ChangePassword();

        if (isset($_POST['ChangePassword'])) {
            $model->attributes = $_POST['ChangePassword'];

            if ($model->validate() && $model->authenticate()) {
                Yii::app()->user->setFlash('changePaswordStatus', '密码修改成功！');
            }


        }

        $this->setPageTitle('重置密码');

        $this->render('changePassword',
                array(
                        'model' => $model
                )
        );
    }


}