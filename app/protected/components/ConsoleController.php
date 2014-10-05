<?php

/**
 * Created by JetBrains PhpStorm.
 * User: Keen
 * Date: 13-8-23
 * Time: 下午4:24
 * To change this template use File | Settings | File Templates.
 */
class ConsoleController extends CoreController
{
    public $leftMenu;

    public function init()
    {
        parent::init();
        $this->layout = '//layouts/console';
        Yii::app()->theme = 'classic';
    }


    protected function beforeAction($action)
    {
        parent::beforeAction($action);

        #导航
        $this->makeNavbar();

        #设置导航
        $this->makeMenu();

        return true;
    }


    private function makeNavbar()
    {

        #获取所有微信账号
        $weixinAccountItem = $weixinAccountButton = $shopItem = $shopButton = array();


        $this->navbar = array(
                'brandLabel' => '系统后台',
                'brandUrl' => '/console',
                'fluid' => true,
            #'display' => null, // default is static to top
                'items' => array(
                        array(
                                'class' => 'bootstrap.widgets.TbNav',
                                'htmlOptions' => array('class' => 'pull-right'),
                                'items' => array(
                                        array('label' => '会员后台', 'url' => '/member'),
                                        array('label' => Yii::app()->user->name,
                                                'icon' => 'user',
                                                'visible' => !Yii::app()->user->isGuest,
                                                'items' => array(
                                                        array('label' => '退出', 'icon' => 'off', 'url' => array('/member/public/logout'))
                                                )
                                        )


                                ),
                        ),
                ),
        );
    }


    private function makeMenu()
    {
        $menus = array(
                array(
                        'label' => '用户管理',
                        'icon' => 'icon-th-large',
                        'items' => array(
                                array(
                                        'label' => '用户列表',
                                        'url' => array('/console/admin/index'),
                                ),
                        ),
                ),
                array(
                        'label' => '权限管理',
                        'icon' => 'icon-th-large',
                        'items' => array(
                                array(
                                        'label' => '用户授权',
                                        'url' => array('/auth/assignment/index'),
                                ),
                                array(
                                        'label' => '添加角色',
                                        'url' => array('/auth/role/create'),
                                ),
                                array(
                                        'label' => '添加任务',
                                        'url' => array('/auth/task/create'),
                                ),
                                array(
                                        'label' => '添加操作',
                                        'url' => array('/auth/operation/create'),
                                ),
                        ),
                ),
                array(
                        'label' => '网站通知',
                        'icon' => 'icon-th-large',
                        'items' => array(
                                array(
                                        'label' => '通知列表',
                                        'url' => array('/console/siteNotification/index'),
                                ),
                                array(
                                        'label' => '新增通知',
                                        'url' => array('/console/siteNotification/create'),
                                ),
                        ),

                ),
                array(
                        'label' => '网站帮助',
                        'icon' => 'icon-th-large',
                        'items' => array(
                                array(
                                        'label' => '帮助列表',
                                        'url' => array('/console/siteHelp/index'),
                                ),
                                array(
                                        'label' => '新增帮助',
                                        'url' => array('/console/siteHelp/create'),
                                ),
                        ),

                ),
                array(
                        'label' => '站内产品',
                        'icon' => 'icon-th-large',
                        'items' => array(
                                array(
                                        'label' => '产品列表',
                                        'url' => array('/console/siteProduct/index'),
                                ),
                                array(
                                        'label' => '添加产品',
                                        'url' => array('/console/siteProduct/create'),
                                ),
                                array(
                                        'label' => '产品分类',
                                        'url' => array('/console/siteProductCategory/index'),
                                ),
                                array(
                                        'label' => '添加分类',
                                        'url' => array('/console/siteProductCategory/create'),
                                ),
                                array(
                                        'label' => '订单列表',
                                        'url' => array('/console/siteProductOrder/index'),
                                ),
                        ),

                ),
        );

        #判断是否需要展开
        $firstLevelActive = null;
        foreach ($menus as $key => $menu) {
            if (isset($menu['items']) && $menu['items']) {
                foreach ($menu['items'] as $k => $item) {
                    $result = $this->checkActionActive($item['url']);
                    $menus[$key]['items'][$k]['active'] = $result;

                    if ($result) {
                        $firstLevelActive = $key;
                    }

                }
            }
        }
        if (!is_null($firstLevelActive)) {
            $menus[$firstLevelActive]['active'] = true;
        }
        $this->leftMenu = $menus;
    }
}