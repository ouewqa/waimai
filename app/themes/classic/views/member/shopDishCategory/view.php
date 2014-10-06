<?php
/* @var $this ShopDishCategoryController */
/* @var $model ShopDishCategory */
?>

<?php
$this->breadcrumbs=array(
	'Shop Dish Categories'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List ShopDishCategory', 'url'=>array('index')),
	array('label'=>'Create ShopDishCategory', 'url'=>array('create')),
	array('label'=>'Update ShopDishCategory', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete ShopDishCategory', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ShopDishCategory', 'url'=>array('admin')),
);
?>

<h1>View ShopDishCategory #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView',array(
    'htmlOptions' => array(
        'class' => 'table table-striped table-condensed table-hover',
    ),
    'data'=>$model,
    'attributes'=>array(
		'id',
		'shop_id',
		'name',
		'ob',
	),
)); ?>