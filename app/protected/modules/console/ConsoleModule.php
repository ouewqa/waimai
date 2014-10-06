<?php

class consoleModule extends CWebModule
{
    public function init()
    {
        // this method is called when the module is being created
        // you may place code here to customize the module or the application
        // import the module-level models and components


        #parent::init();
        $this->setImport(array(
                'console.models.*',
                'console.components.*',
        ));
    }

    public function beforeControllerAction($controller, $action)
    {


        if (parent::beforeControllerAction($controller, $action)) {

            if (!$this->allowIp(Yii::app()->request->userHostAddress)) {
                throw new CHttpException(403, "You are not allowed to access this page.");
            }

            if (!Yii::app()->user->isGuest && Yii::app()->user->getIsAdmin()) {
                return true;
            } else {
                Yii::app()->user->logout();
                Yii::app()->user->loginRequired();
            }

        }

        return false;
    }

    /**
     * 验证IP
     * @param $ip
     * @return bool
     */
    protected function allowIp($ip)
    {
        if (empty($this->ipFilters))
            return true;
        foreach ($this->ipFilters as $filter) {
            if ($filter === '*' || $filter === $ip || (($pos = strpos($filter, '*')) !== false && !strncmp($ip, $filter, $pos)))
                return true;
        }
        return false;
    }
}
