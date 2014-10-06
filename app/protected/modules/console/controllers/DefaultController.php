<?php

class DefaultController extends ConsoleController
{
    public function actionIndex()
    {

        $this->render('index');
    }
    public function actionTest()
    {

        $this->render('test');
    }

}