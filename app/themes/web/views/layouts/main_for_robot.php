<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <title>深圳微信订餐，深圳微信点餐，深圳微信外卖，深圳微信餐饮管理软件｜<?php echo Yii::app()->name; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- ==============================================
    Favicons
    =============================================== -->
    <link rel="shortcut icon" href="<?php echo Yii::app()->theme->baseUrl; ?>/assets/favicon.ico">
    <link rel="apple-touch-icon" href="<?php echo Yii::app()->theme->baseUrl; ?>/assets/apple-touch-icon.png">
    <link rel="apple-touch-icon"
          sizes="72x72"
          href="<?php echo Yii::app()->theme->baseUrl; ?>/assets/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon"
          sizes="114x114"
          href="<?php echo Yii::app()->theme->baseUrl; ?>/assets/apple-touch-icon-114x114.png">

    <!-- ==============================================
    CSS
    =============================================== -->
    <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/font-awesome.css">
    <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/flexslider.css">
    <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/sequencejs-qubico.css" />
    <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/colorbox.css" />
    <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/qubico.css">
    <link id="main" rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/qubico-cyan.css">
    <link id="theme" rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/qubico-cyan.css">
    <!--<link rel="stylesheet" href="<?php /*echo Yii::app()->theme->baseUrl; */ ?>/css/style-switcher.css">-->


    <!-- ==============================================
    JS
    =============================================== -->

    <!--[if lt IE 9]>
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/respond.min.js"></script>
    <![endif]-->

    <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/libs/modernizr.min.js"></script>


</head>

<body data-spy="scroll" data-target="#main-nav" data-offset="400">
<!-- ==============================================
MAIN NAV
=============================================== -->
<div id="main-nav" class="navbar navbar-fixed-top">
    <div class="container">

        <div class="navbar-header">

            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#site-nav">
                <span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
            </button>

            <!-- ======= LOGO ========-->
            <a class="navbar-brand scrollto" href="#home">
                <span class="logo-small"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/logo-small-cyan.png"
                                              alt="" /></span>
                <span class="to-top"><i class="fa fa-arrow-up"></i></span>
                <span><?php echo Yii::app()->name; ?></span>
                <small>您的互联网营销方案订制专家</small>
                <!-- ==== Image Logo ==== -->
                <!--<img class="site-logo" src="<?php echo Yii::app()->theme->baseUrl; ?>/assets/google-logo.png" alt="logo" />-->
            </a>

        </div>

        <div id="site-nav" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <li class="sr-only">
                    <a href="#home" class="scrollto">主页</a>
                </li>
                <li>
                    <a href="#about" class="scrollto">功能</a>
                </li>
                <li>
                    <a href="#services" class="scrollto">特点</a>
                </li>
                <li>
                    <a href="#clients" class="scrollto">合作案例</a>
                </li>
                <li>
                    <a href="#pricing" class="scrollto">价格</a>
                </li>
                <li>
                    <a href="#aboutus" class="scrollto">关于我们</a>
                </li>
                <li>
                    <a href="#contact" class="scrollto">联系我们</a>
                </li>
                <!--<li>
                    <a class="btn" href="/member/default/index">会员中心</a>
                </li>-->
            </ul>
        </div>
        <!--End navbar-collapse -->

    </div>
    <!--End container -->

</div>
<!--End main-nav -->

<?php echo $content; ?>

<!-- ==============================================
FOOTER
=============================================== -->
<footer id="main-footer" class="color-bg light-typo">

    <div class="container text-center">
        <!--<ul class="social-links">
            <li><a href="#link"><i class="fa fa-twitter fa-fw"></i></a></li>
            <li><a href="#link"><i class="fa fa-facebook fa-fw"></i></a></li>
            <li><a href="#link"><i class="fa fa-google-plus fa-fw"></i></a></li>
            <li><a href="#link"><i class="fa fa-dribbble fa-fw"></i></a></li>
            <li><a href="#link"><i class="fa fa-linkedin fa-fw"></i></a></li>
        </ul>-->
        <p>&copy;2014 <?php echo Yii::app()->name; ?> 深圳市菠柚科技有限公司</p>

        <p>公司地址：深圳市南山区科兴科技园A4-1506 联系电话：15019467705 备案号：闽ICP备07501983号-1</p>

    </div>

</footer>


<!-- ==============================================
SCRIPTS
=============================================== -->
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/libs/jquery-1.8.2.min.js"></script>

<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/libs/bootstrap.min.js"></script>
<script src='<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.easing.1.3.min.js'></script>
<script src='<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.scrollto.js'></script>
<script src='<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.flexslider.min.js'></script>
<script src='<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.fitvids.js'></script>
<script src='<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.fittext.js'></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/waypoints.min.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.countTo.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.easypiechart.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.sequence-min.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.colorbox-min.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/contact.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/qubico.js"></script>
<!--<script src="/js/style-switcher.js"></script>-->

<script>
    $(window).load(function () {
        $('.macbook-screen').html('<iframe width="715" height="410" frameborder=0 src="http://v.qq.com/iframe/player.html?vid=q01355u27e9&tiny=0&auto=0" allowfullscreen></iframe>');
         $('.macbook-screen').fitVids();
    })
</script>

<!--Style Switcher-->
<!--<div id="style-switcher">
    <div id="toggle-switcher"><i class="fa fa-tint"></i></div>
    <h1>切换风格</h1>
    <ul>
        <li id="red"></li>
        <li id="orange"></li>
        <li id="yellow-orange"></li>
        <li id="yellow"></li>
        <li id="grass"></li>
        <li id="green"></li>
        <li id="light-green"></li>
        <li id="cyan"></li>
        <li id="blue"></li>
        <li id="light-purple"></li>
        <li id="purple"></li>
        <li id="pink"></li>
    </ul>
</div>-->
<!--End Style Switcher-->
<div style="display: none">
    <script language="javascript" type="text/javascript" src="http://js.users.51.la/1075059.js"></script>
</div>
</body>

</html>