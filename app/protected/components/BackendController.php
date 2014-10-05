<?php

/**
 * Created by JetBrains PhpStorm.
 * User: Keen
 * Date: 13-8-23
 * Time: 下午4:24
 * To change this template use File | Settings | File Templates.
 */
class BackendController extends CoreController
{
    public $leftMenu, $user, $account, $shop;

    public function init()
    {
        parent::init();
        $this->layout = '//layouts/main';
        Yii::app()->theme = 'classic';


        #判断是否需要跳转
        if (!Yii::app()->user->isGuest) {

            #判断试用用户是否过期
            $user = Admin::model()->cache(0)->findByPk(Yii::app()->user->id, 'status=:status', array(
                    ':status' => 'Y'
            ));

            if (!$user) {

            } else if ($user->expire < DATELINE) {

            } else {
                $this->user = $user;
            }


            #查找默认的微信账号
            $account = WeixinAccount::model()->findDefaultAccount();

            if ($account) {
                $this->account = $account;
                Yii::app()->user->setState('weixin_account_id', $this->account->id);


                #查找默认门店
                $shop = Shop::model()->findDefaultShop();
                if ($shop) {
                    $this->shop = $shop;
                } else {
                    #判断当前网址，跳转自动创建门店
                    if (!in_array($this->id, array('shop', 'weixinAccount', 'public'))) {
                        Yii::app()->user->setReturnUrl(Yii::app()->request->url);
                        $this->error('您需要先创建一个门店！', '/member/shop/create');
                    }
                }


            } else {

                #判断当前网址，跳转创建微信公众号
                if (!in_array($this->id, array('weixinAccount', 'public'))) {
                    Yii::app()->user->setReturnUrl(Yii::app()->request->url);
                    $this->error('您已注册成功，使用微单前，您需要先添加一个微信公众号账号', '/member/weixinAccount/create');
                }
            }


        }

    }


    protected function beforeAction($action)
    {
        parent::beforeAction($action);

        #echo Yii::app()->controller->id;exit;

        #如果已登陆，自动跳转
        if (!Yii::app()->user->isGuest && in_array($action->getId(), array('login', 'register'))) {
            $this->redirect(array('/member'));
        }


        if ($this->id == 'public') {
            $this->layout = '//layouts/public';
        } else {
            #风格
            $this->layout = '//layouts/member';

            #导航
            $this->makeTopMenu();

            #设置导航
            $this->makeLeftMenu();
        }
        return true;
    }


    /**
     * 设置顶部菜单
     */
    private function makeTopMenu()
    {

        #获取所有微信账号
        $weixinAccountItem = $weixinAccountButton =
        $shopItem = $shopButton = array();

        #微信公众号导航
        if ($this->account) {

            $dependency = new CDbCacheDependency('SELECT MAX(id) FROM `weixin_account` WHERE admin_id=' . Yii::app()->user->id);
            $weixinAccount = WeixinAccount::model()->cache(86400, $dependency)->findAll(array(
                    'condition' => 'status=:status AND id!=:id AND admin_id=:admin_id',
                    'params' => array(
                            ':status' => 'Y',
                            ':id' => $this->account->id,
                            ':admin_id' => Yii::app()->user->id,
                    ),
                    'limit' => 100
            ));


            if ($weixinAccount) {
                foreach ($weixinAccount as $key => $value) {
                    $weixinAccountItem[] = array(
                            'label' => '切换微信公众号：' . $value->name, 'icon' => 'user', 'url' => array('/member/weixinAccount/changeAccount', 'id' => $value->id),
                    );
                }

                $weixinAccountButton[] = array(
                        'label' => '当前微信公众号：' . $this->account->name,
                        'url' => '#',
                        'items' => $weixinAccountItem,
                        'visible' => !Yii::app()->user->isGuest
                );
            }


            #店铺导航
            if ($this->shop) {
                $shops = Shop::model()->findAll(array(
                        'condition' => 'status=:status AND id!=:id AND weixin_account_id=:weixin_account_id',
                        'params' => array(
                                ':id' => $this->shop->id,
                                ':status' => 'Y',
                                ':weixin_account_id' => $this->account->id,
                        ),
                        'limit' => 100
                ));

                /*echo '$this->shop->id：', $this->shop->id, PHP_EOL;
                echo '$this->account->id：', $this->account->id, PHP_EOL;
                echo 'shop count', count($shops), PHP_EOL;*/


                if ($shops) {
                    foreach ($shops as $key => $value) {
                        $shopItem[] = array(
                                'label' => '切换门店：' . $value->name, 'icon' => 'user', 'url' => array('/member/shop/changeShop', 'id' => $value->id),
                        );
                    }

                    $shopButton[] = array(
                            'label' => '当前门店：' . $this->shop->name,
                            'url' => '#',
                            'items' => $shopItem,
                            'visible' => !Yii::app()->user->isGuest
                    );
                }
            }


        }

        #var_dump($weixinAccountButton);exit;


        $this->navbar = array(
                'brandLabel' => '微单 » 微信订餐系统',
                'brandUrl' => '/member',
                'fluid' => true,
            #'display' => null, // default is static to top
                'items' => array(
                        array(
                                'class' => 'bootstrap.widgets.TbNav',
                                'items' => $weixinAccountButton,
                        ),
                        array(
                                'class' => 'bootstrap.widgets.TbNav',
                                'items' => $shopButton,
                        ),

                        array(
                                'class' => 'bootstrap.widgets.TbNav',
                                'htmlOptions' => array('class' => 'pull-right'),
                                'items' => array(
                                        array('label' => '网站前台', 'url' => '/'),
                                        array('label' => Yii::app()->user->name,
                                                'icon' => 'user',
                                                'visible' => !Yii::app()->user->isGuest,
                                                'items' => array(
                                                        array('label' => '个人资料', 'icon' => 'user', 'url' => array('/member/default/setting')),
                                                        array('label' => '修改密码', 'icon' => 'user', 'url' => array('/member/default/changePassword')),
                                                        array('label' => '退出', 'icon' => 'off', 'url' => array('/member/public/logout'))
                                                )
                                        )


                                ),
                        ),
                ),
        );
    }


    /**
     * 设置左侧菜单
     */
    private function makeLeftMenu()
    {
        $menus = array(
                array(
                        'label' => '控制面板',
                        'icon' => 'icon-th-large',
                        'url' => array('/member/default/dashboard'),
                ),
                array(
                        'label' => '订单管理',
                        'icon' => 'icon-th-list',
                        'items' => array(
                                array(
                                        'label' => '列表',
                                        'url' => array('/member/shopOrder/index'),
                                ),
                                array(
                                        'label' => '统计',
                                        'url' => array('/member/shopOrder/analyze'),
                                ),
                        )),

                array(
                        'label' => '菜品管理',
                        'icon' => 'icon-fire',
                        'items' => array(
                                array(
                                        'label' => '菜品列表',
                                        'url' => array('/member/shopDish/index'),
                                ), array(
                                        'label' => '添加菜品',
                                        'url' => array('/member/shopDish/create'),
                                ),
                                array(
                                        'label' => '菜品分类',
                                        'url' => array('/member/shopDishCategory/index'),
                                ),
                                array(
                                        'label' => '添加分类',
                                        'url' => array('/member/shopDishCategory/create'),
                                ),
                        )),

                array(
                        'label' => '商户管理',
                        'icon' => 'icon-inbox',
                        'items' => array(
                                array(
                                        'label' => '门店管理',
                                        'url' => array('/member/shop/index'),
                                ),
                                array(
                                        'label' => '门店创建',
                                        'url' => array('/member/shop/create'),
                                ),
                                array(
                                        'label' => '通知方式',
                                        'url' => array('/member/shop/notification'),
                                ),
                                array(
                                        'label' => '门店公告',
                                        'url' => array('/member/announcement/index'),
                                ), array(
                                        'label' => '添加公告',
                                        'url' => array('/member/announcement/create'),
                                ),
                                array(
                                        'label' => '词汇自定义',
                                        'url' => array('/member/shop/language'),
                                ),
                                array(
                                        'label' => '意见反馈',
                                        'url' => array('/member/feedback/index'),
                                ),
                        )),

                array('label' => '会员管理',
                        'icon' => 'icon-user',
                        'items' => array(
                                array(
                                        'label' => '消息列表',
                                        'url' => array('/member/weixinMessage/index'),
                                ),
                                array(
                                        'label' => '微信会员',
                                        'url' => array('/member/weixin/index'),
                                ),
                                array(
                                        'label' => '会员分组',
                                        'url' => array('/member/weixinGroup/index'),
                                ),
                                array(
                                        'label' => '统计',
                                        'url' => array('/member/weixin/analyze'),
                                ),
                        )),

                array('label' => '微信公众号',
                        'icon' => 'icon-list-alt',
                        'items' => array(
                                array(
                                        'label' => '公众号列表',
                                        'url' => array('/member/weixinAccount/index'),
                                ),
                                array(
                                        'label' => '微信菜单',
                                        'url' => array('/member/weixinMenu/index'),
                                ),
                                array(
                                        'label' => '添加公众号',
                                        'url' => array('/member/weixinAccount/create'),
                                ),
                        )
                ),
                array('label' => '自定义回复管理',
                        'icon' => 'icon-comment',
                        'items' => array(
                                array(
                                        'label' => '关注后自动回复',
                                        'url' => array('/member/material/followResponse'),
                                ),
                                array(
                                        'label' => '地理位置回复',
                                        'url' => array('/member/material/lbsResponse'),
                                ), array(
                                        'label' => '默认回复',
                                        'url' => array('/member/material/defaultResponse'),
                                ),
                                array(
                                        'label' => '自定义回复列表',
                                        'url' => array('/member/material/index'),
                                ),
                                array(
                                        'label' => '添加自定义回复',
                                        'url' => array('/member/material/create'),
                                ),

                        )
                ),
                array('label' => '设置中心',
                        'icon' => 'icon-certificate',
                        'items' => array(
                                array(
                                        'label' => '支付设置',
                                        'url' => array('/member/paymentMethod/index'),
                                ),
                                array(
                                        'label' => '账号信息',
                                        'url' => array('/member/default/setting'),
                                ),

                                array(
                                        'label' => '账号验证',
                                        'url' => array('/member/default/verify'),
                                ),
                                array(
                                        'label' => '密码修改',
                                        'url' => array('/member/default/changePassword'),
                                ),

                        )
                ),
                array('label' => '增值服务',
                        'icon' => 'icon-plus-sign',
                        'items' => array(
                                array(
                                        'label' => '产品列表',
                                        'url' => array('/member/siteProduct/index'),
                                ),
                                array(
                                        'label' => '订单列表',
                                        'url' => array('/member/siteProductOrder/index'),
                                ),

                        )
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


    /**
     * 过滤
     * @return array
     */
    public function filters()
    {
        return array(
                'accessAuth',
                'postOnly + delete',
        );
    }

    /*
     * 动作权限过滤，只能更新删除自身的数据，
     * @param $filterChain
     */
    public function filterAccessAuth($filterChain)
    {
        $actions = array(
                'delete', 'update'
        );

        if (in_array($filterChain->action->id, $actions)) {
            if ($_REQUEST['id']) {
                try {
                    $model = $this->loadModel($_REQUEST['id']);
                    $this->operationControl($model);
                } catch (Exception $e) {
                    $this->error($e->getMessage());
                }

            }
        }
        $filterChain->run();
    }

    /**
     * 限定用户只能操作自已的数据
     * @param $model
     * @throws CHttpException
     */
    private function operationControl($model)
    {
        #$modelName = strtolower(get_class($model));

        #判断店铺
        if (isset($model->shop_id)) {
            if ($model->shop_id != $this->shop->id) {
                throw new CHttpException(500, '你当前没有权限执行本次操作！');
            }
        }

        #判断微信号
        if (isset($model->weixin_account_id)) {
            if ($model->weixin_account_id != $this->account->id) {
                throw new CHttpException(500, '你当前没有权限执行本次操作！');
            }
        }

        #判断用户
        if (isset($model->admin_id)) {
            if ($model->admin_id != Yii::app()->user->id) {
                throw new CHttpException(500, '你当前没有权限执行本次操作！');
            }
        }
    }
}