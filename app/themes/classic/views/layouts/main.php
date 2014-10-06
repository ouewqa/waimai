<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" xmlns:wb="http://open.weibo.com/wb">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="language" content="en" />
    <meta name="viewport"
          content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <?php
    Yii::app()->bootstrap->register();
    Yii::app()->clientScript->registerCoreScript('cookie');
    ?>
    <script type="text/javascript" src="/js/common.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/style.css" />
</head>

<body>

<?php if (isset($this->navbar) && $this->navbar) {
    $this->widget('bootstrap.widgets.TbNavbar', $this->navbar);
}?>

<!--<div class="container">-->
<div class="container">
    <?php echo $content; ?>
    <div class="clear"></div>
    <footer>

    </footer>
</div>
<div id="51dotla" style="display:none;">
    <script language="javascript" type="text/javascript" src="http://js.users.51.la/17154225.js"></script>
</div>
</body>
</html>
