<?php

class PublicController extends FrontController
{


    /**
     * 设置默认门店
     */
    public function actionGetDefaultShop()
    {
        $shops = Shop::model()->findOwnerShops($this->account->id);
        if (Yii::app()->request->isAjaxRequest) {

        }

        $this->setPageTitle('设置距你最近的店');

        $this->render('getDefaultShop', array(
                'shops' => $shops,
        ));
    }

    /**
     * 设置默认门店
     * @param $shop_id
     */
    public function actionSetDefaultShop($shop_id)
    {
        if ($shop_id) {
            CookieHelper::set('defaultShop', $shop_id);
            $this->redirect('/weixin/default/index');
        } else {
            $this->redirect('/weixin/default/getDefaultShop');
        }
    }
}