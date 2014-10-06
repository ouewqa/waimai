<?php

class TestController extends FrontController
{
    public function init()
    {
        Yii::app()->theme = 'classic';
        parent::init();
        $this->layout = '//layouts/ratchet';
    }


    public function actionIndex()
    {

    }

    public function actionA()
    {
       $this->render('a');
    }

    public function actionB()
    {
        $this->render('b');
    }



}