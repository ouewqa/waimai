<?php

class ProfileController extends FrontController
{
    public function actionIndex()
    {

        $criteria = new CDbCriteria();
        $criteria->order = 'reply_time DESC, dateline DESC';
        $criteria->condition = 't.weixin_id=:weixin_id';
        $criteria->params = array(
                ':weixin_id' => $this->weixin->id,
        );
        $count_feedback = Feedback::model()->count($criteria);


        $criteria = new CDbCriteria();
        $criteria->with = 'shopDishes';
        $criteria->order = 't.dateline DESC';
        $criteria->condition = 't.weixin_id=:weixin_id AND t.dateline>:dateline';
        $criteria->params = array(
                ':weixin_id' => $this->weixin->id,
                ':dateline' => DATELINE - 86400 * 30, #30天前的数据
        );
        $count_order = ShopOrder::model()->count($criteria);


        $this->setPageTitle('会员中心');

        $this->render('index', array(
                'count_feedback' => $count_feedback,
                'count_order' => $count_order,
        ));

    }

    public function actionView()
    {
        $shop = Shop::model()->findByPk($this->shop->id);
        $this->render('view', array(
                'model' => $shop,
        ));
    }

    public function actionOrderList()
    {

        $criteria = new CDbCriteria();
        $criteria->with = 'shopDishes';
        $criteria->order = 't.dateline DESC';
        $criteria->condition = 't.weixin_id=:weixin_id AND t.dateline>:dateline';
        $criteria->params = array(
                ':weixin_id' => $this->weixin->id,
                ':dateline' => DATELINE - 86400 * 30, #30天前的数据
        );

        $count = ShopOrder::model()->count($criteria);

        $pages = new CPagination($count);

        $pages->pageSize = 10;
        $pages->applyLimit($criteria);
        $orders = ShopOrder::model()->findAll($criteria);

        $this->setPageTitle('历史订单');

        $this->render('orderList', array(
                'orders' => $orders,
                'pages' => $pages,
        ));
    }

    public function actionOrderView($id)
    {
        $model = ShopOrder::model()->cache(0)->with(array('items'))->findByPk($id);

        if (!$model) {
            throw new CHttpException(404, '不存在这个订单');
        }

        $this->setPageTitle('订单详情');
        $this->render('orderView', array(
                'model' => $model
        ));
    }

    public function actionAddAddress($id = null)
    {
        if ($id) {
            $model = UsedAddresses::model()->findByPk($id);
        } else {
            $model = new UsedAddresses();
            $model->unsetAttributes();
            $model->weixin_id = $this->weixin->id;
            $model->dateline = DATELINE;
        }


        if (isset($_POST['UsedAddresses'])) {
            $model->attributes = $_POST['UsedAddresses'];

            if ($this->account->need_mobile_verify == 'Y') {
                $model->status = 'N';
            } else {
                $model->status = 'Y';
            }

            if ($model->save()) {

                if ($model->realname && !$this->weixin->realname) {
                    $this->weixin->realname = $model->realname;
                }

                if ($model->province && !$this->weixin->province) {
                    $this->weixin->province = $model->province;
                }

                if ($model->city && !$this->weixin->city) {
                    $this->weixin->city = $model->city;
                }

                if ($model->district && !$this->weixin->district) {
                    $this->weixin->district = $model->district;
                }

                if ($model->address && !$this->weixin->address) {
                    $this->weixin->address = $model->address;
                }

                $this->weixin->save();

                #如果需要验证手机号码，则跳转到验证流程
                if ($this->account->need_mobile_verify == 'Y') {
                    $this->redirect(array('mobileVerify', 'mobile' => $model->mobile, 'addresses_id' => $model->id));
                } else {
                    $this->redirect(array('product/order'));
                }


            }
        }


        $this->setPageTitle('添加常用地址');

        $this->render('addAddress', array(
                'model' => $model,
        ));
    }

    /**
     * 设置状态
     */
    public function actionSetStatus()
    {
        if (Yii::app()->request->isAjaxRequest) {

            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
            $status = isset($_POST['status']) ? intval($_POST['status']) : 0;


            $model = ShopOrder::model()->findByPk($id, 'weixin_id=:weixin_id', array(
                    ':weixin_id' => $this->weixin->id
            ));

            if (!empty($model)) {
                $model->status = $status;
                if ($model->save()) {
                    $result = array(
                            'status' => true,
                            'msg' => '设置成功',
                    );
                } else {
                    $result = array(
                            'status' => false,
                            'msg' => '设置失败',
                            'data' => $model->getErrors(),
                    );
                }
            } else {
                $result = array(
                        'status' => false,
                        'msg' => '不存在该订单',
                );
            }

            #取消订单通知商家
            if ($status == 2) {
                #$printData = ShopOrder::model()->buildPrintData($model, $this->shop);
                try {
                    $puser = new PushHelper($this->shop);
                    $puser->diy('<CB>订单取消通知</CB>
用户ID：' . $this->weixin->id . '
订单号：' . $model->id . '
取消时间：' . date('Y-m-d H:i:s'));
                } catch (Exception $e) {

                }
            }

            $this->jsonout($result);
        }
    }

    public function actionAbout()
    {
        $this->render('about');
    }

    public function actionAddress()
    {
        $this->render('address');
    }


    /**
     * 验证手机号码
     * @param $mobile
     * @param $addresses_id
     */
    public function actionMobileVerify($mobile, $addresses_id)
    {
        #判断手机号码是否已使用过
        $model = UsedMobile::model()->checkMobile($mobile);

        if (!$model) {
            $model = new UsedMobile();
            $model->weixin_id = $this->weixin->id;
            $model->mobile = $mobile;


            $lastSendMobileCodeTime = Yii::app()->cache->get('lastSendMobileCodeTime');
            if (!$lastSendMobileCodeTime || time() - $lastSendMobileCodeTime > 60) {
                try {
                    $sms = new SMS($mobile, $this->account->admin);
                    $code = VerificationCode::model()->getCode('mobile', $mobile);
                    $result = $sms->send('手机验证码为：' . $code . '，有效期为1小时。', 'V');
                } catch (Exception $e) {
                    $result = false;
                }

                #如果发送失败，则自动设置为已验证
                if (!$result) {
                    $model->status = 'Y';
                    $model->save();
                    #更新地址状态
                    UsedAddresses::model()->updateByPk($addresses_id, array(
                            'status' => 'Y'
                    ));

                    Yii::app()->cache->delete('lastSendMobileCodeTime');

                    $this->redirect(array('product/order'));
                }

                Yii::app()->cache->set('lastSendMobileCodeTime', time(), 60);
            }

        } else {
            #如果存在，则判断是否是本人
            if ($model->weixin_id !== $this->weixin->id) {
                $this->error('该手机号码已被其他用户验证过。', array('profile/addAddress', 'id' => $addresses_id));
            } else {
                $this->redirect(array('product/order'));
            }


        }

        if (Yii::app()->request->isPostRequest && isset($_POST['code'])) {

            if (VerificationCode::model()->checkCode('mobile', $_POST['code'])) {
                $model->status = 'Y';
                $model->dateline = DATELINE;

                if ($model->save()) {
                    Yii::app()->cache->delete('lastSendMobileCodeTime');

                    #更新地址状态
                    UsedAddresses::model()->updateByPk($addresses_id, array(
                            'status' => 'Y'
                    ));

                    $this->redirect(array('product/order'));
                }
            }
        }

        $this->render('mobileVerify', array(
                'model' => $model,
        ));
    }


    public function actionSendSmsVerifyCode($mobile)
    {
        $lastReSendMobileCodeTime = Yii::app()->cache->get('lastReSendMobileCodeTime');
        if (!$lastReSendMobileCodeTime || time() - $lastReSendMobileCodeTime > 60) {
            try {
                $sms = new SMS($mobile, $this->account->admin);
                $code = VerificationCode::model()->getCode('mobile', $mobile);
                $result = $sms->send('手机验证码为：' . $code . '，有效期为1小时。', 'V');
                if ($result) {
                    $result = array(
                            'status' => true,
                            'msg' => '短信验证码已发送成功，请查收！',
                    );
                } else {
                    $result = array(
                            'status' => false,
                            'msg' => '发送失败，请稍后再试。',
                    );
                }
            } catch (Exception $e) {
                $result = array(
                        'status' => false,
                        'msg' => '发送失败，请稍后再试。',
                );
            }

            Yii::app()->cache->set('lastReSendMobileCodeTime', time(), 60);
        } else {
            $result = array(
                    'status' => false,
                    'msg' => '发送太频繁，请稍后再试',
            );
        }
        $this->jsonout($result);
    }

    public function actionCuidan($id)
    {
        if (Yii::app()->request->isAjaxRequest) {
            $order = ShopOrder::model()->findByPk($id);
            if ($order) {


                try {
                    $pusher = new PushHelper($this->shop);
                    $data = $pusher->buildData($order);
                    $data['name'] = '催单通知：' . $order->id;

                    $result = $pusher->send($data);

                    if ($result) {
                        if (in_array($order->status, array(0, 1))) {
                            $order->status = 9;
                            $order->save();
                        }

                        $result = array(
                                'status' => true,
                                'msg' => '催单成功，' . $this->shop->name . '已收到你的催单请来。',
                        );
                    } else {
                        $result = array(
                                'status' => false,
                                'msg' => '催单失败，请拨打本页最底部的商家电话。',
                        );
                    }
                } catch (Exception $e) {
                    $result = array(
                            'status' => false,
                            'msg' => '催单失败，请拨打本页最底部的商家电话。',
                    );
                }


            } else {
                $result = array(
                        'status' => false,
                        'msg' => '不存在本订单，请拨打本页最低下的商家电话。',
                );
            }

            $this->jsonout($result);
        }
    }
}