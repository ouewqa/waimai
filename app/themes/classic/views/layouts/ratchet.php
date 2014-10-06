<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>

    <!-- Sets initial viewport load and disables zooming  -->
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">

    <!-- Makes your prototype chrome-less once bookmarked to your phone's home screen -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">

    <!--忽略页面中的数字识别为电话号码-->
    <meta name="format-detection" content="telephone=no" />

    <!--去除Android平台中对邮箱地址的识别-->
    <meta name="format-detection" content="email=no" />

    <!-- Include the compiled Ratchet CSS -->
    <link href="/library/ratchet-v2.0.2/css/ratchet.min.css" rel="stylesheet">
    <link href="/library/ratchet-v2.0.2/css/ratchet-theme-ios.min.css" rel="stylesheet">

    <!-- Include the compiled Ratchet JS -->
    <script src="/library/ratchet-v2.0.2/js/ratchet.min.js"></script>
    <?php Yii::app()->clientScript->registerCoreScript('zepto'); ?>
</head>
<body>
<?php echo $content; ?>
</body>
</html>