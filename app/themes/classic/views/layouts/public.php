<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" xmlns:wb="http://open.weibo.com/wb">
<head>
    <meta charset="utf-8">
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    Yii::app()->bootstrap->register();
    ?>
    <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/login.css">
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script type="text/javascript" src="/js/html5.js"></script>
    <![endif]-->
    <style>
        h1 {
            margin-top: 50px;

            font: 28px "Microsoft YaHei", "微软雅黑", "MicrosoftJhengHei", Helvetica, Arial, sans-serif;
            font-weight: 300;
            overflow-x: hidden;
        }

        h1 a {
            color: #777;
        }

        h1 a:hover {
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <a class="brand" href="/"><img src="/themes/web/assets/logo-small-cyan.png"></a>

            <h1><a href="/"><?php echo Yii::app()->name; ?></a></h1>
        </div>
    </div>
</div>

<div class="container">
    <?php echo $content; ?>
</div>
<script type="text/javascript" src="/js/backstretch.min.js"></script>
<script>
    jQuery(document).ready(function ($) {
        $.backstretch([
            "<?php echo Yii::app()->theme->baseUrl; ?>/images/non_member/bg1.png",
            "<?php echo Yii::app()->theme->baseUrl; ?>/images/non_member/bg2.png"
        ], {duration: 2000, fade: 750});

    });
</script>
</body>
</html>
