<?php
/**
 * 本地开发配置
 */


return CMap::mergeArray(
        require(dirname(__FILE__) . '/main.php'),
        array(
                'modules' => array(
                        'gii' => array(
                                'class' => 'system.gii.GiiModule',
                                'password' => '123456',
                                'ipFilters' => array('127.0.0.1', '::1'),
                                'generatorPaths' => array(
                                        'bootstrap.gii'
                                ),

                        ),
                ),
                'components' => array(
                        'db' => array(
                                'connectionString' => 'mysql:host=127.0.0.1;dbname=waimai', //_development
                                'emulatePrepare' => true,
                                'username' => 'root',
                                'password' => '123456',
                                'charset' => 'utf8',
                                'tablePrefix' => '',
                                'enableProfiling' => true,
                                'enableParamLogging' => true,
                        ),

                        'log' => array(
                                'class' => 'CLogRouter',
                                'routes' => array(
                                        array(
                                                'class' => 'ext.yii-debug-toolbar.YiiDebugToolbarRoute',
                                                'ipFilters' => array('127.0.0.1', '192.168.1.215'),
                                        ),
                                ),
                        ),
                        'cache' => array(
                                'class' => 'system.caching.CFileCache'
                        ),
                        /*'cache' => array(
                                'class' => 'CMemCache',
                                'useMemcached' => false,
                                'servers' => array(
                                        array(
                                                'host' => '127.0.0.1',
                                                'port' => 11211,
                                        ),
                                ),
                        ),*/
                    /*'errorHandler' => array(
                            'errorAction' => 'site/error',
                    ),
                    */
                ),

        )
);