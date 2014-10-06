<?php
/* @var $this ShopDishController */
/* @var $model ShopDish */
?>

<?php
$this->breadcrumbs=array(
	'Shop Dishes'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List ShopDish', 'url'=>array('index')),
	array('label'=>'Create ShopDish', 'url'=>array('create')),
	array('label'=>'Update ShopDish', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete ShopDish', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ShopDish', 'url'=>array('admin')),
);
?>

<h1>View ShopDish #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView',array(
    'htmlOptions' => array(
        'class' => 'table table-striped table-condensed table-hover',
    ),
    'data'=>$model,
    'attributes'=>array(
		'id',
		'shop_dish_category_id',
		'name',
		'image',
		'price',
		'discount',
		'description',
	),
)); ?>