<?php
/* @var $this SiteProductController */
/* @var $model SiteProduct */
?>

<?php
$this->breadcrumbs=array(
	'Site Products'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List SiteProduct', 'url'=>array('index')),
	array('label'=>'Create SiteProduct', 'url'=>array('create')),
	array('label'=>'View SiteProduct', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage SiteProduct', 'url'=>array('admin')),
);
?>

    <h1>Update SiteProduct <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>