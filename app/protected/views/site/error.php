<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle = Yii::app()->name . ' - Error';
$this->breadcrumbs = array(
        'Error',
);
?>
<style>
    html {
        height: 100%;
    }

    body {
        font-size: 13px;
        line-height: 150%;
        font-family: "Helvetica Neue", Helvetica, Arial, 'Microsoft YaHei', sans-serif;
        color: #666;
        margin: 0px;
        height: 100%;
        background: #f4f4f4;
    }

    .wrap {

        margin: 150px auto 0;
        text-align: center;
    }

    .wrap p {
        font-size: 18px;
    }

    .wrap p a {
        font-size: 14px;
    }

    blockquote {
        margin: 50px auto;
        font-size: 24px;
    }

</style>

<div class="wrap">
    <img src="/images/404-logo.png" />

    <blockquote><?php echo CHtml::encode($message); ?></blockquote>

    <p>错误类型：Error <?php echo $code; ?></p>

    <p><?php echo CHtml::link('返回首页', $this->createUrl('/default')); ?></p>
</div>