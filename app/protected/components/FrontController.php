<?php

/**
 * Created by JetBrains PhpStorm.
 * User: Keen
 * Date: 13-8-23
 * Time: 下午5:18
 * To change this template use File | Settings | File Templates.
 */
class FrontController extends CoreController
{
    public $account, $shop, $weixin;

    public function init()
    {

        Yii::app()->theme = 'classic';
        #布局
        $this->layout = '//layouts/weixin';


        #检查用户
        $this->checkUser();

        $public = array('public', 'test');
        if (!in_array($this->id, $public)) {
            #非微信端，自动跳出
            if (!ISWEIXINCLIENT && !YII_DEBUG) {
                throw new CHttpException(404, '该页面不存在~');
            }

            #查找默认门店
            $this->findDefaultShop();
        }


        parent::init();
    }

    /**
     * 检查用户
     * @throws CHttpException
     */
    private function checkUser()
    {
        $open_id = isset($_GET['open_id']) ? trim($_GET['open_id']) : CookieHelper::get('open_id');
        if (!$open_id) {
            throw new CHttpException(404, '同学，您是不是迷路了呀~~');
        } else {
            CookieHelper::set('open_id', $open_id);
        }

        $weixin = Weixin::model()->findByOpenId($open_id);
        if (!$weixin) {
            throw new CHttpException(500, '当前用户不存在或已被封禁！');
        } else {
            $this->weixin = $weixin;
            $account = WeixinAccount::model()->findByPk($weixin->weixin_account_id, 'status=:status', array(
                    ':status' => 'Y',
            ));

            if (!$account) {
                throw new CHttpException(500, '本微信公众号已停止服务！');
            } else {
                $this->account = $account;

                #请求数处理
                $account->count_request += 1;
                $account->save();
            }
        }
    }


    /**
     * 查找默认门店
     */
    private function findDefaultShop()
    {
        #寻找最近的
        $defaultShop = intval(CookieHelper::get('defaultShop'));

        if (!$defaultShop) {

            $shops = Shop::model()->findOwnerShops($this->account->id);

            if (count($shops) > 1) {
                $this->redirect('/weixin/public/getDefaultShop');
            } else {
                foreach ($shops as $shop) {
                    $defaultShop = $shop->id;
                }
                CookieHelper::set('defaultShop', $defaultShop);
            }
        }

        $shop = Shop::model()->findByPk($defaultShop, 'status=:status', array(
                ':status' => 'Y',
        ));

        if (!$shop) {
            throw new CHttpException(500, '该门店已暂停服务！');
        } else {
            $this->shop = $shop;

            #风格设置
            if ($this->shop->theme) {
                Yii::app()->theme = $this->shop->theme;
            }
        }
    }

}