<?php
/* @var $this ShopDishController */
/* @var $model ShopDish */
?>

<?php
$this->breadcrumbs=array(
	'菜品管理'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List ShopDish', 'url'=>array('index')),
	array('label'=>'Create ShopDish', 'url'=>array('create')),
	array('label'=>'View ShopDish', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage ShopDish', 'url'=>array('admin')),
);
?>

    <h1>更新菜品: <?php echo $model->name; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'category' => $category)); ?>