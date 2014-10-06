<?php

class MemberModule extends CWebModule
{
    public function init()
    {
        // this method is called when the module is being created
        // you may place code here to customize the module or the application

        // import the module-level models and components
        $this->setImport(array(
                'member.models.*',
                'member.components.*',
        ));
    }

    public function beforeControllerAction($controller, $action)
    {
        if (parent::beforeControllerAction($controller, $action)) {
            // this method is called before any module controller action is performed
            // you may place customized code here

            $publicActions = array(
                    'public/test',
                    'public/login',
                    'public/logout',
                    'public/register',
                    'public/tenpayReturn',
                    'public/tenpayNotify',
                    'fileUpload/index',
            );

            $route = $controller->id . '/' . $action->id;


            if (Yii::app()->user->isGuest) {
                if (!in_array($route, $publicActions)) {
                    Yii::app()->user->setReturnUrl(Yii::app()->request->url);
                    Yii::app()->user->loginRequired();
                }
            }

            return true;

        } else
            return false;
    }
}
