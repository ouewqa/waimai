<?php
/* @var $this ShopController */
/* @var $model Shop */
?>

<?php
$this->breadcrumbs=array(
	'门店管理'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'更新',
);

$this->menu=array(
	array('label'=>'List Shop', 'url'=>array('index')),
	array('label'=>'Create Shop', 'url'=>array('create')),
	array('label'=>'View Shop', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Shop', 'url'=>array('admin')),
);
?>

    <h1>更新门店信息</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>