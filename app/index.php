<?php
header("Content-type: text/html; charset=utf-8");


#判断访问来源
if (strpos($_SERVER["HTTP_USER_AGENT"], "MicroMessenger")) {
    define('ISWEIXINCLIENT', true);
} else {
    define('ISWEIXINCLIENT', false);
}


/*if (!ISWEIXINCLIENT && strstr($_SERVER["HTTP_USER_AGENT"], 'baidu') === false) {
    header('Location: http://www.bo-u.cn/tuangou');
}*/


if (!ini_get('date.timezone')) {
    date_default_timezone_set('Asia/Shanghai');
}

$yii = dirname(__FILE__) . '/../yii-1.1.15.022a51/framework/yii.php';

if (strpos($_SERVER['SERVER_ADDR'], '127') === 0 || $_SERVER['HTTP_USER_AGENT'] == 'debug') { #
    defined('YII_DEBUG') or define('YII_DEBUG', true);
    $config = dirname(__FILE__) . '/protected/config/main-development.php';
} else {
    defined('YII_DEBUG') or define('YII_DEBUG', false);
    $config = dirname(__FILE__) . '/protected/config/main.php';
}


defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);
define('DATELINE', time());


defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);

require_once($yii);
Yii::createWebApplication($config)->run();

#自定义调试函数
function debug()
{
    $arg_list = func_get_args();
    echo '<pre />', PHP_EOL;
    print_r($arg_list);
    exit;
}