<?php
/* @var $this ShopOrderController */
/* @var $model ShopOrder */
?>

<?php
$this->breadcrumbs=array(
	'Shop Orders'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List ShopOrder', 'url'=>array('index')),
	array('label'=>'Create ShopOrder', 'url'=>array('create')),
	array('label'=>'View ShopOrder', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage ShopOrder', 'url'=>array('admin')),
);
?>

    <h1>Update ShopOrder <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>