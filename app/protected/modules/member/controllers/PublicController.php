<?php

class PublicController extends BackendController
{
    public function actionLogin()
    {
        if (!Yii::app()->user->isGuest && Yii::app()->user->getReturnUrl()) {
            $this->redirect(Yii::app()->user->getReturnUrl());
        }

        $model = new Admin;

        if (isset($_POST['Admin'])) {

            if (!$_POST['Admin']['username'] || !$_POST['Admin']['password']) {
                Yii::app()->user->setFlash('loginStatus', '账号密码和密码不得为空！');
            } else {

                $identity = new UserIdentity($_POST['Admin']['username'], $_POST['Admin']['password']);
                try {

                    if ($identity->authenticate()) {
                        Yii::app()->user->login($identity, isset($_POST['saveLoginStatus']) ? 86400 * 7 : 0);

                        #登陆后跳转
                        $this->redirect('/member');

                        /*if (Yii::app()->user->getReturnUrl()) {
                            $this->redirect(Yii::app()->user->getReturnUrl());
                        } else {
                            $this->redirect('/member');
                        }*/

                        #默认用户
                        /* $auth = Yii::app()->authManager;
                         $bizRule = "return !Yii::app()->user->isGuest;";
                         $role = $auth->createRole("authenticated", "authenticated user", $bizRule);*/
                    } else {

                        switch ($identity->errorCode) {
                            case 1:
                                $message = '您输入的账号有误！';
                                break;
                            case 2:
                                $message = '您输入的账号或密码不正确！';
                                break;
                            case 10:
                                $message = '您的账号已过试用期。请联系管理员';
                                break;
                            default:
                                $message = '登陆失败，请联系管理员';
                                break;
                        }

                        Yii::app()->user->setFlash('loginStatus', $message);
                    }


                } catch (Exception $e) {
                    Yii::app()->user->setFlash('loginStatus', $e->getMessage());
                }
            }
        }


        $this->setPageTitle('登陆系统');
        $this->render('login', array(
                'model' => $model,
        ));
    }

    public function actionLogout()
    {
        Yii::app()->user->logout(false);
        /*SessionHelper::clear();
        SessionHelper::destroy();*/
        $this->redirect(Yii::app()->user->loginUrl);
    }

    /**
     * 注册
     */
    public function actionRegister()
    {
        $model = new Admin;

        if (isset($_POST['Admin'])) {
            $model->attributes = $_POST['Admin'];

            $model->salt = $model->genRandomString(6);

            $model->repassword = $_POST['Admin']['repassword'];

            if ($model->validate()) {

                if ($model->save()) {
                    #自动登陆
                    $identity = new UserIdentity($_POST['Admin']['username'], $_POST['Admin']['password']);
                    try {
                        if ($identity->authenticate()) {
                            Yii::app()->user->login($identity, isset($_POST['saveLoginStatus']) ? 86400 * 10 : 0);
                        }
                    } catch (Exception $e) {

                    }

                    $this->redirect(array('default/dashboard'));
                } else {
                    print_r($model->getErrors());
                    exit;
                    $this->error('注册失败,请返回重新注册', array('register'));
                }
            }
        }


        $this->setPageTitle('注册账号');
        $this->render('register', array(
                'model' => $model
        ));
    }


    public function actionTest()
    {
        $this->layout = '//layouts/min';

        $model = WeixinQrcode::model()->getNewSceneId($this->account);

        if ($model) {
            echo $model->scene_id;

        }

        $model = new Shop();

        if (Yii::app()->request->isPostRequest) {
            print_r($_POST);
            exit;
        }
        $this->render('test', array(
                'model' => $model,
        ));
    }

    public function actionWxPay($api)
    {
        list($account_id, $action) = explode('_', $api);

        $account_id = intval($account_id);
        if (!in_array($action, array('client', 'priority_notify', 'warning'))) {
            throw new CHttpException(404, '不存在这个服务！');
        }
    }

    /**
     * 响应tenpay服务器返回通知
     */
    public function actionTenpayReturn()
    {
        $order_id = TenpayHelper::returnInterface();
        if ($order_id) {
            $this->redirect(array(
                    '/member/siteProductOrder/view', 'id' => $order_id
            ));
        } else {
            $this->redirect(array(
                    '/member/siteProductOrder/index'
            ));
        }
    }

    /**
     * 响应tenpay服务器通知
     */
    public function actionTenpayNotify()
    {
        $result = TenpayHelper::notifyInterface();
        if ($result) {
            echo 'success';
            Yii::app()->end();
        } else {
            echo 'fail';
            Yii::app()->end();
        }
    }


    /**
     * 响应alipay服务器通知
     */
    public function actionAlipayReturn()
    {
        if (AlipayHelper::returnInterface()) {

            //Yii::log(var_export($_GET), 'error', 'application');

                        $order_sn = $_GET['out_trade_no'];
            $trade_no = $_GET['trade_no'];
            $total_fee = $_GET['total_fee'];

            if ($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS') {
                $order_id = SiteProductOrder::purchased($order_sn, $trade_no);
                if ($order_id) {
                    $this->redirect(array(
                            '/member/siteProductOrder/view', 'id' => $order_id
                    ));
                } else {
                    $this->redirect(array(
                            '/member/siteProductOrder/index'
                    ));
                }
            } else {
                $result = "trade_status=" . $_GET['trade_status'];
            }
        } else {
            $result = "fail";
        }

        exit($result);
    }


    /**
     * 接收alipay服务器通知
     */
    public function actionAlipayNotify()
    {
        if (AlipayHelper::notifyInterface()) {

            $order_id = $_POST['out_trade_no'];
            $trade_no = $_POST['trade_no'];
            $order_fee = $_POST['total_fee'];

            if (($_POST['trade_status'] == 'TRADE_FINISHED' || $_POST['trade_status'] == 'TRADE_SUCCESS') && SiteProductOrder::purchased($order_id, $trade_no)) {
                $result = "success";
            } else {
                $result = "fail";
            }
        } else {
            $result = "fail";
        }

        exit($result);
    }

    /**
     * 地区动态级别
     * @param $name
     * @param int $level 层级，1为省，2为市，3为区
     */
    public function actionGetDynamicArea($name, $level = 1)
    {
        if (Yii::app()->request->isAjaxRequest) {


            if ($level == 2) {
                $model = CateArea::model()->getCityList($name);
                if ($model) {
                    $model = CHtml::listData($model, 'name', 'name');
                }

                $aa = "-请选择市-";
            } else if ($level == 3) {
                $model = CateArea::model()->getDistrictList($name);
                if ($model) {
                    $model = CHtml::listData($model, 'name', 'name');
                }

                $aa = "-请选择区-";
            }

            echo CHtml::tag('option', array('value' => 'empty'), $aa, true);

            $area = '';
            foreach ($model as $value => $name) {
                $area .= CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
            }
            echo $area;
        }
    }

}