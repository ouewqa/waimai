<?php
/* @var $this WeixinAccountController */
/* @var $model WeixinAccount */
?>

<?php
$this->breadcrumbs = array(
        '微信公众账号' => array('index'),
        $model->name => array('view', 'id' => $model->id),
        '完善',
);

$this->menu = array(
        array('label' => 'List WeixinAccount', 'url' => array('index')),
        array('label' => 'Create WeixinAccount', 'url' => array('create')),
        array('label' => 'View WeixinAccount', 'url' => array('view', 'id' => $model->id)),
        array('label' => 'Manage WeixinAccount', 'url' => array('admin')),
);
?>

    <h1>完善微信公众账号资料</h1>

<?php $this->renderPartial('_form', array('model' => $model)); ?>