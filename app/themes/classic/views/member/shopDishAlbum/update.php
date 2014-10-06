<?php
/* @var $this ShopDishAlbumController */
/* @var $model ShopDishAlbum */
?>

<?php
$this->breadcrumbs=array(
	'菜品相册'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'更新',
);

$this->menu=array(
	array('label'=>'List ShopDishAlbum', 'url'=>array('index')),
	array('label'=>'Create ShopDishAlbum', 'url'=>array('create')),
	array('label'=>'View ShopDishAlbum', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage ShopDishAlbum', 'url'=>array('admin')),
);
?>

    <h1>更新相册</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>