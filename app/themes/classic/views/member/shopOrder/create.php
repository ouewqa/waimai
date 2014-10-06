<?php
/* @var $this ShopOrderController */
/* @var $model ShopOrder */
?>

<?php
$this->breadcrumbs=array(
	'Shop Orders'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List ShopOrder', 'url'=>array('index')),
	array('label'=>'Manage ShopOrder', 'url'=>array('admin')),
);
?>

<h1>Create ShopOrder</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>