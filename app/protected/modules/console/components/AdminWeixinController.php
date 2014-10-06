<?php

/**
 * Created by JetBrains PhpStorm.
 * User: Keen
 * Date: 13-8-23
 * Time: 下午4:35
 * To change this template use File | Settings | File Templates.
 */
class AdminWeixinController extends BackendController
{
    public $account;

    public function init()
    {
        parent::init();
        $this->layout = '//layouts/weixin';
        #查找默认的微信账号
        $account = WeixinAccount::model()->findDefaultAccount();
        if ($account) {
            $this->account = $account;
        } else {
            if ($this->id != 'weixinAccount') {
                $this->redirect('/console/weixinAccount/create');
            }
        }
    }



    public function notification($to_weixin_id, $type, $message)
    {
        #自己不通知自己
        //if ($this->user->id != $weixin_id) {
        $notification = new WeixinNotification();
        $notification->attributes = array(
                'to_weixin_id' => $to_weixin_id,
                'from_weixin_id' => 1,
                'type' => $type,
                'message' => $message,
                'dateline' => time(),
        );
        if ($notification->save()) {
            $result = true;
        } else {
            //$notification->getErrors()
            $result = false;
        }

        return $result;
        //}
    }


    public function notificationFeedbackReply($to_weixin_id, $feedback, $reply)
    {
        $user = Weixin::model()->findByPk($to_weixin_id);
        $message = "管理员回复了您的反馈\n\n{$feedback}\n\n【回复内容】\n{$reply}\n\n";
        $message .= '<a href="' . $this->createAbsoluteUrl('/weixin/weixinFeedback/index', array('active' => 'list', 'weixin_id' => $user->id, 'open_id' => $user->open_id)) . '">[查看详情]</a>';
        $this->notification($to_weixin_id, '反馈', $message);
    }


    /**
     * 权限检查
     * @param $model 　带有weixin_account_id的模型
     * @throws CHttpException
     * @return bool
     */
    public function authorizationCheckByAccountId($model)
    {
        if ($model && isset($model->weixin_account_id)) {
            $account = WeixinAccount::model()->cache(0)->findByPk($model->weixin_account_id);

            if ($account) {
                if (Yii::app()->user->id != $account->admin_id) {
                    throw new CHttpException(500, '你的权限不足');
                } else {
                    return true;
                }
            } else {
                throw new CHttpException(500, '不存在该微信账号');
            }
        }

    }
}