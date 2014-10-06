<?php
/* @var $this ShopDishAlbumController */
/* @var $model ShopDishAlbum */
?>

<?php
$this->breadcrumbs=array(
	'Shop Dish Albums'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List ShopDishAlbum', 'url'=>array('index')),
	array('label'=>'Create ShopDishAlbum', 'url'=>array('create')),
	array('label'=>'Update ShopDishAlbum', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete ShopDishAlbum', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ShopDishAlbum', 'url'=>array('admin')),
);
?>

<h1>View ShopDishAlbum #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView',array(
    'htmlOptions' => array(
        'class' => 'table table-striped table-condensed table-hover',
    ),
    'data'=>$model,
    'attributes'=>array(
		'id',
		'shop_dish_id',
		'image',
		'description',
	),
)); ?>