<?php
/* @var $this WeixinQrcodeController */
/* @var $model WeixinQrcode */
?>

<?php
$this->breadcrumbs=array(
	'Weixin Qrcodes'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List WeixinQrcode', 'url'=>array('index')),
	array('label'=>'Manage WeixinQrcode', 'url'=>array('admin')),
);
?>

<h1>Create WeixinQrcode</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>