<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Keen
 * Date: 13-8-23
 * Time: 下午4:35
 * To change this template use File | Settings | File Templates.
 */

class AdminToolsController extends AdminWeixinController{


    public function init()
    {
        parent::init();
        $this->layout = '//layouts/tools';
    }

}