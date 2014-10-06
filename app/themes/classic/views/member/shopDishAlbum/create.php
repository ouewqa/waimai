<?php
/* @var $this ShopDishAlbumController */
/* @var $model ShopDishAlbum */
?>

<?php
$this->breadcrumbs=array(
	'菜品相册'=>array('index'),
	'创建',
);

$this->menu=array(
	array('label'=>'List ShopDishAlbum', 'url'=>array('index')),
	array('label'=>'Manage ShopDishAlbum', 'url'=>array('admin')),
);
?>

<h1>Create ShopDishAlbum</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>