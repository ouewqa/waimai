<?php

class ApiController extends WeixinApiController
{

    /**
     *
     * @throws CHttpException
     */
    public function actionCallBack($id, $token)
    {
        if (!$id || !$token) {
            throw new CHttpException(404, '参数出错！');
        }

        $account = WeixinAccount::model()->findByPk($id);

        if (!$account) {
            throw new CHttpException(404, '当前公众号不存在！');
        } else if ($account->token != $token) {
            throw new CHttpException(404, 'TOKEN值错误！');
        }

        #初始化
        $this->weixin = new WeixinHelper($account);

        #初始化，捕获请求信息包，保存用户信息
        $this->weixin->init();

        #判断公众号是否已过期
        if ($account->status == 'F') {

            $this->weixin->message = array(
                    'type' => 'text',
                    'data' => '当前公众号已关闭或服务已到期！',
            );

            #发送消息
            $this->weixin->response();

        } else if ($this->weixin->user && $this->weixin->user->status == 'F') {

            $this->weixin->message = array(
                    'type' => 'text',
                    'data' => '你已被标为违禁用户，如系统有误，请联系管理员。',
            );

            #发送消息
            $this->weixin->response();

        } else {

            $this->weixin->msg->MsgType = strtolower($this->weixin->msg->MsgType);
            if ($this->weixin->msg->MsgType) {
                if ($this->weixin->msg->MsgType) {
                    #响应处理，所有处理函数　response　打头
                    try {

                        #执行响应请求
                        $this->weixin->log('处理response' . ucfirst($this->weixin->msg->MsgType) . '响应。');
                        $do = 'response' . ucfirst($this->weixin->msg->MsgType);
                        $this->$do();
                    } catch (Exception $e) {
                        $this->weixin->log($e->getMessage());
                        $this->weixin->setFlag(true);
                        $this->weixin->message = array(
                                'type' => 'text',
                                'data' => '命令执行出错，请联系管理员，出错内容:' . $e->getMessage(),
                        );
                        #Yii::log('微信请求执行失败：' . $e->getMessage(), 'error', 'weixin.apiController.response');
                    }

                    if (!$this->weixin->message) {
                        $this->defaultResponse();
                    }

                    #发送消息
                    $this->weixin->response();

                    #即时抛出消息
                    ob_flush();
                    flush();

                    #超时记录
                    //$this->checkTimeout();

                    #后续回调，异步
                    #$this->callback();

                    #保存记录
                    $this->saveMessage();

                    #同步微信官方用户数据
                    $this->autoSyncUserInfo();

                    #请求数处理
                    $this->weixin->account->count_request += 1;
                    $this->weixin->account->save();


                    #用户数据全局保存
                    if (!$this->weixin->user->save()) {
                        $this->weixin->log('保存用户信息失败');
                        $this->weixin->log($this->weixin->user->getErrors());
                    } else {
                        $this->weixin->log('保存用户信息成功');
                    }
                }
            } else {
                throw new CHttpException(500, '微信接口专用');
            }
        }


    }


    public function actionIndex()
    {
        throw new CHttpException(404, '小朋友，你迷路了么？');
    }

    /**
     * @param $code
     * @param null $state 这个值用户跳转用
     * @param $weixin_account_id
     * @throws CHttpException
     */
    public function actionOAuth($code, $weixin_account_id, $state = null)
    {

        //https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx6f4215c8d31a61c2&redirect_uri=http%3A%2F%2Fwx.ribenyu.cn%2Fweixin%2Fdefault%2FoAuth&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect&weixin_account_id=1

        $account = WeixinAccount::model()->findByPk($weixin_account_id);


        if ($account && $code) {

            if ($account->status == 'F') {
                throw new CHttpException(500, '当前公众号已关闭或服务已到期！');
            }


            #同意授权
            $this->weixin = new WeixinHelper($account);
            $result = $this->weixin->oauth2($code);

            $this->weixin->log('Oauth请求结果：');
            $this->weixin->log($result);

            if (isset($result['errcode']) && $result['errcode'] === 0 && isset($result['data']['openid'])) {

                if ($state) {
                    $state = str_replace('#wechat_', '', $state);
                    $user = Weixin::model()->find(
                            'open_id=:open_id', array(
                            ':open_id' => $result['data']['openid'],
                    ));

                    #weixin_group_id = 1 为黑名单
                    if ($user && $user->status == 'Y' && $user->weixin_group_id != 1) {

                        $this->weixin->log('执行跳转地址：' . $state);
                        $this->redirect($state . '/open_id/' . $user->open_id);
                    } else {
                        $this->weixin->log('用户不存在');
                        throw new CHttpException(500, '你已被列入黑名单，如有疑问，请联系管理员！');
                    }
                }
            } else {
                $this->weixin->log('oAuth认证失败');
                $this->weixin->log($result);
            }
        } else {
            #不同意授权
            echo '不同意授权';
        }
    }


}