<?php
/* @var $this PaymentMethodController */
/* @var $model PaymentMethod */
?>

<?php
$this->breadcrumbs=array(
	'支付设置'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List PaymentMethod', 'url'=>array('index')),
	array('label'=>'Manage PaymentMethod', 'url'=>array('admin')),
);
?>

<h1>Create PaymentMethod</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>