<?php

class DefaultController extends FrontController
{
    public function actionIndex()
    {
        $announcement = Announcement::model()->findOwnersAnnouncement($this->shop->id);

        $shops = Shop::model()->findOwnerShops($this->account->id);

        $this->setPageTitle($this->shop->name);
        $this->render('index', array(
                'announcement' => $announcement,
                'shops' => $shops,
        ));
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

                $aa = "市";
            } else if ($level == 3) {
                $model = CateArea::model()->getDistrictList($name);
                if ($model) {
                    $model = CHtml::listData($model, 'name', 'name');
                }

                $aa = "区";
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