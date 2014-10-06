<?php
/* @var $this WeixinAccountController */
/* @var $model WeixinAccount */
?>

<?php
$this->breadcrumbs = array(
        '微信公众账号' => array('index'),
        '添加',
);

$this->menu = array(
        array('label' => 'List WeixinAccount', 'url' => array('index')),
        array('label' => 'Manage WeixinAccount', 'url' => array('admin')),
);
?>

    <h1>添加微信公众账号</h1>
    <blockquote>
        <p>
            微信公众账号注册地址：<?php echo CHtml::link('点击注册', 'https://mp.weixin.qq.com/cgi-bin/readtemplate?t=register/step1_tmpl&lang=zh_CN'); ?>
        </p>
    </blockquote>

<?php $this->renderPartial('_form', array('model' => $model)); ?>