<?php

class DefaultController extends webController
{
    public function actionIndex()
    {

        if ($this->isRobot()) {
            $this->layout = '//layouts/main_for_robot';
            $this->render('index_for_robot');
        } else {
            $this->layout = '//layouts/main';
            $this->render('index');
        }
    }

    public function actionContact()
    {


        if (Yii::app()->request->isAjaxRequest && $_POST) {

            foreach ($_POST as $key => $value) {
                $_POST[$key] = strip_tags($value);
            }


            $content = sprintf("
<p>姓名：%s</p>
<p>手机：%d</p>
<p>留言：%s</p>
",
                    $_POST['name'], $_POST['mobile'], nl2br($_POST['comments']));

            $result = EmailHelper::send(array(
                    'email' => '6202551@qq.com',
                    'subject' => '微信订餐需求联系',
                    'data' => array(
                            'title' => '微信订餐需求联系',
                            'content' => $content,
                    ),
                    'layout' => 'mail',
                    'view' => 'email',
                    'debug' => YII_DEBUG,
            ));

            if ($result) {
                $result = array(
                        'status' => true,
                        'msg' => '发送成功，我们会有销售人员联系你。请保存手机畅通。',
                );
            } else {
                $result = array(
                        'status' => false,
                        'msg' => '发送失败，请使用手机，或邮件方式联系我。',
                );
            }

            $this->jsonout($result);
        }
    }
}