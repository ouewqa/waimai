<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
        'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
        'name' => 'My Web Application',

        'language' => 'zh_cn', //默认语言
        'defaultController' => 'default', //默认模块　
        'charset' => 'utf-8',


        'preload' => array('log'),

        'aliases' => array(
                'bootstrap' => 'ext.yiistrap-master', #Version 1.2.0
                'yiiwheels' => 'ext.yiiwheels', #Version 1.0.3
        ),

        'import' => array(
                'application.models.*',
                'application.components.*',
                'application.helpers.*',
                'application.vendors.*',
                'application.forms.*',

            #拓展
                'bootstrap.helpers.*',
                'bootstrap.behaviors.*',
                'bootstrap.widgets.*',
                'bootstrap.components.*',


                'ext.YiiMailer.YiiMailer',
        ),

        'modules' => array(
                'default',
                'member',
                'weixin',
                'console',
                'auth' => array(
                        'strictMode' => false, // when enabled authorization items cannot be assigned children of the same type.
                        'userClass' => 'Admin', // the name of the user model class.
                        'userIdColumn' => 'id', // the name of the user id column.
                        'userNameColumn' => 'username', // the name of the user name column.
                    //'appLayout' => 'webroot.themes.console.views.layouts.main', // the layout used by the module.
                        'appLayout' => 'application.models.auth.views.layouts.main', // the layout used by the module.
                        'viewDir' => null, // the path to view files to use with this module.
                ),

        ),

    // application components
        'components' => array(
                'alipay' => array(
                        'class' => 'application.vendors.alipay.AlipayProxy',
                        'key' => 'z92pgvbjpe1up86kuuhhfiic3i0y70zw',
                        'partner' => '2088002159770241',
                        'seller_email' => 'imsave@gmail.com',
                        'return_url' => '/member/public/alipayReturn',
                        'notify_url' => '/member/public/alipayNotify',
                        'show_url' => '',
                ),
                'tenpay' => array(
                        'class' => 'application.vendors.tenpay.TenpayProxy',
                        'partner' => '2088801162173***', // your partner id
                        'key' => '***om2l8gxuvca9gtniqbextf4y66***', // your key
                        'seller_id' => '6772017@qq.com',
                        'return_url' => '/member/public/tenpayReturn',
                        'notify_url' => '/member/public/tenpayNotify',
                        'show_url' => '',
                ),

                'user' => array(
                        'class' => 'application.components.AuthWebUser',
                        'admins' => array(''),
                        'allowAutoLogin' => true,
                ),

                'authManager' => array(
                        'class' => 'auth.components.CachedDbAuthManager',
                        'defaultRoles' => array('member'),
                        'cachingDuration' => 0,
                        'itemTable' => 'authitem',
                        'itemChildTable' => 'authitemchild',
                        'assignmentTable' => 'authassignment',
                        'behaviors' => array(
                                'auth' => array(
                                        'class' => 'auth.components.AuthBehavior',
                                ),
                        ),
                ),
                'urlManager' => array(
                        'urlFormat' => 'path',
                        'showScriptName' => false,
                        'urlSuffix' => '',
                        'caseSensitive' => true,
                        'rules' => array(
                                'sites' => 'site/index',


                            #微信支付相关
                                'public/wxPay/<do:>' => 'public/wxPay',


                                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                        ),
                ),
                'db' => array(
                        'connectionString' => 'mysql:host=localhost;dbname=waimai',
                        'emulatePrepare' => true,
                        'username' => 'root',
                        'password' => '123456',
                        'charset' => 'utf8',

                        'schemaCachingDuration' => 86400, //数据表结构缓存时间


                        'tablePrefix' => '', //表名前缀
                        'enableProfiling' => true, //设置是否在调试中打印sql
                        'enableParamLogging' => true, //设置是否打印sql中所用的参数
                ),

                'errorHandler' => array(
                        'errorAction' => 'site/error',
                ),
                'log' => array(
                        'class' => 'CLogRouter',
                        'routes' => array(
                                array(
                                        'class' => 'CFileLogRoute',
                                        'levels' => 'trace, error, warning',
                                ),
                                array(
                                        'class' => 'EmailLogRoute',
                                        'levels' => 'warning', #所有异常的错误级别均为error　, warning,
                                    //'filter'=>'CLogFilter',
                                        'emails' => '6202551@qq.com',
                                        'sentFrom' => '6202551@qq.com',
                                        'subject' => 'Bo-u网站报错啦！',
                                ),
                            // uncomment the following to show log messages on web pages
                            /*
                            array(
                                'class'=>'CWebLogRoute',
                            ),
                            */
                        ),
                ),
                'bootstrap' => array(
                        'class' => 'bootstrap.components.TbApi',
                ),
                'yiiwheels' => array(
                        'class' => 'yiiwheels.YiiWheels',
                ),
        ),

    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
        'params' => array(
                'version' => 'v1.31',
                'telephone' => '0755-33202122',
                'mobile' => '15766088661',
                'email' => 'service@bo-u.cn',
        ),
);